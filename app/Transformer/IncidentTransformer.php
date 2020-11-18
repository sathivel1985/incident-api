<?php 
namespace App\Transformer;

use League\Fractal\TransformerAbstract;
use App\Transformer\CategoryTransformer;
use App\Transformer\PeopleTransformer;
use App\Models\Incident;


class IncidentTransformer extends TransformerAbstract
{

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'people',
        'category'
    ];

    /**
     * Transform the our original database column name(attribute) to new column(attribute) name.
     *
     * @param  Incident  $incident
     * @return array
     */
    public function transform(Incident $incident)
    {
        return [
            'identifier'        => (int) $incident->id,
            'location'          => json_decode(json_encode($incident->location),true),
            'title'             => (string) $incident->title,
            'comments'          => (string) $incident->comment,
            'incidentDate'      => (string) $incident->date,
            'createDate'        => (string) $incident->created_at,
            'modifyDate'        => (string) $incident->updated_at
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
            'location'            => 'location',
    		'title'               => 'title',
            'category'            => 'category_id',
            'comments'            => 'comment',
            'people'              => 'people',
            'incidentDate'        => 'date',
    		'createDate'          => 'created_at',
    		'modifyDate'          => 'updated_at',
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
            'location'             => 'location',
            'title'                => 'title',
            'category_id'          => 'category',
            'comment'              => 'comments',
            'people'               => 'people',
            'date'                 => 'incidentDate',
            'created_at'           => 'createDate',
            'updated_at'           => 'modifyDate',
            'deleted_at'           => 'deletedDate'
        ];
        return isset($attributes[$index]) ? $attributes[$index]: null;
    }

    public function includePeople(Incident $incident)
    {
        $people = $incident->people;
        if(  $people ) {
             return $this->collection($people, new PeopleTransformer);
        }
    } 

    public function includeCategory(Incident $incident)
    {
        $category = $incident->category;
        if(  $category ) {
             return $this->item($category, new CategoryTransformer);
        } 
    } 

 
}