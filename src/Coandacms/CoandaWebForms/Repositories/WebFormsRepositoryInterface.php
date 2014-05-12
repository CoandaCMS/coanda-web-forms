<?php namespace CoandaCMS\CoandaWebForms\Repositories;

interface WebFormsRepositoryInterface {

    public function formFields($page_id, $version_number);

    public function addFormField($type, $page_id, $version_number);

    public function removeFormField($form_field_id);

}
