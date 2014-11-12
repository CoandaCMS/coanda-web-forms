<label for="admin_email_notification_notification_email">Notification email address</label>
<input type="text" class="form-control" id="admin_email_notification_notification_email" name="post_submit_handler_data[admin_email_notification][notification_email]" value="{{ isset($hander_data['notification_email']) ? $hander_data['notification_email'] : '' }}">

<div class="checkbox">
    <label for="admin_email_notification_include_data">
        <input type="checkbox" id="admin_email_notification_include_data" name="post_submit_handler_data[admin_email_notification][include_data]" value="yes" {{ (isset($hander_data['include_data']) && $hander_data['include_data'] == 'yes') ? 'checked="checked"' : '' }}">
        Include data in email? *
    </label>
</div>
<em>* If you choose this option, the submitted data will be included as a table in the notification email, otherwise the email will just contain a link to the admin view of the data.</em>

