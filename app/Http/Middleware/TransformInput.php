<?php

namespace App\Http\Middleware;

use Illuminate\Validation\ValidationException as ValidationException;
use Closure;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        
        $transformedInput = [];

        foreach($request->all() as $input => $value) {

             $transformedInput[$transformer::originalAttribute($input)] = $value;

        }
        
        $request->replace($transformedInput);

        $response = $next($request);

        if( isset($response->exception) && $response->exception instanceof ValidationException ) {

            $data = $response->getData();

            $transformedErrors = [];

            foreach ($data->error_message as $field => $error) {
                
                if(strpos($field, '.')){

                    $split_array = explode('.', $field);

                    $field = $split_array[0];
                }

                $transformedField = $transformer::transformedAttribute($field);
                 
                if(empty($transformedErrors[$transformedField])){
                     $transformedErrors[$transformedField] = str_replace($field, $transformedField, $error);
                }else{
                     $transformedErrors[$transformedField] = 
                     array_merge($transformedErrors[$transformedField],str_replace($field, $transformedField, $error));
                }
               
            }

            $data->error_message = $transformedErrors;

            $response->setData($data);
        }
        return $response;
    }
}
