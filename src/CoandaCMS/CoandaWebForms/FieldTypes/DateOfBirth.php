<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class DateOfBirth extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'Date of Birth';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'date_of_birth';
    }

    /**
     * @param $field
     * @param $data
     * @return mixed
     * @throws FieldTypeRequiredException
     */
    public function handleSubmissionData($field, $data)
    {
        $day = isset($data['day']) ? $data['day'] : '';
        $month = isset($data['month']) ? $data['month'] : '';
        $year = isset($data['year']) ? $data['year'] : '';

        if (($day == '' || $month == '' || $year == '') && $field->required)
        {
            throw new FieldTypeRequiredException($field->label . ' is required');
        }

        return $data;
    }

    private function renderDate($field)
    {
        $data = json_decode($field->field_data, true);

        if (isset($data['day']) && isset($data['month']) && isset($data['year']))
        {
            return $data['day'] . '/' . $data['month'] . '/' . $data['year'];    
        }

        return '';
    }

    /**
     * @param $field
     * @return string
     */
    public function displayLine($field)
    {
        return $this->renderDate($field);
    }

    /**
     * @param $field
     * @return string
     */
    public function displayFull($field)
    {
        return $this->renderDate($field);
    }
}