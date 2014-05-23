<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Eloquent, Coanda, App;

/**
 * Class FormField
 * @package CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models
 */
class FormField extends Eloquent {

    /**
     * @var array
     */
    protected $fillable = ['page_id', 'version_number', 'type', 'type_data', 'order'];

    /**
     * @var string
     */
    protected $table = 'webformfields';

    /**
     * @param $data
     */
    public function setTypeData($data)
	{
		$this->type_data = json_encode($data);
	}

    /**
     * @return mixed
     */
    public function typeData()
	{
		return json_decode($this->type_data, true);
	}

}