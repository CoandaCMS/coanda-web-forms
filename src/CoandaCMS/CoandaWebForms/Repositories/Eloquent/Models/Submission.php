<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Eloquent, Coanda, App;

class Submission extends Eloquent {

    protected $fillable = ['page_id', 'version_number'];

	protected $table = 'webformsubmissions';

	public function fields()
	{
		return $this->hasMany('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\SubmissionField', 'submission_id');
	}

}