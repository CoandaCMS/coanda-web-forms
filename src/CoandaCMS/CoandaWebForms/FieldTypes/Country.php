<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;

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