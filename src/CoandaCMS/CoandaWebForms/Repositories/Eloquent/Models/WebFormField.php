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
    protected $fillable = ['webform_id', 'identifier', 'label', 'type', 'type_data', 'validation_rules', 'order'];

    /**
     * @var string
     */
    protected $table = 'coanda_webformfields';

    /**
     * @param array $options
     */
    public function save(array $options = [])
    {
        if (!$this->identifier || $this->identifier == '')
        {
            $this->identifier = \CoandaCMS\Coanda\Urls\Slugifier::convert($this->label);
        }

        parent::save($options);
    }

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