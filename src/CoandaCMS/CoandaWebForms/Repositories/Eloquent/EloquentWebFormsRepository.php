<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent;

use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeConfigurationException;
use Input, Coanda;

use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebForm as WebFormModel;
use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebFormField as WebFormFieldModel;
use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebFormDownload as WebFormDownloadModel;

use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\Submission as SubmissionModel;
use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\SubmissionField as SubmissionFieldModel;

use CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface;
use CoandaCMS\CoandaWebForms\Exceptions\WebFormNotFoundException;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;
use CoandaCMS\CoandaWebForms\Exceptions\SubmissionNotFoundException;

use CoandaCMS\Coanda\Exceptions\ValidationException;

class EloquentWebFormsRepository implements WebFormsRepositoryInterface {

    /**
     * @var WebFormModel
     */
    private $web_form_model;

    /**
     * @var WebFormFieldModel
     */
    private $web_form_field_model;

    /**
     * @var WebFormModel
     */
    private $web_form_download_model;

    /**
     * @var SubmissionModel
     */
    private $submission_model;

    /**
     * @var SubmissionFieldModel
     */
    private $submission_field_model;

    /**
     * @param WebFormModel $web_form_model
     * @param WebFormFieldModel $web_form_field_model
     * @param SubmissionModel $submission_model
     * @param SubmissionFieldModel $submission_field_model
     */
    public function __construct(WebFormModel $web_form_model, WebFormFieldModel $web_form_field_model, WebFormDownloadModel $web_form_download_model, SubmissionModel $submission_model, SubmissionFieldModel $submission_field_model)
	{
		$this->web_form_model = $web_form_model;
		$this->web_form_field_model = $web_form_field_model;
        $this->web_form_download_model = $web_form_download_model;

		$this->submission_model = $submission_model;
		$this->submission_field_model = $submission_field_model;
	}

    /**
     * @param $per_page
     * @return mixed
     */
    public function forms($per_page)
	{
		return $this->web_form_model->paginate($per_page);
	}

    /**
     * @return mixed
     */
    public function formlist()
	{
		return $this->web_form_model->get();
	}

    /**
     * @param $data
     * @return mixed
     * @throws ValidationException
     */
    public function createForm($data)
	{
		$invalid_fields = [];

		if (!isset($data['name']) || $data['name'] == '')
		{
			$invalid_fields['name'] = 'Please enter a name';
		}

		if (count($invalid_fields) > 0)
		{
			throw new ValidationException($invalid_fields);
		}

		return $this->web_form_model->create(['name' => $data['name']]);
	}

    /**
     * @param $id
     * @return mixed
     * @throws WebFormNotFoundException
     */
    public function getForm($id)
	{
		$form = $this->web_form_model->find($id);

		if (!$form)
		{
			throw new WebFormNotFoundException('Form #' . $id . ' does not exists.');
		}

		return $form;
	}

    /**
     * @param $id
     * @param $data
     * @throws ValidationException
     * @throws WebFormNotFoundException
     */
    public function storeForm($id, $data)
	{
		$form = $this->getForm($id);

		$invalid_fields = [];

		if (!isset($data['name']) || $data['name'] == '')
		{
			$invalid_fields['name'] = 'Please enter a name for this form';
		}
		else
		{
			$form->name = $data['name'];
            $form->submitted_message = $data['submitted_message'];
            $form->enable_recaptcha = (isset($data['enable_recaptcha']) && $data['enable_recaptcha'] == 'yes') ? true : false;
			$form->save();
		}

		foreach ($form->fields as $field)
		{
			$field->label = isset($data['field_' . $field->id . '_label']) ? $data['field_' . $field->id . '_label'] : $field->label;
			$field->order = isset($data['field_' . $field->id . '_order']) ? $data['field_' . $field->id . '_order'] : $field->order;
			$field->columns = isset($data['field_' . $field->id . '_columns']) ? $data['field_' . $field->id . '_columns'] : $field->columns;

			if ($field->columns > 12)
			{
				$field->columns = 12;
			}

			$field->required = isset($data['field_' . $field->id . '_required']) && $data['field_' . $field->id . '_required'] == 'true';

            try
            {
                $field->setTypeData(isset($data['field_' . $field->id . '_custom']) ? $data['field_' . $field->id . '_custom'] : []);
            }
            catch (FieldTypeConfigurationException $exception)
            {
                $invalid_fields['field_' . $field->id]['label'] = $exception->getMessage();
            }

			$field->save();

			if ($field->label == '')
			{
				$invalid_fields['field_' . $field->id]['label'] = 'Please enter a label for this field';
			}			
		}

		if (count($invalid_fields) > 0)
		{
			throw new ValidationException($invalid_fields);
		}

		$post_submit_handlers = [];

		if (isset($data['post_submit_handlers']) && count($data['post_submit_handlers']) > 0)
		{
			foreach($data['post_submit_handlers'] as $post_submit_handler)
			{
				$handler = Coanda::webforms()->postSubmitHandler($post_submit_handler);

				if ($handler)
				{
					$handler_data = isset($data['post_submit_handler_data'][$handler->identifier()]) ? $data['post_submit_handler_data'][$handler->identifier()] : [];

					$post_submit_handlers[$handler->identifier()] = $handler->storeAdmin($handler_data);
				}
			}
		}

		if (count($post_submit_handlers) > 0)
		{
			$form->post_submit_handler_data = json_encode($post_submit_handlers);
		}
		else
		{
			$form->post_submit_handler_data = '';
		}

		$form->save();
	}

    /**
     * @param $form_id
     * @param $field_type
     * @throws WebFormNotFoundException
     */
    public function addField($form_id, $field_type)
	{
		$form = $this->getForm($form_id);

		$field = new $this->web_form_field_model;
		$field->type = $field_type;
		$field->order = $this->web_form_field_model->where('webform_id', '=', $form->id)->max('order') + 1;

		$form->fields()->save($field);
	}

    /**
     * @param $form_id
     * @param $field_type
     * @param $label
     * @param $required
     * @param $columns
     * @param $custom_data
     * @throws WebFormNotFoundException
     */
    public function addFieldFull($form_id, $field_type, $label, $required, $columns, $custom_data)
	{
		$form = $this->getForm($form_id);

		$field = new $this->web_form_field_model;
		$field->type = $field_type;
		$field->order = $this->web_form_field_model->where('webform_id', '=', $form->id)->max('order') + 1;

		$field->label = $label;
		$field->required = $required;
		$field->columns = $columns;

		$field->setTypeData($custom_data);

		$form->fields()->save($field);
	}

    /**
     * @param $form
     * @param $handler_identifier
     * @param array $handler_data
     */
    public function addPostSubmitHandler($form, $handler_identifier, $handler_data = [])
	{
		$post_submit_handlers = json_decode($form->post_submit_handler_data, true);

		if (!is_array($post_submit_handlers))
		{
			$post_submit_handlers = [];
		}

		$handler = Coanda::webforms()->postSubmitHandler($handler_identifier);

		if ($handler)
		{
			$handler_data = isset($handler_data) ? $handler_data : [];

			$post_submit_handlers[$handler->identifier()] = $handler->storeAdmin($handler_data);
		}

		$form->post_submit_handler_data = json_encode($post_submit_handlers);
		$form->save();
	}

    /**
     * @param $form_id
     * @param $field_id
     * @throws WebFormNotFoundException
     */
    public function removeField($form_id, $field_id)
	{
		$form = $this->getForm($form_id);

		$form->fields()->whereId($field_id)->delete();
	}

	/**
	 * @param $form
	 * @param bool $limit
	 * @return array
	 */
    public function dataHeadings($form, $limit = false)
	{
		$headings = [];

		foreach ($form->dataHeadings($limit) as $field)
		{
			$headings[$field->identifier.'-'.$field->id] = $field->label;
		}

		return $headings;
	}

    /**
     * @return bool|string
     */
    private function getUserIP()
	{
		if (getenv('HTTP_CLIENT_IP'))
		{
			$ip = getenv('HTTP_CLIENT_IP');
		}
		else if (getenv('HTTP_X_FORWARDED_FOR'))
		{
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		else if (getenv('REMOTE_ADDR'))
		{
			$ip = getenv('REMOTE_ADDR');
		}
		else
		{
			$ip = false;
		}

		return $ip;
	}

    /**
     * @param $response
     * @return bool
     */
    private function verifyRecaptcha($response)
	{
		$peer_key = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';
        
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query([
                	'secret' => \Config::get('coanda-web-forms::recaptcha_secret_key'),
                	'response' => $response,
                	'remoteip' => $this->getUserIP()
                ]),
                'verify_peer' => true,
                $peer_key => 'www.google.com',
            ),
        );
        
        $context = stream_context_create($options);
        $result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);

		$process_result = json_decode($result, true);

		return isset($process_result['success']) ? $process_result['success'] : false;
	}

    /**
     * @param $data
     * @param $page_id
     * @throws ValidationException
     */
    public function storeSubmission($data, $page_id)
	{
		if (!isset($data['form_id']))
		{
			throw new ValidationException(['form' => 'Missing form_id']);
		}

		try
		{
			$form = $this->getForm($data['form_id']);

			$invalid_fields = [];
			$field_values = [];

			if ($form->enable_recaptcha)
			{
				if (!$this->verifyRecaptcha($data['g-recaptcha-response']))
				{
					$invalid_fields['recaptcha'] = 'reCAPTCHA could not be verified';
				}
			}

			foreach ($form->fields as $field)
			{
				if (!$field->canStore())
				{
					continue;
				}

				$field_data = isset($data['field_' . $field->id]) ? $data['field_' . $field->id] : false;

				try
				{
					$field_values['field_' . $field->id] = $field->handleSubmissionData($field_data);
				}
				catch (FieldTypeRequiredException $exception)
				{
					$invalid_fields['field_' . $field->id] = $exception->getMessage();
				}
			}

			if (count($invalid_fields) > 0)
			{
				throw new ValidationException($invalid_fields);
			}

			$submission = $this->submission_model->create([
					'form_id' => $form->id,
					'page_id' => $page_id
				]);

			foreach ($form->fields as $field)
			{
				if (!$field->canStore())
				{
					continue;
				}

				$submission_field = new $this->submission_field_model;

				$submission_field->label = $field->label;
                $submission_field->identifier = $field->identifier;
				$submission_field->field_id = $field->id;
				$submission_field->type = $field->type;
				$submission_field->field_data = $field_values['field_' . $field->id];

				$submission->fields()->save($submission_field);
			}
		}
		catch (WebFormNotFoundException $exception)
		{
			throw new ValidationException(['form' => 'Error fetching form']);
		}
	}

    /**
     * @param $id
     * @return mixed
     * @throws SubmissionNotFoundException
     */
    public function getSubmission($id)
	{
		$submission = $this->submission_model->find($id);

		if (!$submission)
		{
			throw new SubmissionNotFoundException('Submission #' . $id . ' does not exists.');
		}

		return $submission;
	}

    /**
     * @param $limit
     * @return mixed
     */
    public function getUnprocessesSubmissions($limit)
	{
		return $this->submission_model->where('post_submit_handler_executed', '=', 0)->take($limit)->get();
	}

	/**
	 * @param $form_id
	 * @param $offset
	 * @param $limit
	 * @param bool $from
	 * @param bool $to
	 * @return mixed
     */
	public function getSubmissions($form_id, $offset = false, $limit = false, $from = false, $to = false, $count = false)
	{
		$query = $this
			->submission_model
			->where('form_id', '=', $form_id);

        if ($offset) {
            $query->skip($offset);
        }
		
        if ($limit) {
			$query->take($limit);
        }

		if ($from) {
			$query->where('created_at' , '>', $from);
		}

		if ($to) {
			$query->where('created_at' , '<', $to);
		}

        if ($count) {
            return $query->count();
        }

		return $query->get();
	}

    /**
     * @param  array $data
     * @return WebFormDownload       
     */
    public function createDownload($data)
    {
        return $this->web_form_download_model->create($data);
    }

    /**
     * @param  int $download_id
     * @return WebFormDownload       
     */
    public function getDownload($download_id)
    {
        return $this->web_form_download_model->find($download_id);
    }

    /**
     * Update Download Percentage
     * 
     * @param  int $download_id 
     * @param  int $percentage  
     * 
     * @return boolean  Success or failure
     */
    public function updateDownloadPercentage($download_id, $percentage)
    {
        $webFormDownload =  $this->web_form_download_model->find($download_id);

        if ($webFormDownload) {
            $webFormDownload->status_percentage = $percentage;
            $webFormDownload->save();

            return true;
        }

        return false;
    }
}