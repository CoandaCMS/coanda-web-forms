<?php namespace CoandaCMS\CoandaWebForms\PageTypes;

class Form extends \CoandaCMS\Coanda\Pages\PageType {

	/**
	 * @var string
     */
	protected $name = 'Form';
	/**
	 * @var string
     */
	protected $icon = 'fa-list';
	/**
	 * @var bool
     */
	protected $allow_sub_pages = false;
	/**
	 * @var bool
     */
	protected $static_cache = false;

	/**
	 * @var array
     */
	protected $schema = [
		'name' => 'Name|textline|required|generates_slug',
		'content' => 'Content|html',
		'form' => 'Form|webform'
	];

    /**
     * @param $version
     * @param array $data
     * @return mixed
     */
    public function template($version, $data = [])
	{
		return \Config::get('coanda-web-forms::config.page_type_template');
	}
}