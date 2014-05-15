<?php namespace CoandaCMS\CoandaWebForms\Repositories;

interface WebFormsRepositoryInterface {

	public function formpages($per_page, $page);

	public function submissions($page_id, $per_page);

	public function submission($submission_id);

    public function formFields($page_id, $version_number);

    public function addFormField($type, $page_id, $version_number);

    public function removeFormField($form_field_id);
    
    public function storeSubmission($data);

}
