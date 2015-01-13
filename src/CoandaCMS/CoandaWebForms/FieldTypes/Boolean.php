<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Boolean extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'Boolean';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'boolean';
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
                'default_ticked' => false
            ];
        }

        if (isset($data['default_ticked']) && is_string($data['default_ticked']) && $data['default_ticked'] == 'true')
        {
            $data['default_ticked'] = true;
        }

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
        if ((!$data || $data !== 'true') && $field->required)
        {
            throw new FieldTypeRequiredException($field->label . ' is required');
        }

        return $data;
    }

    /**
     * @param $field
     * @return string
     */
    public function displayLine($field)
    {
        return $this->display($field);
    }

    /**
     * @param $field
     * @return string
     */
    public function displayFull($field)
    {
        return $this->display($field);
    }

    /**
     * @param $field
     * @return string
     */
    private function display($field)
    {
        return $field->field_data == 'true' ? 'Yes' : 'No';
    }
}