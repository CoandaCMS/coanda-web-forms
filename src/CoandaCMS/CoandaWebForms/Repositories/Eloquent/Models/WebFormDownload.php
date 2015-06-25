<?php namespace CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models;

use Carbon\Carbon;
use Coanda;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Queue;

/**
 * Class WebFormDownload
 * @package CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models
 */
class WebFormDownload extends Model {

    /**
     * @var array
     */
    protected $fillable = ['webform_id', 'status', 'status_percentage', 'filename'];

    /**
     * @var string
     */
    protected $table = 'coanda_webform_downloads';

    /**
     * 
     * @return void
     */
    public static function boot()
    {

        parent::boot();
    }

    /**
     * @param array $options
     */
    public function save(array $options = [])
    {
        
        parent::save($options);
    }

    public function delete(array $options = [])
    {

        parent::delete($options);
    }

    /**
     * @return mixed
     */
    public function form()
    {
        return $this->belongsTo('CoandaCMS\CoandaWebForms\Repositories\Eloquent\Models\WebForm', 'webform_id');
    }

    /**
     * Add a task to the queue for the webform
     * submissions list CSV to be created, and save
     * this WebFormDownload isntance to the database.
     * 
     * @return boolean
     */
    public function queue()
    {
        $this->save();

        Queue::push('CoandaCMS\CoandaWebForms\Queue\CreateCsv', $this);
    }

    /**
     * Has the queue task been run and has it completed?
     * 
     * @return boolean
     */
    public function queueTaskHasRun()
    {
        if ($this->status <= 1) {
            return false;
        }

        return true;
    }

    /**
     * How old is the file?
     * 
     * @return mixed Returns false if the file doesn't exist yet
     */
    public function age()
    {
        if (!$this->available()) {
            return false;
        }

        return Carbon::parse( $this->updated_at )->diffForHumans();
    }

    /**
     * Returns true if the file exists. 
     * First checks if the task is run, since there's not point
     * checking the filesystem if the task hasn't run yet!
     * 
     * @return boolean
     */
    public function available()
    {
        if (!$this->queueTaskHasRun()) {
            return false;
        }

        return true;
    }

    /**
     * Accessor for status_percentage
     *
     * Takes the decimal value from the status_percentage
     * column and appends the percentage (%) symbol.
     *
     * @return  string
     */
    public function getStatusPercentageAttribute($value)
    {
        return round($value) . '%';
    }

    /**
     * Mutator for status_percentage
     *
     * Takes the calculated percentage and converts it
     * to the correct format for the database:
     *     decimal(3,0)
     * 
     * @param [type] $value [description]
     */
    public function setStatusPercentageAttribute($value)
    {
        if ($value < 0) {
            $value = 0;
        }

        if ($value > 100) {
            $value = 100;
        }

        $this->attributes['status_percentage'] = round($value);
    }

}