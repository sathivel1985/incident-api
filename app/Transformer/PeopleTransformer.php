<?php 
namespace App\Transformer;

use League\Fractal\TransformerAbstract;
use App\Models\People;

class PeopleTransformer extends TransformerAbstract
{

     /**
     * Transform the our original database column name(attribute) to new column(attribute) name.
     *
     * @param  People  $People
     * @return array
     */
    public function transform(People $people)
    {
        return [
            'identifier'        => (int) $people->id,
            'name'              => (string) $people->name,
            'type'              => (string) $people->type,
        ];
    }

    /**
     * Convert from api input field name to original attribute name.
     *
     * @param  string  $index
     * @return array
    */
    public static function originalAttribute($index)
    {
    	$attributes =[
    		'identifier'          => 'id',
    		'name'                => 'name',
            'type'                 => 'type',
    		'creationDate'        => 'created_at',
    		'lastChange'          => 'updated_at',
    		'deletedDate'         => 'deleted_at'

    	];

    	return isset($attributes[$index]) ? $attributes[$index]: null;
    }


    /**
     *  Get transformed attribute name.
     *
     * @param  string  $index
     * @return string
    */
    public static function transformedAttribute($index) 
    {
        $attributes = [
            'id'                   => 'identifier',
            'name'                 => 'name',
            'type'                 => 'type',
            'created_at'           => 'creationDate',
            'updated_at'           => 'lastChange',
            'deleted_at'           => 'deletedDate'
        ];
        return isset($attributes[$index]) ? $attributes[$index]: null;
    }

 
}