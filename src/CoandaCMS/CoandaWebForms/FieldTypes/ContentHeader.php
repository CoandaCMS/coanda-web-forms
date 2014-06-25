<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class ContentHeader extends FieldType {

    public function name()
    {
        return 'Content: Header';
    }

    public function identifier()
    {
        return 'content_header';
    }

    public function canStore()
    {
        return false;
    }
}