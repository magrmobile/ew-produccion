<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Marco Garcia',
            'email' => 'magrmobile@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('hanoi2979'), // secret
            'active_user' => 'enabled',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Operator 1',
            'email' => 'operator@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('hanoi2979'), // secret
            'active_user' => 'enabled',
            'role' => 'operator',
        ]);

        User::create([
            'name' => 'Supervisor 1',
            'email' => 'supervisor@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('hanoi2979'), // secret
            'active_user' => 'enabled',
            'role' => 'supervisor',
        ]);

        factory(User::class, 20)->states('operator')->create();

        factory(User::class, 5)->states('supervisor')->create();
    }
}
