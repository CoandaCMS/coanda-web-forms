<?php namespace CoandaCMS\CoandaWebForms;

use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

abstract class FieldType {

    /**
     * @return mixed
     */
    abstract public function name();

    /**
     * @return mixed
     */
    abstract public function identifier();

    /**
     * @return string
     */
    public function editTemplate()
    {
        return 'coanda-web-forms::admin.fieldtypes.edit.' . $this->identifier();
    }

    /**
     * @return string
     */
    public function adminViewTemplate()
    {
        return 'coanda-web-forms::admin.fieldtypes.view.' . $this->identifier();
    }

    /**
     * @return string
     */
    public function viewTemplate()
    {
        return 'coanda-web-forms::attributes.fieldtypes.' . $this->identifier();
    }

    /**
     * @param $field
     * @return mixed
     */
    public function getTypeData($field)
    {
        $data = json_decode($field->type_data, true);

        if ($data && is_array($data))
        {
            return $data;
        }

        return $field->type_data;
    }

    /**
     * @param $field
     * @param $data
     */
    public function setTypeData($field, $data)
    {
        if (is_array($data))
        {
            $data = json_encode($data);
        }

        $field->type_data = $data;
    }

    /**
     * @return bool
     */
    public function canStore()
    {
        return true;
    }

    // A basic example
    /**
     * @param $field
     * @param $data
     * @return mixed
     * @throws FieldTypeRequiredException
     */
    public function handleSubmissionData($field, $data)
    {
        if (!$data && $field->required)
        {
            throw new FieldTypeRequiredException($field->label . ' is required');
        }

        return $data;
    }

    /**
     * @param $field
     * @return mixed
     */
    public function displayLine($field)
    {
        return $field->field_data;
    }

    /**
     * @param $field
     * @return mixed
     */
    public function displayFull($field)
    {
        return $field->field_data;
    }
}