<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class IncidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\Incident::factory(5)
         						->hasPeople(2)
         						->create();
    }
}
