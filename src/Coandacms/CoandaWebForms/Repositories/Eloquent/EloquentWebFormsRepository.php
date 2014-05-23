<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent;

use Coanda, Paginator, DB;

use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\FormField as FormFieldModel;
use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\Submission as SubmissionModel;
use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\SubmissionField as SubmissionFieldModel;

use CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface;

use CoandaCMS\Coanda\Exceptions\ValidationException;

/**
 * Class EloquentWebFormsRepository
 * @package CoandaCMS\CoandaWebForms\Repositories\Eloquent
 */
class EloquentWebFormsRepository implements WebFormsRepositoryInterface {

    /**
     * @var Models\FormField
     */
    private $form_field_model;
    /**
     * @var Models\Submission
     */
    private $submission_model;
    /**
     * @var Models\SubmissionField
     */
    private $submission_field_model;

    /**
     * @param FormFieldModel $form_field_model
     * @param SubmissionModel $submission_model
     * @param SubmissionFieldModel $submission_field_model
     */
    public function __construct(FormFieldModel $form_field_model, SubmissionModel $submission_model, SubmissionFieldModel $submission_field_model)
	{
		$this->form_field_model = $form_field_model;
		$this->submission_model = $submission_model;
		$this->submission_field_model = $submission_field_model;
	}

    /**
     * @param $per_page
     * @param $page
     * @return mixed
     */
    public function formpages($per_page, $page)
	{
		$offset = ($page - 1) * $per_page;

		$page_ids = $this->submission_model->distinct('page_id')->skip($offset)->take($per_page)->lists('page_id');
		$count = DB::select('SELECT COUNT(distinct page_id) as page_count FROM webformsubmissions as page_count');

		$formpages = [];

		foreach ($page_ids as $page_id)
		{
			$formpages[] = [
				'page' => Coanda::module('pages')->getPage($page_id),
				'submissions' => $this->submissionCountForPage($page_id)
			];
		}

		return Paginator::make($formpages, $count[0]->page_count, $per_page);
	}

    /**
     * @param $page_id
     * @return mixed
     */
    private function submissionCountForPage($page_id)
	{
		return $this->submission_model->wherePageId($page_id)->count();
	}

    /**
     * @param $page_id
     * @param $per_page
     * @return mixed
     */
    public function submissions($page_id, $per_page)
	{
		return $this->submission_model->wherePageId($page_id)->paginate($per_page);
	}

    /**
     * @param $submission_id
     * @return mixed
     */
    public function submission($submission_id)
	{
		return $this->submission_model->find($submission_id);
	}

    /**
     * @param $page_id
     * @param $version_number
     * @return mixed
     */
    public function formFields($page_id, $version_number)
	{
		return $this->form_field_model->wherePageId($page_id)->whereVersionNumber($version_number)->orderBy('order', 'asc')->get();
	}

    /**
     * @param $type
     * @param $page_id
     * @param $version_number
     * @return mixed
     */
    public function addFormField($type, $page_id, $version_number)
	{
		$form_field_data = [
			'type' => $type,
			'page_id' => $page_id,
			'version_number' => $version_number
		];

		return $this->form_field_model->create($form_field_data);
	}

    /**
     * @param $form_field_id
     */
    public function removeFormField($form_field_id)
	{
		$field = $this->form_field_model->find($form_field_id);

        if ($field)
        {
            $field->delete();
        }
	}

    /**
     * @param $data
     * @throws MissingWebFormDataException
     * @throws \CoandaCMS\Coanda\Exceptions\ValidationException
     */
    public function storeSubmission($data)
	{
        // Get the page
        $page_id = isset($data['page_id']) ? $data['page_id'] : false;
        $version = isset($data['version']) ? $data['version'] : false;
        $location_id = isset($data['location_id']) ? $data['location_id'] : false;

        if ($page_id && $location_id)
        {
	        $page = Coanda::module('pages')->getPage($page_id);
	        $location = Coanda::module('pages')->getLocation($location_id);

	        if ($page && $location)
	        {
	        	$version_number = $version ? $version : $page->current_version;

	            $fields = $this->formFields($page->id, $version_number);
	            $invalid_fields = [];

	            foreach ($fields as $field)
	            {
	            	$field_data = isset($data['field_' . $field->id]) ? $data['field_' . $field->id] : false;

	            	if ($field->required && !$field_data)
	            	{
	            		$invalid_fields['field_' . $field->id] = $field->label . ' is required';
	            	}

	            	$type_validation_method = camel_case('validate-' . $field->type . '-field-type');

	            	if (method_exists($this, $type_validation_method))
	            	{
	            		$validation_result = $this->$type_validation_method($field, $field_data);

	            		if ($validation_result)
	            		{
	            			$invalid_fields['field_' . $field->id] = $validation_result;
	            		}
	            	}
	            }

	            if (count($invalid_fields) > 0)
	            {
	            	// dd($invalid_fields);
	            	throw new ValidationException($invalid_fields);
	            }

            	// Creat the submission
            	$submission_data = [
            		'page_id' => $page->id,
            		'version_number' => $version_number
            	];

            	$submission = $this->submission_model->create($submission_data);

            	foreach ($fields as $field)
            	{
            		$field_data = isset($data['field_' . $field->id]) ? $data['field_' . $field->id] : false;

            		$submission_field_data = [
            			'submission_id' => $submission->id,
            			'field_id' => $field->id,
            			'label' => $field->label,
            			'type' => $field->type,
            			'field_data' => $field_data
            		];

            		$submission_field = $this->submission_field_model->create($submission_field_data);
            	}
	        }
	        else
	        {
	        	throw new MissingWebFormDataException('Unable to find submitted page or location');
	        }
        }
        else
        {
        	throw new MissingWebFormDataException('Missing page_id or location_id from data');
        }
	}

    /**
     * @param $field
     * @param $data
     * @return string
     */
    private function validateEmailFieldType($field, $data)
	{
		if (!filter_var($data, FILTER_VALIDATE_EMAIL))
		{
			return 'Please enter a valid email address';
		}
	}

    /**
     * @param $field
     * @param $data
     * @return string
     */
    private function validateNumberFieldType($field, $data)
	{
		$field_type_data = $field->typeData();

		if ((isset($field_type_data['number_min']) && $field_type_data['number_min'] > 0) && (isset($field_type_data['number_max']) && $field_type_data['number_max'] > 0))
		{
			if ($data < $field_type_data['number_min'] || $data > $field_type_data['number_max'])
			{
				return 'Please enter a value between ' . $field_type_data['number_min'] . ' and ' . $field_type_data['number_max'];
			}
		}
		elseif ((isset($field_type_data['number_min']) && $field_type_data['number_min'] > 0))
		{
			if ($data < $field_type_data['number_min'])
			{
				return 'Please enter a value greater than ' . $field_type_data['number_min'];
			}
		}
		elseif ((isset($field_type_data['number_max']) && $field_type_data['number_max'] > 0))
		{
			if ($data > $field_type_data['number_max'])
			{
				return 'Please enter a value less than ' . $field_type_data['number_max'];
			}
		}
	}
}