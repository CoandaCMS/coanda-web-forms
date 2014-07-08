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

    public function postSubmitHandlerData()
    {
        $data = json_decode($this->post_submit_handler_data, true);

        if (!is_array($data))
        {
            $data = [];
        }

        return $data;
    }

    public function enabledPostSubmitHandlers()
    {
        return array_keys($this->postSubmitHandlerData());
    }

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