<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Transformer\PeopleTransformer;

class People extends Model
{
    use HasFactory;

   /**
   * People' types
   * 
   * @var array
   */
   public const TYPES = [
      'staff',
      'witness'
   ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'incident_id',
    ];
   /**
     * The category transformer properties
     * @var CategoryTransformer class
    */
    public $transformer = PeopleTransformer::class;
}
