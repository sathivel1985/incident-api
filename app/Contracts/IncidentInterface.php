<?php 
namespace App\Contracts;

interface IncidentInterface 
{

    public function get();

    public function post(IncidentRequest $incidentRequest);

}
