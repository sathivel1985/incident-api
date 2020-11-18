<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Transformer\CategoryTransformer;
use Illuminate\Database\Eloquent\Model;
use App\Models\Incident;

class Category extends Model
{
    use HasFactory;

    /**
     * The table name .
     *
     * @var string
     */
    protected $table = "categories";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */

    protected $fillable = [
        'name'
    ];

    /**
     * The category transformer properties
     * @var CategoryTransformer class
    */
    public $transformer = CategoryTransformer::class;

    public function categories(){
        return $this->belongsToMany(Incident::class);
    }

}
