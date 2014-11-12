<?php namespace CoandaCMS\CoandaWebForms\PostSubmitHandlers;

use CoandaCMS\CoandaWebForms\PostSubmitHandler;

use View, Mail, Config;

class AdminEmailNotification extends PostSubmitHandler {

    /**
     * @return string
     */
    public function name()
    {
    	return 'Admin notification email';
    }

    /**
     * @return string
     */
    public function identifier()
    {
    	return 'admin_email_notification';
    }

    /**
     * @param $submission
     * @param $data
     */
    public function process($submission, $data)
    {
    	$notification_email = isset($data['notification_email']) ? $data['notification_email'] : false;
		$include_data = isset($data['include_data']) ? ($data['include_data'] == 'yes') : false;

    	if ($notification_email)
    	{
    		$form = $submission->form;

			Mail::send('coanda-web-forms::admin.postsubmithandlers.adminemailnotification.email', ['form' => $form, 'submission' => $submission, 'include_data' => $include_data], function($message) use ($notification_email, $submission, $form)
			{
	    		$from_name = Config::get('coanda::coanda.site_name');
	    		$from_email = Config::get('coanda::coanda.site_admin_email');

				$message->from($from_email, $from_name);
			    $message->to($notification_email)->subject('New submission via ' . $form->name);
			});
    	}
    }
}