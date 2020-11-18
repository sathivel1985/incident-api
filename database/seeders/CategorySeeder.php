<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        /* $categories = [
            ['name' => 'Security'],
            ['name' => 'Health & Safety'],
            ['name' => 'Loss Prevention']
        ];
        foreach ($categories as $category) {
            Category::create([
                'name' => $category["name"]
            ]);
        } */

          \App\Models\Category::factory(3)->create();

    }
}
