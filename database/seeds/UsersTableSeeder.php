<?php

use Illuminate\Database\Seeder;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            'name'  => 'test',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456')
        ]);

        User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@test.com',
            'password'          => '123456'
        ]);

        User::create([
            'name'              => 'Moderator User',
            'email'             => 'moderator@test.com',
            'password'          => '123456'
        ]);

        User::create([
            'name'              => 'Normal User',
            'email'             => 'user@test.com',
            'password'          => '123456'
        ]);

        User::create([
            'name'              => 'Tanh',
            'email'             => 'tanh@test.com',
            'password'          => '123456'
        ]);
    }
}
