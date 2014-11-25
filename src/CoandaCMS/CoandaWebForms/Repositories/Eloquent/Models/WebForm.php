<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Eloquent, Coanda;

class WebForm extends Eloquent {

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var string
     */
    protected $table = 'coanda_webforms';

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        static::deleting( function($form)
        {
            $form->fields()->delete();

            SubmissionField::join('coanda_webformsubmissions', 'coanda_webformsubmissions.id', '=', 'coanda_webformsubmissionfields.submission_id')->where('coanda_webformsubmissions.form_id', '=', $form->id)->delete();
            $form->submissions()->delete();

        });
    }

    /**
     * @return mixed
     */
    public function fields()
	{
		return $this->hasMany('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebFormField', 'webform_id')->orderBy('order', 'asc');
	}

    /**
     * @return mixed
     */
    public function submissions()
    {
        return $this->hasMany('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\Submission', 'form_id')->orderBy('created_at', 'desc');
    }

    /**
     * @param bool $limit
     * @return mixed
     */
    public function dataHeadings($limit = false)
    {
        $query = $this->fields()->whereNotIn('type', ['content_header', 'content_text']);

        if ($limit)
        {
            $query->take($limit);
        }

        return $query->get();
    }

    /**
     * @return array|mixed
     */
    public function postSubmitHandlerData()
    {
        $data = json_decode($this->post_submit_handler_data, true);

        if (!is_array($data))
        {
            $data = [];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function enabledPostSubmitHandlers()
    {
        return array_keys($this->postSubmitHandlerData());
    }

    /**
     * @param $submission
     */
    public function executePostSubmissionHandlers($submission)
    {
        $handler_data = $this->postSubmitHandlerData();

        foreach ($this->enabledPostSubmitHandlers() as $post_submission_handler_identifier)
        {
            $post_submission_handler = Coanda::webforms()->postSubmitHandler($post_submission_handler_identifier);
            
            if ($post_submission_handler)
            {
                $post_submission_handler->process($submission, isset($handler_data[$post_submission_handler_identifier]) ? $handler_data[$post_submission_handler_identifier] : false);
            }
        }

        $submission->post_submit_handler_executed = true;
        $submission->save();

    }

}