<?php namespace CoandaCMS\CoandaWebForms;

use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

abstract class FieldType {

    abstract public function name();

    abstract public function identifier();

    public function getTypeData($field)
    {
        $data = json_decode($field->type_data, true);

        if ($data && is_array($data))
        {
            return $data;
        }

        return $field->type_data;
    }

    public function setTypeData($field, $data)
    {
        if (is_array($data))
        {
            $data = json_encode($data);
        }

        $field->type_data = $data;
    }

    public function canStore()
    {
        return true;
    }

    // A basic example
    public function handleSubmissionData($field, $data)
    {
        if (!$data && $field->required)
        {
            throw new FieldTypeRequiredException($field->label . ' is required');
        }

        return $data;
    }

    public function displayLine($field) // How the field will be displayed on the screen...
    {
        return $field->field_data;
    }

    public function displayFull($field)
    {
        return $field->field_data;
    }

    public function downloadDisplay($field) // How the field will be displayed in a download...
    {
        return $field->field_data;
    }


}