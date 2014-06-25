<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use Input, Coanda;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class File extends FieldType {

    public function name()
    {
        return 'File upload';
    }

    public function identifier()
    {
        return 'file';
    }

    public function handleSubmissionData($field, $data)
    {
        $file = Input::hasFile('field_' . $field->id) ? Input::file('field_' . $field->id) : false;

        if ($file === false && $field->required)
        {
           throw new FieldTypeRequiredException('Please upload a file');
        }

        if ($file)
        {
            $media = Coanda::media()->handleUpload($file, 'webforms', true);

            $data = [
                'media_id' => $media->id,
                'file_name' => $media->original_filename
            ];
        }

        if (is_array($data))
        {
            return json_encode($data);
        }
        
        return '';
    }

    public function displayLine($field)
    {
        $data = json_decode($field->field_data, true);

        if (is_array($data))
        {
            return $data['file_name'];
        }

        return '';
    }

    public function displayFull($field)
    {
        $data = json_decode($field->field_data, true);

        if (is_array($data))
        {
            return '<a href="">' . $data['file_name'] . '</a>';
        }

        return '';
    }    
}