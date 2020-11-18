<?php
namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException AS AuthorizationException;
use App\Traits\ApiResponser;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller 
{
	use ApiResponser;

    /*   
    @Todo : Restrict the logged in user to access 

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    @Todo : Restriction to access certain controller function 

    protected function allowedAdminAction() 
    {
    	if( Gate::denies('admin-action') ) {
            throw new AuthorizationException("This action is Unauthorized "); 
        }
    }

    ...
    
    */
     
} 