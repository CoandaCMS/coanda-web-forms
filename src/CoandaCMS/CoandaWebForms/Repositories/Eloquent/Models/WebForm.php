<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Eloquent;

class WebForm extends Eloquent {

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var string
     */
    protected $table = 'webforms';

    public function fields()
	{
		return $this->hasMany('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebFormField', 'webform_id')->orderBy('order', 'asc');
	}

    public function submissions()
    {
        return $this->hasMany('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\Submission', 'form_id')->orderBy('created_at', 'desc');
    }

    public function firstFiveFields()
    {
        return $this->fields()->whereNotIn('type', ['content_header', 'content_text'])->take(5)->get();
    }

}