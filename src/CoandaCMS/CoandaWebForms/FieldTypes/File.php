<?php namespace CoandaCMS\CoandaWebForms\FieldTypes;

use Input, Coanda;

use CoandaCMS\CoandaWebForms\FieldType;
use CoandaCMS\CoandaWebForms\Exceptions\FieldTypeRequiredException;

class File extends FieldType {

    /**
     * @return string
     */
    public function name()
    {
        return 'File upload';
    }

    /**
     * @return string
     */
    public function identifier()
    {
        return 'file';
    }

    private function generateRequiredIf($required_if, $form_id)
    {
        if (!strpos($required_if, ':')) {
            return;
        }

        $parts = explode(':', $required_if);

        $field = $parts[0];
        $values = explode('|', $parts[1]);

        $form_field = \CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebFormField::whereIdentifier($field)->whereWebformId($form_id)->first();

        if (!$form_field) {
            return;
        }

        $submitted_value = Input::get('field_'.$form_field->id);

        return in_array($submitted_value, $values);
    }

    /**
     * @param $field
     * @param $data
     * @return string
     * @throws FieldTypeRequiredException
     */
    public function handleSubmissionData($field, $data)
    {
        $file = Input::hasFile('field_' . $field->id) ? Input::file('field_' . $field->id) : false;
        $required_if = false;

        $type_data = $field->typeData();

        if (isset($type_data['required_if_logic'])) {
            $required_if = $this->generateRequiredIf($type_data['required_if_logic'], $field->webform_id);
        }

        if ($file === false && ($field->required || $required_if))
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

    /**
     * @param $field
     * @return string
     */
    public function displayLine($field)
    {
        $data = json_decode($field->field_data, true);

        if (is_array($data))
        {
            return $data['file_name'];
        }

        return '';
    }

    /**
     * @param $field
     * @return string
     */
    public function displayFull($field)
    {
        $data = json_decode($field->field_data, true);

        if (is_array($data))
        {
            return '<a class="new-window" href="' . Coanda::adminUrl('media/download/' . $data['media_id']) . '">' . $data['file_name'] . '</a>';
        }

        return '';
    }

    public function displayExport($field)
    {
        $data = json_decode($field->field_data, true);

        if (is_array($data))
        {
            return Coanda::adminUrl('media/download/' . $data['media_id']);
        }

        return '';
    }
}