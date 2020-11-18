<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Incident;
use App\Models\Category;
use Carbon\Carbon;

class IncidentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Incident::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'location'   =>[
                            'latitude'   => $this->faker->latitude($min = -90, $max = 90),
                            'longitude'  => $this->faker->longitude($min = -180, $max = 180)
                          ],
            'title'      => $this->faker->sentence,
            'comment'    => $this->faker->paragraph,
            'category_id'=> Category::all()->random()->id,
            'date'       => $this->faker->dateTimeBetween('-1 month','now'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }
}
