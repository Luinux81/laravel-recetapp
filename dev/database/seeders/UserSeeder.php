<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
            'name'=>env("SEED_USER_NAME"),
            'email'=>env("SEED_USER_EMAIL"),
            'password'=>Hash::make(env("SEED_USER_PASS")),
        ]);
    }
}
