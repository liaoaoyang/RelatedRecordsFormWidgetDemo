<?php namespace Your\Plugin\Models;

class Sample extends Model
{
    protected $table      = 'your_plugin_sample';
    protected $primaryKey = 'id';

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = ['published_at', 'updated_at', 'created_at'];
    protected $jsonable = ['related'];
}
