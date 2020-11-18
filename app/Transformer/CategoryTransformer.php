<?php 
namespace App\Transformer;

use League\Fractal\TransformerAbstract;
use App\Models\Category;

class CategoryTransformer extends TransformerAbstract
{

     /**
     * Transform the our original database column name(attribute) to new column(attribute) name.
     *
     * @param  Category  $category
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier'        => (int) $category->id,
            'title'              => (string) $category->name,
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
    		'title'                => 'name',
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
            'name'                 => 'title',
            'created_at'           => 'creationDate',
            'updated_at'           => 'lastChange',
            'deleted_at'           => 'deletedDate'
        ];
        return isset($attributes[$index]) ? $attributes[$index]: null;
    }

 
}