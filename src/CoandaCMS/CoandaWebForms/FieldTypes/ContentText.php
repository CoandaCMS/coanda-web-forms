<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class ContentText extends FieldType {

    public function name()
    {
        return 'Content: Text';
    }

    public function identifier()
    {
        return 'content_text';
    }

    public function canStore()
    {
        return false;
    }
}