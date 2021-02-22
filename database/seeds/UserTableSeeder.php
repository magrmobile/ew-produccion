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
        User::create(['name' => 'Marco Garcia', 'email' => 'magrmobile@gmail.com', 'username' => 'magrmobile', 'email_verified_at' => now(), 'password' => bcrypt('hanoi2979'), 'active_user' => 'enabled', 'role' => 'admin', 'process_id' => 1 ]);

        User::create([
            'name' => 'Operator 1',
            'email' => 'operator@gmail.com',
            'username' => 'operator',
            'email_verified_at' => now(),
            'password' => bcrypt('hanoi2979'), // secret
            'active_user' => 'enabled',
            'role' => 'operator',
            'process_id' => 1
        ]);

        User::create([
            'name' => 'Supervisor 1',
            'email' => 'supervisor@gmail.com',
            'username' => 'supervisor',
            'email_verified_at' => now(),
            'password' => bcrypt('hanoi2979'), // secret
            'active_user' => 'enabled',
            'role' => 'supervisor',
            'process_id' => 1
        ]);

        //factory(User::class, 20)->states('operator')->create();
        //factory(User::class, 5)->states('supervisor')->create();
        
        User::create(['name' => 'EFRAIN CHAVARRIA', 'email' => null, 'username' => 'SCHAVARRIA', 'email_verified_at' => now(), 'password' => bcrypt('SCHAVARRIA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'DAVID VASQUEZ', 'email' => null, 'username' => 'DVASQUEZ', 'email_verified_at' => now(), 'password' => bcrypt('DVASQUEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'VICTOR GARCIA', 'email' => null, 'username' => 'VGARCIA', 'email_verified_at' => now(), 'password' => bcrypt('VGARCIA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'ALFREDO MENDOZA', 'email' => null, 'username' => 'JMENDOZA', 'email_verified_at' => now(), 'password' => bcrypt('JMENDOZA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'MOISES PAREDES', 'email' => null, 'username' => 'MPAREDES', 'email_verified_at' => now(), 'password' => bcrypt('MPAREDES'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'DAVID PORTILLO', 'email' => null, 'username' => 'DPORTILLO', 'email_verified_at' => now(), 'password' => bcrypt('DPORTILLO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'RENZO GONZALEZ', 'email' => null, 'username' => 'RGONZALEZ', 'email_verified_at' => now(), 'password' => bcrypt('RGONZALEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'DARIO RODRIGUEZ', 'email' => null, 'username' => 'JRODRIGUEZ', 'email_verified_at' => now(), 'password' => bcrypt('JRODRIGUEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'EDWIN SANCHEZ', 'email' => null, 'username' => 'ESANCHEZ', 'email_verified_at' => now(), 'password' => bcrypt('ESANCHEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'LUDWIN MARTINEZ', 'email' => null, 'username' => 'LMARTINEZ', 'email_verified_at' => now(), 'password' => bcrypt('LMARTINEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'GERBER SOLA', 'email' => null, 'username' => 'GSOLA', 'email_verified_at' => now(), 'password' => bcrypt('GSOLA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'JULIO BERRIOS', 'email' => null, 'username' => 'JHERNANDEZ', 'email_verified_at' => now(), 'password' => bcrypt('JHERNANDEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'JULIO MELENDEZ', 'email' => null, 'username' => 'JMELENDEZ', 'email_verified_at' => now(), 'password' => bcrypt('JMELENDEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'SELVYN NERIO', 'email' => null, 'username' => 'SNERIO', 'email_verified_at' => now(), 'password' => bcrypt('SNERIO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'ALEXANDER CERRATO', 'email' => null, 'username' => 'ECERRATO', 'email_verified_at' => now(), 'password' => bcrypt('ECERRATO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'OSCAR GONZALEZ', 'email' => null, 'username' => 'OGONZALEZ', 'email_verified_at' => now(), 'password' => bcrypt('OGONZALEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'SAUL VARGAS', 'email' => null, 'username' => 'SVARGAS', 'email_verified_at' => now(), 'password' => bcrypt('SVARGAS'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'ALVARO CORTEZ', 'email' => null, 'username' => 'ACORTEZ', 'email_verified_at' => now(), 'password' => bcrypt('ACORTEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'JAVIER VASQUEZ', 'email' => null, 'username' => 'FVASQUEZ', 'email_verified_at' => now(), 'password' => bcrypt('FVASQUEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'RAFAEL SANCHEZ', 'email' => null, 'username' => 'ESANCHEZM', 'email_verified_at' => now(), 'password' => bcrypt('ESANCHEZM'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'JONNY VELASQUEZ', 'email' => null, 'username' => 'JVELASQUEZ', 'email_verified_at' => now(), 'password' => bcrypt('JVELASQUEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'IRVING RAMIREZ', 'email' => null, 'username' => 'IORELLANA', 'email_verified_at' => now(), 'password' => bcrypt('IORELLANA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'CARLOS BASILIO', 'email' => null, 'username' => 'CBASILIO', 'email_verified_at' => now(), 'password' => bcrypt('CBASILIO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'JAVIER FLORES', 'email' => null, 'username' => 'EFLORES', 'email_verified_at' => now(), 'password' => bcrypt('EFLORES'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'RONALD DUQUE', 'email' => null, 'username' => 'RDUQUE', 'email_verified_at' => now(), 'password' => bcrypt('RDUQUE'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'RAUL ESCOBAR', 'email' => null, 'username' => 'RESCOBAR', 'email_verified_at' => now(), 'password' => bcrypt('RESCOBAR'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'EDWIN MARTINEZ', 'email' => null, 'username' => 'EMARTINEZ', 'email_verified_at' => now(), 'password' => bcrypt('EMARTINEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'SAMUEL PAIZ', 'email' => null, 'username' => 'SPAIZ', 'email_verified_at' => now(), 'password' => bcrypt('SPAIZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'DENYS MEJIA', 'email' => null, 'username' => 'DMEJIA', 'email_verified_at' => now(), 'password' => bcrypt('DMEJIA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'ISAI MARTEL', 'email' => null, 'username' => 'IMARTEL', 'email_verified_at' => now(), 'password' => bcrypt('IMARTEL'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'CRISTIAN PEREZ', 'email' => null, 'username' => 'CPEREZ', 'email_verified_at' => now(), 'password' => bcrypt('CPEREZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'JONATHAN SOSA', 'email' => null, 'username' => 'JSOSA', 'email_verified_at' => now(), 'password' => bcrypt('JSOSA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'MIGUEL HERNANDEZ', 'email' => null, 'username' => 'MHERNANDEZ', 'email_verified_at' => now(), 'password' => bcrypt('MHERNANDEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'CARLOS GONZALEZ', 'email' => null, 'username' => 'CGONZALEZ', 'email_verified_at' => now(), 'password' => bcrypt('CGONZALEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'MIGUEL REVELO', 'email' => null, 'username' => 'LREVELO', 'email_verified_at' => now(), 'password' => bcrypt('LREVELO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'EVER HERNANDEZ', 'email' => null, 'username' => 'EHERNANDEZ', 'email_verified_at' => now(), 'password' => bcrypt('EHERNANDEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'NILSON MONTERROSA', 'email' => null, 'username' => 'NMONTERROSA', 'email_verified_at' => now(), 'password' => bcrypt('NMONTERROSA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'JAIRO LUARCA', 'email' => null, 'username' => 'JLUARCA', 'email_verified_at' => now(), 'password' => bcrypt('JLUARCA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'JUAN LOPEZ', 'email' => null, 'username' => 'JFUENTES', 'email_verified_at' => now(), 'password' => bcrypt('JFUENTES'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'HERBERT ALVARENGA', 'email' => null, 'username' => 'HALVARENGA', 'email_verified_at' => now(), 'password' => bcrypt('HALVARENGA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'JOSE BACILIO', 'email' => null, 'username' => 'JBACILIO', 'email_verified_at' => now(), 'password' => bcrypt('JBACILIO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'MANUEL PONCE', 'email' => null, 'username' => 'MPONCE', 'email_verified_at' => now(), 'password' => bcrypt('MPONCE'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'FREDI BARAHONA', 'email' => null, 'username' => 'FBARAHONA', 'email_verified_at' => now(), 'password' => bcrypt('FBARAHONA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'FERMIN ABARCA', 'email' => null, 'username' => 'FABARCA', 'email_verified_at' => now(), 'password' => bcrypt('FABARCA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'OSCAR PONCE', 'email' => null, 'username' => 'OPONCE', 'email_verified_at' => now(), 'password' => bcrypt('OPONCE'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'CARLOS REYES', 'email' => null, 'username' => 'CREYES', 'email_verified_at' => now(), 'password' => bcrypt('CREYES'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'JOSE TRUJILLO', 'email' => null, 'username' => 'JTRUJILLO', 'email_verified_at' => now(), 'password' => bcrypt('JTRUJILLO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'KEVIN DELGADO', 'email' => null, 'username' => 'KDELGADO', 'email_verified_at' => now(), 'password' => bcrypt('KDELGADO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'ABRAHAM RAMOS', 'email' => null, 'username' => 'ARAMOS', 'email_verified_at' => now(), 'password' => bcrypt('ARAMOS'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'EMERSON MEDRANO', 'email' => null, 'username' => 'HMEDRANO', 'email_verified_at' => now(), 'password' => bcrypt('HMEDRANO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'KEVIN RAMOS', 'email' => null, 'username' => 'KCERON', 'email_verified_at' => now(), 'password' => bcrypt('KCERON'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'MOISES HERNANDEZ', 'email' => null, 'username' => 'MHERNANDEZL', 'email_verified_at' => now(), 'password' => bcrypt('MHERNANDEZL'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'LEONEL SANCHEZ', 'email' => null, 'username' => 'LSANCHEZ', 'email_verified_at' => now(), 'password' => bcrypt('LSANCHEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'JULIO GUERRA', 'email' => null, 'username' => 'JGUERRA', 'email_verified_at' => now(), 'password' => bcrypt('JGUERRA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'ALEXANDER CARTAGENA', 'email' => null, 'username' => 'WMORALES', 'email_verified_at' => now(), 'password' => bcrypt('WMORALES'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'ELENILSON PEÑA', 'email' => null, 'username' => 'RPEÑA', 'email_verified_at' => now(), 'password' => bcrypt('RPEÑA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'BRIAM TREJO', 'email' => null, 'username' => 'BTREJO', 'email_verified_at' => now(), 'password' => bcrypt('BTREJO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'DANIEL PAREDES', 'email' => null, 'username' => 'DPAREDES', 'email_verified_at' => now(), 'password' => bcrypt('DPAREDES'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'EDUARDO HUEZO', 'email' => null, 'username' => 'EHUEZO', 'email_verified_at' => now(), 'password' => bcrypt('EHUEZO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'GEOVANI FUENTES', 'email' => null, 'username' => 'GFUENTES', 'email_verified_at' => now(), 'password' => bcrypt('GFUENTES'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'WALTER SALAZAR', 'email' => null, 'username' => 'WSALAZAR', 'email_verified_at' => now(), 'password' => bcrypt('WSALAZAR'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'DIEGO MERCADO', 'email' => null, 'username' => 'DMERCADO', 'email_verified_at' => now(), 'password' => bcrypt('DMERCADO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'ENRIQUE GRANADOS', 'email' => null, 'username' => 'EGRANADOS', 'email_verified_at' => now(), 'password' => bcrypt('EGRANADOS'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'KEVIN BACILIO', 'email' => null, 'username' => 'KBACILIO', 'email_verified_at' => now(), 'password' => bcrypt('KBACILIO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'CESAR PORTILLO', 'email' => null, 'username' => 'CPORTILLO', 'email_verified_at' => now(), 'password' => bcrypt('CPORTILLO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'JORGE ZALDAÑA', 'email' => null, 'username' => 'JZALDANA', 'email_verified_at' => now(), 'password' => bcrypt('JZALDANA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'ANIBAL HERNANDEZ', 'email' => null, 'username' => 'AHERNANDEZ', 'email_verified_at' => now(), 'password' => bcrypt('AHERNANDEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'DANIEL FACUNDO', 'email' => null, 'username' => 'DFACUNDO', 'email_verified_at' => now(), 'password' => bcrypt('DFACUNDO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'RICARDO SAMAYOA', 'email' => null, 'username' => 'JALFARO', 'email_verified_at' => now(), 'password' => bcrypt('JALFARO'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'ABEL RODRIGUEZ', 'email' => null, 'username' => 'ARODRIGUEZ', 'email_verified_at' => now(), 'password' => bcrypt('ARODRIGUEZ'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'JOSUE SOLA', 'email' => null, 'username' => 'JSOLA', 'email_verified_at' => now(), 'password' => bcrypt('JSOLA'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);



    }
}
