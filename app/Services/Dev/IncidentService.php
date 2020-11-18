<?php 
namespace App\Services\Dev;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\IncidentInterface;
use App\Models\Incident;
use DB;

class IncidentService implements IncidentInterface  {

    /**
     *  Get Incident item list   
     *
     * @return Collection|null
    */
    public function get() : Collection
    {
         return Incident::all('id','location','title','comment','date','created_at','updated_at','category_id');
    }

    /**
     *  save Incident item    
     *
     * @return Model|null
    */
    public function post($incidentRequest) : ?Model
    {

    	return DB::transaction(function() use ($incidentRequest) {
            
            $incident = Incident::create($incidentRequest->all());

            if($incidentRequest->has('people')){

                 $incident->people()->createMany($incidentRequest->people);
            }
            /*
                @Todo: do some code logging or send email etc in production mode
            */
            
            return $incident;
        });

    }
}