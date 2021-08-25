<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        User::firstOrCreate(
            ['email' => "jdoe@mail.com"],
            [
                'name' => Factory::create()->name,
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt("1q2w3e"),
                'remember_token' => Str::random(10)
            ]
        );
    }
}
