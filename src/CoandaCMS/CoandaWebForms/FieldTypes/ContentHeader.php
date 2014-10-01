<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class ContentHeader extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'Content: Header';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'content_header';
    }

    /**
     * @return bool
     */
    public function canStore()
    {
        return false;
    }
}