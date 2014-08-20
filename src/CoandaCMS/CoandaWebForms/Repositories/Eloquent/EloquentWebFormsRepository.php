<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent;

use Input, Coanda;

use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebForm as WebFormModel;
use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebFormField as WebFormFieldModel;

use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\Submission as SubmissionModel;
use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\SubmissionField as SubmissionFieldModel;

use CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface;
use CoandaCMS\CoandaWebForms\Exceptions\WebFormNotFoundException;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;
use CoandaCMS\CoandaWebForms\Exceptions\SubmissionNotFoundException;

use CoandaCMS\Coanda\Exceptions\ValidationException;

/**
 * Class EloquentWebFormsRepository
 * @package CoandaCMS\CoandaWebForms\Repositories\Eloquent
 */
class EloquentWebFormsRepository implements WebFormsRepositoryInterface {

    private $web_form_model;
    private $web_form_field_model;

    private $submission_model;
    private $submission_field_model;

    public function __construct(WebFormModel $web_form_model, WebFormFieldModel $web_form_field_model, SubmissionModel $submission_model, SubmissionFieldModel $submission_field_model)
	{
		$this->web_form_model = $web_form_model;
		$this->web_form_field_model = $web_form_field_model;

		$this->submission_model = $submission_model;
		$this->submission_field_model = $submission_field_model;
	}

    /**
     * @param $per_page
     * @param $page
     * @return mixed
     */
    public function forms($per_page)
	{
		return $this->web_form_model->paginate($per_page);
	}

    public function formlist()
	{
		return $this->web_form_model->get();
	}

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

	public function getForm($id)
	{
		$form = $this->web_form_model->find($id);

		if (!$form)
		{
			throw new WebFormNotFoundException('Form #' . $id . ' does not exists.');
		}

		return $form;
	}

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

			if (isset($data['field_' . $field->id . '_custom']))
			{
				$field->setTypeData($data['field_' . $field->id . '_custom']);
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

		$form->save();
	}

	public function addField($form_id, $field_type)
	{
		$form = $this->getForm($form_id);

		$field = new $this->web_form_field_model;
		$field->type = $field_type;
		$field->order = $this->web_form_field_model->where('webform_id', '=', $form->id)->max('order') + 1;

		$form->fields()->save($field);
	}

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

	public function removeField($form_id, $field_id)
	{
		$form = $this->getForm($form_id);

		$form->fields()->whereId($field_id)->delete();
	}

	public function fieldHeadings($form)
	{
		$headings = [];

		foreach ($form->firstFiveFields() as $field)
		{
			$headings[] = $field->label;
		}

		return $headings;
	}

	public function storeSubmission($data, $location_id)
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
					'location_id' => $data['location_id']
				]);

			foreach ($form->fields as $field)
			{
				if (!$field->canStore())
				{
					continue;
				}

				$submission_field = new $this->submission_field_model;

				$submission_field->label = $field->label;
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

	public function getSubmission($id)
	{
		$submission = $this->submission_model->find($id);

		if (!$submission)
		{
			throw new SubmissionNotFoundException('Submission #' . $id . ' does not exists.');
		}

		return $submission;
	}

	public function getUnprocessesSubmissions($limit)
	{
		return $this->submission_model->where('post_submit_handler_executed', '=', 0)->take($limit)->get();
	}

}