<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Eloquent, Coanda, App;

class SubmissionField extends Eloquent {

    protected $fillable = ['submission_id', 'field_id', 'type', 'label', 'field_data'];

	protected $table = 'webformsubmissionfields';

	public function setFieldDataAttribute($value)
	{
		if (is_array($value))
		{
			$value = json_encode($value);
		}

		$this->attributes['field_data'] = $value;
	}	

	public function submission()
	{
		return $this->belongsTo('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\SubmissionField', 'submission_id');
	}

}