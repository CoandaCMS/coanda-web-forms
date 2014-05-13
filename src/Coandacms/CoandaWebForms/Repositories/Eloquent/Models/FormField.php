<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Eloquent, Coanda, App;

class FormField extends Eloquent {

    protected $fillable = ['page_id', 'version_number', 'type', 'type_data', 'order'];

	protected $table = 'webformfields';

	public function setTypeData($data)
	{
		$this->type_data = json_encode($data);
	}

	public function typeData()
	{
		return json_decode($this->type_data, true);
	}

}