<?php namespace CoandaCMS\CoandaWebForms;

abstract class PostSubmitHandler {

    /**
     * @return mixed
     */
    abstract public function name();

    /**
     * @return mixed
     */
    abstract public function identifier();

    /**
     * @return string
     */
    public function adminTemplate()
    {
    	return 'coanda-web-forms::admin.postsubmithandlers.' . $this->identifier();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function storeAdmin($data)
    {
    	return $data;
    }

    /**
     * @param $submission
     * @param $data
     */
    public function process($submission, $data)
    {
    }
}