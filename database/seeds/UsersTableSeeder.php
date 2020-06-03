<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'cindy.adonia3@gmail.com',
            'password' => bcrypt('123'),
            'role_id' => 1
        ]);
    }
}
