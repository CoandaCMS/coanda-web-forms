<?php namespace CoandaCMS\CoandaWebForms\PageTypes;

/**
 * Class Form
 * @package CoandaCMS\CoandaWebForms\PageTypes
 */
class Form extends \CoandaCMS\Coanda\Pages\PageType {

    /**
     * @return string
     */
    public function identifier()
	{
		return 'form';
	}

    /**
     * @return string
     */
    public function name()
	{
		return 'Form';
	}

    /**
     * @return string
     */
    public function icon()
	{
		return 'fa-list';
	}

    /**
     * @return bool
     */
    public function allowsSubPages()
	{
		return false;
	}

    /**
     * @return array
     */
    public function attributes()
	{
		return [
				'name' => [
					'name' => 'Name',
					'identifier' => 'name',
					'type' => 'textline',
					'required' => true,
					'generates_slug' => true
				],
				'content' => [
					'name' => 'Content',
					'identifier' => 'content',
					'type' => 'html',
					'required' => false
				],
				'form' => [
					'name' => 'Form',
					'identifier' => 'form',
					'type' => 'webform',
					'required' => true
				],
			];
	}

    /**
     * @param $version
     * @return mixed
     */
    public function generateName($version)
	{
		return $version->getAttributeByIdentifier('name')->typeData();
	}

    /**
     * @return string
     */
    public function template($version, $data = [])
	{
		return 'coanda-web-forms::pagetypes.form';
	}
}