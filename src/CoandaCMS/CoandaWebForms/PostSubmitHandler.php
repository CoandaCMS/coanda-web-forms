<?php namespace CoandaCMS\CoandaWebForms;

abstract class PostSubmitHandler {

    abstract public function name();

    abstract public function identifier();

    public function adminTemplate()
    {
    	return 'coanda-web-forms::admin.postsubmithandlers.' . $this->identifier();
    }

    public function storeAdmin($data)
    {
    	return $data;
    }

    public function process($submission, $data)
    {
    }
}