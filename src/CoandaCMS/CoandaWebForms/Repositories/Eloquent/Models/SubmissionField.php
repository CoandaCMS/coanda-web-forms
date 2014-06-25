<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Eloquent, Coanda, App;

/**
 * Class SubmissionField
 * @package CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models
 */
class SubmissionField extends Eloquent {

    /**
     * @var array
     */
    protected $fillable = ['submission_id', 'field_id', 'type', 'label', 'field_data'];

    /**
     * @var string
     */
    protected $table = 'webformsubmissionfields';

    public function type()
    {
        return Coanda::webforms()->fieldType($this->type);
    }

    /**
     * @param $value
     */
    public function setFieldDataAttribute($value)
	{
		if (is_array($value))
		{
			$value = json_encode($value);
		}

		$this->attributes['field_data'] = $value;
	}

    /**
     * @return mixed
     */
    public function submission()
	{
		return $this->belongsTo('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\SubmissionField', 'submission_id');
	}

    public function getDisplayLineAttribute()
    {
        return $this->type()->displayLine($this);
    }

    public function getDisplayFullAttribute()
    {
        return $this->type()->displayFull($this);
    }
}