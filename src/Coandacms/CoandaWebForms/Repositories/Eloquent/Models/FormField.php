<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Eloquent, Coanda, App;

class FormField extends Eloquent {

    protected $fillable = ['page_id', 'version_number', 'type', 'type_data', 'order'];

	protected $table = 'webformfields';

}