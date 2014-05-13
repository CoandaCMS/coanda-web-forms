<?php namespace CoandaCMS\CoandaWebForms\PageTypes;

class Form extends \CoandaCMS\Coanda\Pages\PageType {

	public function identifier()
	{
		return 'form';
	}

	public function name()
	{
		return 'Form';
	}

	public function icon()
	{
		return 'fa-list';
	}

	public function allowsSubPages()
	{
		return false;
	}

	public function attributes()
	{
		return [
				'name' => [
					'name' => 'Name',
					'identifier' => 'name',
					'type' => 'textline',
					'required' => true
				],
				'form' => [
					'name' => 'Form',
					'identifier' => 'form',
					'type' => 'webform',
					'required' => true
				],
			];
	}

	public function generateName($version)
	{
		return $version->getAttributeByIdentifier('name')->typeData();
	}

	public function template()
	{
		return 'coanda-web-forms::pagetypes.form';
	}
}