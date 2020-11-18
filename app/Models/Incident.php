<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Transformer\IncidentTransformer;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\People;
use Carbon\Carbon;

class Incident extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location',
        'category_id',
        'title',
        'comment',
        'date',
        'created_at',
        'updated_at'
    ];

    protected $casts = [ 
		'location' => 'json' // save location as a json column,
 	];


 	/**
     * The incident transformer properties
     * @var IncidentTransformer class
    */
    public $transformer = IncidentTransformer::class;

    /**
     * The incident  belongs to category
     * @return collection
    */
    public function category(){
        return $this->belongsTo(Category::class);
    }

    /**
     * The incident has many peoples
     * @return collection
    */
    public function people(){
        return $this->hasMany(People::class);
    }
    /**
     * Set the incident's date Y-m-d H:i:s.
     *
     * @param  string  $value
     * @return void
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d H:i:s'); 
    }

    /**
	 * Get the date format with d-m-Y H:i:s.
	 *
	 * @return string
	 */
	public function getDateAttribute($value)
	{
	    return Carbon::parse($value)->format('d-m-Y H:i:s');
	}

	/**
     * Set the update at's format with Y-m-d H:i:s.
     *
     * @param  string  $value
     * @return void
     */
    public function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = $value ? 
        							Carbon::parse($value)->format('Y-m-d H:i:s') : Carbon::now(); 
    }

    /**
	 * Get the update format with d-m-Y H:i:s.
	 *
	 * @return string
	 */
	public function getUpdatedAtAttribute($value)
	{
	    return Carbon::parse($value)->format('d-m-Y H:m:s');
	}

		/**
     * Set the update at's format with Y-m-d H:i:s.
     *
     * @param  string  $value
     * @return void
     */
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = $value ? 
        							Carbon::parse($value)->format('Y-m-d H:m:s') : Carbon::now(); 
    }

    /**
	 * Get the update format with d-m-Y H:i:s.
	 *
	 * @return string
	 */
	public function getCreatedAtAttribute($value)
	{
	    return Carbon::parse($value)->format('d-m-Y H:m:s');
	}

    
}
