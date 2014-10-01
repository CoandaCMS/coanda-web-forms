<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Checkboxes extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'Checkboxes (multiple select)';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'checkboxes';
    }

    /**
     * @param $field
     * @param $data
     */
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

    /**
     * @param $field
     * @param $data
     * @return string
     * @throws FieldTypeRequiredException
     */
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

    /**
     * @param $field
     * @return string
     */
    public function displayLine($field)
    {
        $data = json_decode($field->field_data, true);

        if (is_array($data) && count($data) > 0)
        {
            return implode($data, ',');
        }

        return '';
    }

    /**
     * @param $field
     * @return string
     */
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