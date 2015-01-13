<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Dropdown extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'Dropdown';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'dropdown';
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

        if (!isset($data['options']))
        {
            $data['options'] = [];
        }

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
}