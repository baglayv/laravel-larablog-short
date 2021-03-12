<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Создать 20 постов блога
        //factory(App\Models\Post::class, 20)->create();
        \App\Models\Post::factory()->count(20)->create();
    }
}
