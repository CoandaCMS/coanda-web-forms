<?php namespace CoandaCMS\CoandaWebForms\Repositories;

/**
 * Interface WebFormsRepositoryInterface
 * @package CoandaCMS\CoandaWebForms\Repositories
 */
interface WebFormsRepositoryInterface {

    /**
     * @param $per_page
     * @param $page
     * @return mixed
     */
    public function formpages($per_page, $page);

    /**
     * @param $page_id
     * @param $per_page
     * @return mixed
     */
    public function submissions($page_id, $per_page);

    /**
     * @param $submission_id
     * @return mixed
     */
    public function submission($submission_id);

    /**
     * @param $page_id
     * @param $version_number
     * @return mixed
     */
    public function formFields($page_id, $version_number);

    /**
     * @param $type
     * @param $page_id
     * @param $version_number
     * @return mixed
     */
    public function addFormField($type, $page_id, $version_number);

    /**
     * @param $form_field_id
     * @return mixed
     */
    public function removeFormField($form_field_id);

    /**
     * @param $data
     * @return mixed
     */
    public function storeSubmission($data);

}
