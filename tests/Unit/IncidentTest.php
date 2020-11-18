<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Incident;
use App\Models\Category;
use Tests\TestCase;

class IncidentTest extends TestCase
{

    use RefreshDatabase;
    
    /** @test */
    public function get_incident_list()
    {
        $response = $this->get(route('incidents.index'))
        ->assertStatus(self::HTTP_OK);
    }
    /**
     * Validate title.
     * @test
     * @return void
    */
    public function store_should_throw_an_error_if_title_is_missing()
    {
        $this->post('api/incidents')
            ->assertStatus(self::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['error_message'=>['title']]);
    }

    /**
     * validate location is missing .
     * @test
     * @return void
    */
    public function store_should_throw_an_error_if_location_is_missing()
    {
        $this->post('api/incidents')
            ->assertStatus(self::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['error_message'=>['location']]);
    }

    /**
     * validate category is missing .
     * @test
     * @return void
    */
    public function store_should_throw_an_error_if_category_is_missing()
    {
        $this->post('api/incidents')
            ->assertStatus(self::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['error_message'=>['category']]);
    }

    /**
     * validate category is must be valid .
     * @test
     * @return void
    */
    public function store_should_throw_an_error_if_category_is_must_be_valid()
    {
        $this->post('api/incidents',['category'=>4])
            ->assertStatus(self::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['error_message'=>['category']]);
    }

    /**
     * validate incident date is missing .
     * @test
     * @return void
    */
    public function store_should_throw_an_error_if_incident_date_is_missing()
    {
        $this->post('api/incidents')
            ->assertStatus(self::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['error_message'=>['incidentDate']]);
    }

    /**
     * validate incident date must be date and time(dd-mm-YYY H:i:s) .
     * @test
     * @return void
    */
    public function store_should_throw_an_error_if_incident_date_is_not_date_and_time()
    {
        $this->post('api/incidents',['incidentDate'=>'20-01-2020'])
            ->assertStatus(self::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['error_message'=>['incidentDate']]);
    }

    /**
     * validate people with name and type .
     * @test
     * @return void
    */
    public function store_should_throw_an_error_if_people_must_be_name_type_key()
    {   $payload = ['people'=>[
                            ['name' => 'sathivel']
                        ]
                    ];
         $this->post('api/incidents', $payload )
            ->assertStatus(self::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['error_message'=>['people']]);
    }

    /**
     * validate people with name and type is either staff,witness.
     * @test
     * @return void
    */
    public function store_should_throw_an_error_if_people_type_must_staff_or_witness()
    {   $payload = ['people'=>[
                            [
                                'name' => 'sathivel',
                                'type' => 'other'
                            ]
                        ]
                    ];
         $this->post('api/incidents', $payload )
            ->assertStatus(self::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['error_message'=>['people']]);
    }

    /**
     * create incident without people.
     * @test
     * @return void
     */

    public function create_incident_with_out_people()
    {
        $category = Category::factory()->create();
        $incident = [
            "location" => [
                "longitude" => "21221",
                "latitude" => "1221"
            ],
            "title" => "this is testing",
            "category" => $category->id,
            "incidentDate" => "20-10-2020 10:10:10"
        ];
         
        $this->post('api/incidents', $incident)
            ->assertStatus(self::HTTP_CREATED)
            ->assertJson(['data'=>['title'=>$incident['title']]]);

    }

    /**
     * create incident with people.
     * @test
     * @return void
     */

    public function create_incident_with_people()
    {
        $category = Category::factory()->create();
        $incidentPeople = [
            "location" => [
                "longitude" => "21221",
                "latitude" => "1221"
            ],
            "title" => "this is testing with people",
            "category" => $category->id,
            "incidentDate" => "20-10-2020 10:10:10",
            "people" => [
                [
                    "name" => "test user",
                    "type" => "witness"
                ]
            ]
        ];
         
        $this->post('api/incidents', $incidentPeople)
            ->assertStatus(self::HTTP_CREATED)
            ->assertJson(['data'=>['title'=>$incidentPeople['title']]]);


    }
    

    
}
