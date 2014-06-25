<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Textline extends FieldType {

    public function name()
    {
        return 'Text line';
    }

    public function identifier()
    {
        return 'textline';
    }

    public function handleSubmissionData($field, $data)
    {
        if ((!is_string($data) || $data == '') && $field->required)
        {
            throw new FieldTypeRequiredException($field->label . ' is required');
        }

        return $data;
    }
}