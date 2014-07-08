<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Eloquent, Coanda;

/**
 * Class FormField
 * @package CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models
 */
class WebFormField extends Eloquent {

    /**
     * @var array
     */
    protected $fillable = ['webform_id', 'label', 'type', 'type_data', 'validation_rules', 'order'];

    /**
     * @var string
     */
    protected $table = 'webformfields';

    public function type()
    {
        return Coanda::webforms()->fieldType($this->type);
    }

    public function canStore()
    {
        return $this->type()->canStore();
    }

    public function handleSubmissionData($data)
    {
        return $this->type()->handleSubmissionData($this, $data);
    }

    public function typeData()
    {
        return $this->type()->getTypeData($this);
    }

    public function setTypeData($data)
    {
        $this->type()->setTypeData($this, $data);
    }

}