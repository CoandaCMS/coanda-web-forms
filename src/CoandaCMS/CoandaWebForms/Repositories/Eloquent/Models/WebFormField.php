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

    /**
     * @return mixed
     */
    public function type()
    {
        return Coanda::webforms()->fieldType($this->type);
    }

    /**
     * @return mixed
     */
    public function canStore()
    {
        return $this->type()->canStore();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handleSubmissionData($data)
    {
        return $this->type()->handleSubmissionData($this, $data);
    }

    /**
     * @return mixed
     */
    public function typeData()
    {
        return $this->type()->getTypeData($this);
    }

    /**
     * @param $data
     */
    public function setTypeData($data)
    {
        $this->type()->setTypeData($this, $data);
    }

}