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
    public function forms($per_page);

    public function createForm($data);

    public function addField($form_id, $field_type);
}
