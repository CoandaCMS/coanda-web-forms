<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class UKPostcode extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'UK Postcode';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'uk_postcode';
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

        $data = trim($data);

        if ($data != '')
        {
            if (!preg_match('/^(((([A-PR-UWYZ][0-9][0-9A-HJKS-UW]?)|([A-PR-UWYZ][A-HK-Y][0-9][0-9ABEHMNPRV-Y]?))\s{0,2}[0-9]([ABD-HJLNP-UW-Z]{2}))|(GIR\s{0,2}0AA))$/i', $data))
            {
                throw new FieldTypeRequiredException('Please enter a valid postcode');
            }
        }

        return $data;
    }
}