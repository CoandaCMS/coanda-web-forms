<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Checkboxes extends FieldType {

    public function name()
    {
        return 'Checkboxes (multiple select)';
    }

    public function identifier()
    {
        return 'checkboxes';
    }

    public function setTypeData($field, $data)
    {
        if (!is_array($data))
        {
            $data = [
                'options' => []
            ];
        }

        $valid_options = [];

        foreach ($data['options'] as $option)
        {
            if ($option != '')
            {
                $valid_options[] = $option;
            }
        }

        $data['options'] = $valid_options;

        $field->type_data = json_encode($data);
    }

    public function handleSubmissionData($field, $data)
    {
        if ((!is_array($data) || count($data) == 0) && $field->required)
        {
            throw new FieldTypeRequiredException('Please choose an option');
        }

        if (is_array($data))
        {
            $data = json_encode($data);
        }

        if ($data === false)
        {
            return '';
        }

        return $data;
    }

    public function displayLine($field)
    {
        $data = json_decode($field->field_data, true);

        if (is_array($data) && count($data) > 0)
        {
            return implode($data, ',');
        }

        return '';
    }

    public function displayFull($field)
    {
        $data = json_decode($field->field_data, true);

        if (is_array($data) && count($data) > 0)
        {
            return implode($data, ',');
        }

        return '';
    }    
}