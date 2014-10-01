<?php namespace CoandaCMS\CoandaWebForms\Attributes;

use CoandaCMS\Coanda\Core\Attributes\AttributeType;
use CoandaCMS\Coanda\Exceptions\AttributeValidationException;

use Coanda, Input, View, Request, Session;

/**
 * Class WebForm
 * @package CoandaCMS\CoandaWebForms\Attributes
 */
class WebForm extends AttributeType {

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

    /**
     * @param $data
     * @param $is_required
     * @param $name
     * @param $parameters
     * @return string
     * @throws \CoandaCMS\Coanda\Exceptions\AttributeValidationException
     */
    public function store($data, $is_required, $name, $parameters)
	{
        return $data;
	}

    /**
     * @param $data
     * @param array $parameters
     * @return mixed
     */
    public function data($data, $parameters = [])
	{
        return $data;
	}

    /**
     * @param $data
     * @param array $parameters
     * @return string
     */
    public function render($data, $parameters = [])
    {
        // If we are indexing, then we don't want to return the whole form...
        if ($parameters['indexing'])
        {
            return '';
        }

        if (Session::has('submission_stored'))
        {
            return View::make('coanda-web-forms::attributes.webform_stored');
        }
        else
        {
            $invalid_fields = Session::get('invalid_fields', []);
            $has_error = count($invalid_fields) > 0;

            return View::make('coanda-web-forms::attributes.webform', ['data' => $data, 'parameters' => $parameters, 'has_error' => $has_error, 'invalid_fields' => $invalid_fields ])->render();            
        }
    }
}
