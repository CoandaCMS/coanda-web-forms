<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Number extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'Number';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'number';
    }

    /**
     * @param $field
     * @param $data
     * @return mixed
     * @throws FieldTypeRequiredException
     */
    public function handleSubmissionData($field, $data)
    {
        // If it is required and hasn't been entered, then complain...
        if ($data == '' && $field->required)
        {
            throw new FieldTypeRequiredException($field->label . ' is required');
        }

        if ($data != '')
        {
            $min = isset($field->typeData()['min']) ? $field->typeData()['min'] : false;
            $max = isset($field->typeData()['max']) ? $field->typeData()['max'] : false;

            if (($min && $min) > 0 && ($max && $max > 0))
            {
                if ($data < $min || $data > $max)
                {
                    throw new FieldTypeRequiredException('Please enter a number between ' . $min . ' and ' . $max);
                }
            }
            elseif ($min && $min > 0)
            {
                if ($data < $min)
                {
                    throw new FieldTypeRequiredException('Please enter a number greater than ' . $min);
                }
            }
            elseif ($max && $max > 0)
            {
                if ($data > $max)
                {
                    throw new FieldTypeRequiredException('Please enter a number less than ' . $max);
                }
            }
        }

        return $data;
    }
}