<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class ContentText extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'Content: Text';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'content_text';
    }

    /**
     * @return bool
     */
    public function canStore()
    {
        return false;
    }
}