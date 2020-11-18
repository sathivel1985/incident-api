<?php 

namespace App\Traits;

use Validator;
use League\Fractal;
use Illuminate\Support\Str;
use League\Fractal\Manager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{	
	/**
     *  display the success json response to the user  
     *
     * @param  Array  $data
     * @param  Response Status  $code ( 200 , 201 etc) 
     * @return Json
    */
	protected function successResponse($data, $code) 
	{
		return response()->json($data,$code);
	}

	/**
     *  display the error json response to the user  
     *
     * @param  string  $message
     * @param  Response Status  $code ( 401 , 422 etc) 
     * @return Json
    */
	protected function errorResponse($message, $code)
	{
		
		return response()->json(['error_message'=>$message,'code'=>$code], $code);
	}

	/**
     *  Get collection convert the collection data with new attribute name 
     *
     * @param  Collection  $collection
     * @param  $code default = 200 
     * @return array
    */
	protected function ShowAll(Collection $collection, $code = 200)
	{
		if($collection->isEmpty()) {

			return $this->successResponse($collection,$code);

		}
		
		$transformer = $collection->first()->transformer;

		/*
		 * function call for filter value from collection
		 */
		$collection = $this->filterData($collection, $transformer);
		
		/*
		 * function call for sort by field from collection  
	     */
		$collection = $this->sortData($collection, $transformer);

		/*
		  *function call for custom pagination 
		 */ 
		$collection  = $this->paginate($collection);

		/*
		  *function call for transform the orginal attribute name new attribute name 
		 */ 
		$collection = $this->transformData($collection,$transformer);

		/*
		  *function call for cach the response 
		 */ 
		$collection = $this->cacheResponse($collection);
		
		
		return $this->successResponse($collection,$code);
	}

	/**
     *  Get single instance or model convert the model data with new attribute name 
     *
     * @param  Model  $instance
     * @param  $code default = 200 
     * @return array
    */
	protected function showOne(Model $instance, $code = 200)
	{
		 
		$transformer = $instance->transformer;

		$instance = $this->transformData($instance, $transformer);

		return $this->successResponse($instance,$code);
	}

	/**
     *  Show the success message  
     *
     * @param  string  $message
     * @param  $code default = 200 
     * @return array
    */
	protected function showMessage($message,$code=200)
	{
		return $this->successResponse(['data' => $message],$code);
	}

	/**
     *  show the custome data array   
     *
     * @param  array  $data
     * @param  $code default = 200 
     * @return array
    */
	protected function showCustomArray(array $data, $code = 200)
	{
		return $this->successResponse($data,$code);
	}

	/**
     *  Sort the collection based on the query string 
     *
     * @param  Collection  $collection
     * @param  Class  $transformer 
     * @return Collection
    */
	protected function sortData(Collection $collection, $transformer)
	{
		if( request()->has('sort_by') ) {
			
			$attribute = $transformer::originalAttribute(request()->sort_by);

			if ( request()->has('order_by') && strtolower(request()->order_by) ==='desc' ) {

				$collection = $collection->sortByDesc->{$attribute};

			} else {

				$collection = $collection->sortBy->{$attribute};

			}

		}

		return $collection;
	}

	/**
     *  filter the collection based on the query string 
     *
     * @param  Collection  $collection
     * @param  Class  $transformer 
     * @return Collection
    */
	protected function filterData(Collection $collection, $transformer)
	{
		 
		foreach (request()->query() AS $query => $value) {
			
			$attribute = $transformer::originalAttribute($query);

			if (isset($attribute, $value) && !Str::of($attribute)->contains('date')) {
				 
					$collection = $collection->where($attribute,$value);
			}

		}
		return $collection;
	}

	/**
     *  Custome pagination  
     *
     * @param  Collection  $collection
     * @return Collection
    */
	protected function paginate(Collection $collection)
	{

		if( request()->has('allData') ) {
			
			return $collection;
		}
		 
		$rules = [
			'per_page' => 'integer | min:1|max:50',
		];

		Validator::validate(request()->all(), $rules);

		$page = LengthAwarePaginator::resolveCurrentPage();

		$perPage = config('settings.pagination.items_per_page');

		if( request()->has('per_page') )
		{
			$perPage = (int) request()->per_page;
		}

		$results = $collection->slice( ($page - 1) * $perPage , $perPage )->values();

		$paginated = new LengthAwarePaginator($results,$collection->count(), $perPage,$page,[
			'path' => LengthAwarePaginator::resolveCurrentPath(),
		]);

		$paginated->appends(request()->all());
		
		$collection = $paginated;

		return $collection;

	}

	/**
     *  Transform the orginal attribute to New Attribute  
     *
     * @param  Array|Collection  $data
     * @param  Class  $transformer 
     * @return Collection
    */
	protected function transformData($data, $transformer)
	{
 
		if ($data instanceof Collection) 
		{
        	$transformation = new Fractal\Resource\Collection($data, new $transformer);
	    } 
	    else 
	    {
	        $transformation = new Fractal\Resource\Item($data, new $transformer);
	    }

	    $fractalClass = config('fractal.fractal_class') ?? Fractal::class;

	    $fractal =  $fractalClass::create($data, $transformer, null);

	    if( request()->has('exclude') ) {
			
		   $fractal =  $fractal->parseExcludes(request()->exclude);

		}
		if( request()->has('include') ) {

		   $fractal =  $fractal->parseincludes(request()->include);

		}

		return $fractal;

        
	}

	/**
     *  Cache the response with certain of amount of time
     *
     * @param  Array  $data
     * @return array
    */
	protected function cacheResponse($data)
	{
		$url = request()->url();
		$queryParams = request()->query();

		ksort($queryParams);

		$queryString = http_build_query($queryParams);

		$fullUrl = "{$url}?{$queryString}";

		return Cache::remember($fullUrl, 30/60, function() use($data) {
			return $data;
		});
	}

}