<?php

return [

    'local' => [
        'class' => App\Services\Dev\IncidentService::class,
    ],

    'dev' => [
        'class' => App\Services\Dev\IncidentService::class,
    ],

    'production' => [
        'class' => App\Services\Production\IncidentService::class,
    ],
 	

];