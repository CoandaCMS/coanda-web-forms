<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Matrix extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'Matrix';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'matrix';
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
                'options' => [],
                'questions' => []
            ];
        }

        if (!isset($data['options']))
        {
            $data['options'] = [];
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

        if (!isset($data['questions']))
        {
            $data['questions'] = [];
        }

        $valid_questions = [];

        foreach ($data['questions'] as $question)
        {
            if ($question != '')
            {
                $valid_questions[] = $question;
            }
        }

        $data['questions'] = $valid_questions;

        $field->type_data = json_encode($data);
    }

    private function getDisplayList($data)
    {
        $display_list = [];

        if (is_array($data) && count($data) > 0)
        {
            foreach ($data as $question => $answer)
            {
                $display_list[] = $question . ': ' . $answer;
            }
        }

        return $display_list;
    }

    /**
     * @param $field
     * @return string
     */
    public function displayLine($field)
    {
        $display = $this->getDisplayList(json_decode($field->field_data, true));

        return implode($display, ',');
    }

    /**
     * @param $field
     * @return string
     */
    public function displayFull($field)
    {
        $display = $this->getDisplayList(json_decode($field->field_data, true));

        return implode($display, ',');
    }
}