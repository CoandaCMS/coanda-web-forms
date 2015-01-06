<?php namespace CoandaCMS\CoandaWebForms\Repositories;

interface WebFormsRepositoryInterface {

    /**
     * @param $per_page
     * @return mixed
     */
    public function forms($per_page);

    /**
     * @param $data
     * @return mixed
     */
    public function createForm($data);

    /**
     * @param $form_id
     * @param $field_type
     * @return mixed
     */
    public function addField($form_id, $field_type);

    /**
     * @param $form_id
     * @param $field_id
     * @return mixed
     */
    public function removeField($form_id, $field_id);

    /**
     * @param $form
     * @param $limit
     * @return mixed
     */
    public function dataHeadings($form, $limit);

    /**
     * @param $data
     * @param $page_id
     * @return mixed
     */
    public function storeSubmission($data, $page_id);

    /**
     * @param $id
     * @return mixed
     */
    public function getSubmission($id);

    /**
     * @param $limit
     * @return mixed
     */
    public function getUnprocessesSubmissions($limit);

    /**
     * @param $form_id
     * @param $offset
     * @param $limit
     * @param $from
     * @param $to
     * @return mixed
     */
    public function getSubmissions($form_id, $offset, $limit, $from, $to);
}
