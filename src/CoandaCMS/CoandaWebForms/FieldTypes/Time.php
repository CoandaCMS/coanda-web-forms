<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Time extends FieldType {

    public function name()
    {
        return 'Time';
    }

    public function identifier()
    {
        return 'time';
    }

    public function handleSubmissionData($field, $data)
    {
        $invalid = false;

        if ($data['hour'] !== '' && !is_numeric($data['hour']))
        {
            $invalid = true;
        }

        if ($data['minute'] !== '' && !is_numeric($data['minute']))
        {
            $invalid = true;
        }

        if ($invalid)
        {
            throw new FieldTypeRequiredException($field->label . ' is not a valid time, please ensure you enter valid numbers for hour and minute.');
        }

        $data = (int)$data['hour'] . ':' . (int)$data['minute'];

        if ($data == '' && $field->required)
        {
            throw new FieldTypeRequiredException($field->label . ' is required');
        }

        return $data;
    }
}