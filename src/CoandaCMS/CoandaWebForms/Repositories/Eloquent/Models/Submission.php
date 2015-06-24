<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Eloquent, Coanda, App;

class Submission extends Eloquent {

    /**
     * @var array
     */
    protected $fillable = ['form_id', 'page_id'];

    /**
     * @var string
     */
    protected $table = 'coanda_webformsubmissions';

    /**
     * @return mixed
     */
    public function fields()
	{
		return $this->hasMany('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\SubmissionField', 'submission_id');
	}

    /**
     * @param $identifier
     * @return mixed
     */
    public function fieldByIdentifier($identifier)
    {
        return $this->fields()->where('identifier', '=', $identifier)->first();
    }

    /**
     * @param $field_label
     * @return mixed
     */
    public function field($field_label)
    {
        return $this->fields()->where('label', '=', $field_label)->first();
    }

    /**
     * @return mixed
     */
    public function form()
    {
        return $this->belongsTo('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebForm', 'form_id');
    }

    /**
     * @param $headings
     * @return array
     */
    public function fieldsForHeadings($headings)
    {
        return $this->fields()->whereIn('label', $headings)->get();
    }
}