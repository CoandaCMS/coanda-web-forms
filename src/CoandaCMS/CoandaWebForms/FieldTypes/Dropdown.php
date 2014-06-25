<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Dropdown extends FieldType {

    public function name()
    {
        return 'Dropdown';
    }

    public function identifier()
    {
        return 'dropdown';
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
}