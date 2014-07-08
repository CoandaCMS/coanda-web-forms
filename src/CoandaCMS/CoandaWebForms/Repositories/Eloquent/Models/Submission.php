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
    protected $fillable = ['form_id', 'location_id', 'version', 'slug'];

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

    /**
     * @return mixed
     */
    public function form()
    {
        return $this->belongsTo('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebForm', 'form_id');
    }

    public function fieldsForHeadings($headings)
    {
        $fields = [];

        foreach ($headings as $heading)
        {
            $field = $this->fields()->whereLabel($heading)->first();

            if ($field)
            {
                $fields[] = $field;
            }
            else
            {
                $fields[] = false;
            }
        }

        return $fields;
    }
}