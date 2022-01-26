<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        $basic_data = database_path('sql/basic_data.sql');
        DB::unprepared(file_get_contents($basic_data));
        $this->command->info('Basic table seeded!');
    }
}
