<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\People;

class PeopleArray implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  array  $array
     * @return bool
     */
    public function passes($attribute, $array)
    {   
        if(is_null($array)){
            return true;
        }

        if(is_array($array)){

            foreach($array AS $arrayItem ){
 
                $arrayItemKeys = array_keys($arrayItem);
 
                if(in_array('name', $arrayItemKeys) && in_array('type',$arrayItemKeys))
                {
                    foreach($arrayItem AS $key =>$value ){

                        if(in_array($key,['name','type']) && !empty($value)){

                            if( ($key === 'type' && in_array($value,People::TYPES)) || $key === 'name') {
                                
                               continue;  

                            }else{
                                
                                return false;
                            }
                            
                        }else{
        
                            return false;
                        }
                    }
                }else{
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The people array must be name,type and type will be '.implode(',',People::TYPES);
    }
}
