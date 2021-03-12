<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        
        // создать 4 пользователя
        //factory(App\Models\User::class, 4)->create();
       \App\Models\User::factory()->count(4)->create();
    }
}
