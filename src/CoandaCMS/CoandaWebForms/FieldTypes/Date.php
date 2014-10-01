<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Date extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'Date';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'date';
    }

    /**
     * @param $field
     * @param $data
     * @return mixed
     * @throws FieldTypeRequiredException
     */
    public function handleSubmissionData($field, $data)
    {
        if ((!is_string($data) || $data == '') && $field->required)
        {
            throw new FieldTypeRequiredException($field->label . ' is required');
        }

        return $data;
    }
}