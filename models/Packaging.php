<?php namespace Bedard\USPS\Models;

use Model;

/**
 * Packaging Model
 */
class Packaging extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_usps_packagings';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}