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
}
