<?php

return array(

	/*
	|---------------------------
	| Page type template
	|---------------------------
	|
	*/
	'template' => 'coanda-web-forms::pagetypes.form',
	
	/*
	|---------------------------
	| Available field types
	|---------------------------
	|
	*/
	'field_types' => [

		'CoandaCMS\CoandaWebForms\FieldTypes\Textline',
		'CoandaCMS\CoandaWebForms\FieldTypes\Textbox',
		'CoandaCMS\CoandaWebForms\FieldTypes\Email',
		'CoandaCMS\CoandaWebForms\FieldTypes\Checkboxes',
		'CoandaCMS\CoandaWebForms\FieldTypes\Dropdown',
		'CoandaCMS\CoandaWebForms\FieldTypes\Radiobuttons',
		'CoandaCMS\CoandaWebForms\FieldTypes\Date',
		'CoandaCMS\CoandaWebForms\FieldTypes\Number',
		'CoandaCMS\CoandaWebForms\FieldTypes\File',

		'CoandaCMS\CoandaWebForms\FieldTypes\ContentHeader',
		'CoandaCMS\CoandaWebForms\FieldTypes\ContentText',

	],

	'post_submit_handlers' => [

		'CoandaCMS\CoandaWebForms\PostSubmitHandlers\AdminEmailNotification',



		'Abellio\Modules\LostProperty\LostPropertyAPINotify',

	]

);