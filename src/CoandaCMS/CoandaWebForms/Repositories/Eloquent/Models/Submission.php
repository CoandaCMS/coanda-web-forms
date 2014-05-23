<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Eloquent, Coanda, App;

/**
 * Class Submission
 * @package CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models
 */
class Submission extends Eloquent {

    /**
     * @var array
     */
    protected $fillable = ['page_id', 'version_number'];

    /**
     * @var string
     */
    protected $table = 'webformsubmissions';

    /**
     * @return mixed
     */
    public function fields()
	{
		return $this->hasMany('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\SubmissionField', 'submission_id');
	}

}