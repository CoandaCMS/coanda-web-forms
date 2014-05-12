<?php namespace CoandaCMS\CoandaWebForms\Attributes;

use CoandaCMS\Coanda\Core\Attributes\AttributeType;
use CoandaCMS\Coanda\Exceptions\AttributeValidationException;

use Coanda, Input;

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

    public function store($data, $is_required, $name, $parameters)
	{
        if (!array_key_exists('notification_email', $data))
        {
            $data['notification_email'] = '';
        }

        // Loop though and store all the webformfields
        $fields = Coanda::module('webforms')->formFields($parameters['page_id'], $parameters['version_number']);

        foreach ($fields as $field)
        {
            $field_data = [];

            if (isset($data['fields']['field_' . $field->id]))
            {
                $field_data = $data['fields']['field_' . $field->id];
            }

            if (isset($field_data['label']))
            {
                $field->label = $field_data['label'];
            }
            
            if (isset($field_data['order']))
            {
                $field->order = $field_data['order'];
            }

            if (isset($field_data['required']))
            {
                $field->required = ($field_data['required'] == 'true');
            }
            else
            {
                $field->required = false;
            }
            
            $field->save();
        }

        if ($is_required && $fields->count() == 0)
        {
            throw new AttributeValidationException('Please add at least one form field');
        }

        return json_encode($data);
	}

    /**
     * @param $data
     * @return mixed
     */
    public function data($data, $parameters = [])
	{
        $data = json_decode($data, true);

        $notification_email = '';

        if (is_array($data))
        {
            $notification_email = $data['notification_email'];
        }

        // Get all the web form fields 
        $fields = Coanda::module('webforms')->formFields($parameters['page_id'], $parameters['version_number']);

        $return_data = [
            'notification_email' => $notification_email,
            'fields' => $fields
        ];

        return $return_data;
	}

    public function handleAction($action, $data, $parameters = [])
    {
        if ($action == 'add_field')
        {
            $field_type = Input::get('attribute_' . $parameters['attribute_id'] . '_new_field_type');

            Coanda::module('webforms')->addFormField($field_type, $parameters['page_id'], $parameters['version_number']);
        }

        if ($action == 'remove_fields')
        {
            $attribute_data = Input::get('attribute_' . $parameters['attribute_id']);

            if (isset($attribute_data['remove_field_list']))
            {
                $remove_field_list = $attribute_data['remove_field_list'];

                foreach ($remove_field_list as $remove_field)
                {
                    Coanda::module('webforms')->removeFormField($remove_field);
                }
            }
        }
    }

    public function delete($parameters)
    {
        $fields = Coanda::module('webforms')->formFields($parameters['page_id'], $parameters['version_number']);

        foreach ($fields as $field)
        {
            $field->delete();
        }
    }

    public function initialise($from_parameters, $to_parameters)
    {
        if (isset($from_parameters['page_id']) && isset($from_parameters['version_number']))
        {
            $existing_fields = Coanda::module('webforms')->formFields($from_parameters['page_id'], $from_parameters['version_number']);

            foreach ($existing_fields as $existing_field)
            {
                $new_field = Coanda::module('webforms')->addFormField($existing_field->type, $to_parameters['page_id'], $to_parameters['version_number']);

                $new_field->label = $existing_field->label;
                $new_field->required = $existing_field->required;
                $new_field->order = $existing_field->order;
                $new_field->type_data = $existing_field->type_data;
                $new_field->save();
            }            
        }
    }
}
