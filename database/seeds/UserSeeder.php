<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Nicolás',
            'email' => 'pozas_91@hotmail.com',
            'password' => bcrypt('pozas91'),
        ]);
    }
}
