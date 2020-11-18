<?php

namespace Database\Factories;

use App\Models\People;
use App\Models\Incident;
use Illuminate\Database\Eloquent\Factories\Factory;

class peopleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = people::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'incident_id' => Incident::factory(),
            'name'        => $this->faker->name,
            'type'        => $this->faker->randomELement(People::TYPES),
        ];
    }
}
