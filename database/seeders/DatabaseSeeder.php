<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserTableSeeder::class);
        $this->command->info('Таблица пользователей загружена данными!');

        $this->call(PostTableSeeder::class);
        $this->command->info('Таблица постов блога загружена данными!');
    }
}
