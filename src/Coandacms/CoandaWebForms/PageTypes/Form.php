<?php namespace CoandaCMS\CoandaWebForms\PageTypes;

class Form implements \CoandaCMS\Coanda\Pages\PageTypeInterface {

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

	public function showMeta()
	{
		return true;
	}
}