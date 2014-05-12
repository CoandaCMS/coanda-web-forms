<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent;

use Coanda;

use CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\FormField as FormFieldModel;

use CoandaCMS\CoandaWebForms\Repositories\WebFormsRepositoryInterface;

class EloquentWebFormsRepository implements WebFormsRepositoryInterface {

    private $form_field_model;

    public function __construct(FormFieldModel $form_field_model)
	{
		$this->form_field_model = $form_field_model;
	}

	public function formFields($page_id, $version_number)
	{
		return $this->form_field_model->wherePageId($page_id)->whereVersionNumber($version_number)->orderBy('order', 'asc')->get();
	}

	public function addFormField($type, $page_id, $version_number)
	{
		$form_field_data = [
			'type' => $type,
			'page_id' => $page_id,
			'version_number' => $version_number
		];

		return $this->form_field_model->create($form_field_data);
	}

	public function removeFormField($form_field_id)
	{
		$field = $this->form_field_model->find($form_field_id);

        if ($field)
        {
            $field->delete();
        }
	}
}