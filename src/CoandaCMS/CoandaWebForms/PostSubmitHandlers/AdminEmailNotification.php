<?php namespace CoandaCMS\CoandaWebForms\PostSubmitHandlers;

use CoandaCMS\CoandaWebForms\PostSubmitHandler;

use View, Mail, Config;

class AdminEmailNotification extends PostSubmitHandler {

    public function name()
    {
    	return 'Admin notification email';
    }

    public function identifier()
    {
    	return 'admin_email_notification';
    }

    public function process($submission, $data)
    {
    	$notification_email = isset($data['notification_email']) ? $data['notification_email'] : false;

    	if ($notification_email)
    	{
    		$form = $submission->form;

			Mail::send('coanda-web-forms::admin.postsubmithandlers.adminemailnotification.email', ['form' => $form, 'submission' => $submission], function($message) use ($notification_email, $submission, $form)
			{
	    		$from_name = Config::get('coanda::coanda.site_name');
	    		$from_email = Config::get('coanda::coanda.site_admin_email');

				$message->from($from_email, $from_name);
			    $message->to($notification_email)->subject('New submission via ' . $form->name);
			});
    	}

    }
}