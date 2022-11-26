<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        DB::table('users')->insert([
            [
                'first_name' => 'Agent',
                'last_name' => 'Agent',
                'email' => 'test_agent@jmnation.com',
                'password' => Hash::make('1234'),
            ],
        ]);
    }
}
