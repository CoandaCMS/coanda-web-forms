<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Email extends FieldType {

    public function name()
    {
        return 'Email address';
    }

    public function identifier()
    {
        return 'email';
    }

    public function handleSubmissionData($field, $data)
    {
        if ((!is_string($data) || $data == '') && $field->required)
        {
            throw new FieldTypeRequiredException($field->label . ' is required');
        }

        if (filter_var($data, FILTER_VALIDATE_EMAIL) === false && $field->required)
        {
            throw new FieldTypeRequiredException('Please enter a valid email address');
        }

        return $data;
    }
}