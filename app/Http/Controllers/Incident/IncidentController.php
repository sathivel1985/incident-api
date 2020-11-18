<?php

namespace App\Http\Controllers\Incident;

use App\Transformer\IncidentTransformer;
use App\Http\Controllers\ApiController;
use App\Http\Requests\IncidentRequest;
use App\Contracts\IncidentInterface;
use Illuminate\Http\Request;
use App\Models\Incident;

class IncidentController extends ApiController
{
    
    public $incidentInterface;

    /**
     * default initialize the dependency injection 
     *
     * @param IncidentInterface $IncidentInterface 
     */
    public function __construct(IncidentInterface $incidentInterface)
    {
        $this->incidentInterface = $incidentInterface;

        $this->middleware('transform.input:' . IncidentTransformer::class)->only(['store']); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incident = $this->incidentInterface->get();
        
        return $this->showAll($incident);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IncidentRequest $incidentRequest)
    {

        $incident = $this->incidentInterface->post($incidentRequest);

        return $this->showOne($incident,201);

    }

     

     
}
