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
        User::create(['name' => 'Marco Garcia', 'email' => 'magrmobile@gmail.com', 'username' => 'magrmobile', 'email_verified_at' => now(), 'password' => bcrypt('hanoi2979'), 'active_user' => 'enabled', 'role' => 'admin' ]);

        User::create([
            'name' => 'Operator 1',
            'email' => 'operator@gmail.com',
            'username' => 'operator',
            'email_verified_at' => now(),
            'password' => bcrypt('hanoi2979'), // secret
            'active_user' => 'enabled',
            'role' => 'operator',
        ]);

        User::create([
            'name' => 'Supervisor 1',
            'email' => 'supervisor@gmail.com',
            'username' => 'supervisor',
            'email_verified_at' => now(),
            'password' => bcrypt('hanoi2979'), // secret
            'active_user' => 'enabled',
            'role' => 'supervisor',
        ]);

        //factory(User::class, 20)->states('operator')->create();
        //factory(User::class, 5)->states('supervisor')->create();

        User::create(['name' => 'Alexander Cerrato', 'email' => null, 'username' => 'acerrato', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Anibal Hernandez', 'email' => null, 'username' => 'ahernandez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Alfredo Mendoza', 'email' => null, 'username' => 'amendoza', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Alvaro Ortega', 'email' => null, 'username' => 'aortega', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Alexis Reyes', 'email' => null, 'username' => 'areyes', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Antonio Sola', 'email' => null, 'username' => 'asola', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Brandon Ayala', 'email' => null, 'username' => 'bayala', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Carlos Arquera', 'email' => null, 'username' => 'carquera', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Carlos Basilio', 'email' => null, 'username' => 'cbasilio', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Carlos Gomez', 'email' => null, 'username' => 'cgomez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Carlos Gonzalez', 'email' => null, 'username' => 'cgonzalez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Cristian Perez', 'email' => null, 'username' => 'cperez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Daniel Flores', 'email' => null, 'username' => 'dflores', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Denys Mejia', 'email' => null, 'username' => 'dmejia', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Diego Mercado', 'email' => null, 'username' => 'dmercado', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'David Portillo', 'email' => null, 'username' => 'dportillo', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Dario Rodriguez', 'email' => null, 'username' => 'drodriguez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'David Vasquez', 'email' => null, 'username' => 'dvasquez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Edwin Barahona', 'email' => null, 'username' => 'ebarahona', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Efrain Chavarria', 'email' => null, 'username' => 'echavarria', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Ever Hernandez', 'email' => null, 'username' => 'ehernandez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Eduardo Huezo', 'email' => null, 'username' => 'ehuezo', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Edwin Martinez', 'email' => null, 'username' => 'emartinez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Edwin Sanchez', 'email' => null, 'username' => 'esanchez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Erasmo Segura', 'email' => null, 'username' => 'esegura', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Geovani Fuentes', 'email' => null, 'username' => 'gfuentes', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Geremias Guzman', 'email' => null, 'username' => 'gguzman', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Herbert Alvarenga', 'email' => null, 'username' => 'halvarenga', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Henry Mendez', 'email' => null, 'username' => 'hmendez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Isai Martel', 'email' => null, 'username' => 'imartel', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Ivan Villanueva', 'email' => null, 'username' => 'ivillanueva', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Jose Bacilio', 'email' => null, 'username' => 'jbacilio', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Julio Berrios', 'email' => null, 'username' => 'jberrios', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Jairo Bonilla', 'email' => null, 'username' => 'jbonilla', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Javier Flores', 'email' => null, 'username' => 'jflores', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Juan Lopez', 'email' => null, 'username' => 'jlopez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Julio Melendez', 'email' => null, 'username' => 'jmelendez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Josue Sola', 'email' => null, 'username' => 'jsola', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Jonathan Sosa', 'email' => null, 'username' => 'jsosa', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Javier Vasquez', 'email' => null, 'username' => 'jvasquez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Jonny Velasquez', 'email' => null, 'username' => 'jvelasquez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Jorge Zaldaña', 'email' => null, 'username' => 'jzaldaña', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Kevin Bacilio', 'email' => null, 'username' => 'kbacilio', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Ludwin Martinez', 'email' => null, 'username' => 'lmartinez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Luis Revelo', 'email' => null, 'username' => 'lrevelo', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Marvin Escobar', 'email' => null, 'username' => 'mescobar', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Manuel Hernandez', 'email' => null, 'username' => 'mahernandez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Miguel Hernandez', 'email' => null, 'username' => 'mihernandez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Moises Paredes', 'email' => null, 'username' => 'mparedes', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Manuel Ponce', 'email' => null, 'username' => 'mponce', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Nelvin Menjivar', 'email' => null, 'username' => 'nmenjivar', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Nilson Monterrosa', 'email' => null, 'username' => 'nmonterrosa', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Oscar Gonzalez', 'email' => null, 'username' => 'ogonzalez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Pablo Rodriguez', 'email' => null, 'username' => 'prodriguez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Rene Ayala', 'email' => null, 'username' => 'rayala', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Ronald Duque', 'email' => null, 'username' => 'rduque', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Raul Escobar', 'email' => null, 'username' => 'rescobar', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Renzo Gonzalez', 'email' => null, 'username' => 'rgonzalez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Raul Martinez', 'email' => null, 'username' => 'rmartinez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Rafael Sanchez', 'email' => null, 'username' => 'rsanchez', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Salvador Bautista', 'email' => null, 'username' => 'sbautista', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Selvyn Nerio', 'email' => null, 'username' => 'snerio', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Samuel Paiz', 'email' => null, 'username' => 'spaiz', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Saul Vargas', 'email' => null, 'username' => 'svargas', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Victor Garcia', 'email' => null, 'username' => 'vgarcia', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'Walter Salazar', 'email' => null, 'username' => 'wsalazar', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);
        User::create(['name' => 'William Zelaya', 'email' => null, 'username' => 'wzelaya', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator' ]);

    }
}
