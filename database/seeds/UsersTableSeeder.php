<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        DB::table('users')->insert([
                'firstname' => 'John',
                'lastname' => 'Doe',
                'email' => 'demo@demo.com',
                'password' => app('hash')->make('demodemo'),
                'remember_token' => str_random(40),
                'status' => 1,
                'admin' => 1
            ]);
    }
}
