<?php namespace CoandaCMS\CoandaWebForms\Attributes;

use CoandaCMS\Coanda\Core\Attributes\AttributeTypeInterface;
use CoandaCMS\Coanda\Exceptions\AttributeValidationException;

class WebForm implements AttributeTypeInterface {

    /**
     * @return string
     */
    public function identifier()
	{
		return 'webform';
	}

    /**
     * @return string
     */
    public function edit_template()
    {
    	return 'coanda-web-forms::admin.attributes.edit.webform';
    }

    /**
     * @return string
     */
    public function view_template()
    {
    	return 'coanda-web-forms::admin.attributes.view.webform';
    }

    public function store($data, $is_required, $name)
	{
        dd('store');
		// if ($data == '<p><br></p>')
		// {
		// 	$data = '';
		// }

		// // TODO
		// // - Tidy up HTML?
		// if ($is_required && (!$data || $data == ''))
		// {
		// 	throw new AttributeValidationException($name . ' is required');
		// }

		// return $data;
	}

    /**
     * @param $data
     * @return mixed
     */
    public function data($data)
	{
		return $data;
	}
}