<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeConfigurationException;
use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class NumberRange extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'Number Range';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'number_range';
    }

    /**
     * @param $field
     * @param $data
     * @throws FieldTypeConfigurationException
     */
    public function setTypeData($field, $data)
    {
        $invalid = false;

        if (!isset($data['from']) || $data['from'] == '')
        {
            $invalid = true;
        }

        if (!isset($data['to']) || $data['to'] == '')
        {
            $invalid = true;
        }

        $field->type_data = json_encode($data);

        if ($invalid)
        {
            throw new FieldTypeConfigurationException('Please enter valid from and to numbers');
        }
    }
    /**
     * @param $field
     * @param $data
     * @return mixed
     * @throws FieldTypeRequiredException
     */
    public function handleSubmissionData($field, $data)
    {
        // If it is required and hasn't been entered, then complain...
        if ($data == '' && $field->required)
        {
            throw new FieldTypeRequiredException($field->label . ' is required');
        }

        return $data;
    }
}