<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class Country extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'Country List';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'country';
    }
}