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

        User::create(['name' => 'SANTOS EFRAIN CHAVARRIA LOVO', 'email' => null, 'username' => 'SCHAVARRIA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'DAVID ORLANDO VASQUEZ PAREDES', 'email' => null, 'username' => 'DVASQUEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'VICTOR MANUEL GARCIA MORALES', 'email' => null, 'username' => 'VGARCIA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'JOSE ALFREDO MENDOZA', 'email' => null, 'username' => 'JMENDOZA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'MOISES PAREDES FLORES', 'email' => null, 'username' => 'MPAREDES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'DAVID DE JESUS PORTILLO MOLINA', 'email' => null, 'username' => 'DPORTILLO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'RONY RENZO GONZALEZ DIAZ', 'email' => null, 'username' => 'RGONZALEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'JORGE DARIO RODRIGUEZ MEJIA', 'email' => null, 'username' => 'JRODRIGUEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'EDWIN ALEXANDER SANCHEZ SANCHEZ', 'email' => null, 'username' => 'ESANCHEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'LUDWIN ERNESTO MARTINEZ VIGIL', 'email' => null, 'username' => 'LMARTINEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'GERBER ANTONIO SOLA LOPEZ', 'email' => null, 'username' => 'GSOLA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'JULIO ALBERTO HERNANDEZ BERRIOS', 'email' => null, 'username' => 'JHERNANDEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'JULIO EDUARDO MELENDEZ ECHEVERRIA', 'email' => null, 'username' => 'JMELENDEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'SELVYN PATRICIO NERIO SANCHEZ', 'email' => null, 'username' => 'SNERIO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'EFRAIN ALEXANDER CERRATO ESCOLERO', 'email' => null, 'username' => 'ECERRATO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'OSCAR ARMANDO GONZALEZ CRUZ', 'email' => null, 'username' => 'OGONZALEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'SAUL ERNESTO VARGAS ARRIAZA', 'email' => null, 'username' => 'SVARGAS', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'ALVARO ALEJANDRO CORTEZ ORTEGA', 'email' => null, 'username' => 'ACORTEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'FRANCISCO JAVIER VASQUEZ PEREZ', 'email' => null, 'username' => 'FVASQUEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'EDGARDO RAFAEL SANCHEZ MEJIA', 'email' => null, 'username' => 'ESANCHEZM', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'JONNY NOE VELASQUEZ QUIÑONEZ', 'email' => null, 'username' => 'JVELASQUEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'IRVING ALEXANDER ORELLANA RAMIREZ', 'email' => null, 'username' => 'IORELLANA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'CARLOS HERIBERTO BASILIO', 'email' => null, 'username' => 'CBASILIO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'EDGAR JAVIER FLORES PEREZ', 'email' => null, 'username' => 'EFLORES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'RONALD ERNESTO DUQUE ALAS', 'email' => null, 'username' => 'RDUQUE', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'RAUL ALEXANDER ESCOBAR CAMPOS', 'email' => null, 'username' => 'RESCOBAR', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'EDWIN ALEXANDER MARTINEZ RAMOS', 'email' => null, 'username' => 'EMARTINEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'SAMUEL OSEAS PAIZ CORTEZ', 'email' => null, 'username' => 'SPAIZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'DENYS JOSE MEJIA MARTINEZ', 'email' => null, 'username' => 'DMEJIA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'ISAI OTONIEL MARTEL FUENTES', 'email' => null, 'username' => 'IMARTEL', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'CRISTIAN MAURICIO PEREZ HERNANDEZ', 'email' => null, 'username' => 'CPEREZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'JONATHAN NEHEMIAS SOSA HERNANDEZ', 'email' => null, 'username' => 'JSOSA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'MIGUEL ELEAZAR HERNANDEZ BELTRAN', 'email' => null, 'username' => 'MHERNANDEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'CARLOS DAVID GONZALEZ ARTIGA', 'email' => null, 'username' => 'CGONZALEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'LUIS MIGUEL REVELO FLORES', 'email' => null, 'username' => 'LREVELO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'EVER NAHUN HERNANDEZ BELTRAN', 'email' => null, 'username' => 'EHERNANDEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'NILSON BENJAMIN MONTERROSA MEJIA', 'email' => null, 'username' => 'NMONTERROSA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'JAIRO ENRIQUE LUARCA BONILLA', 'email' => null, 'username' => 'JLUARCA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'JUAN CARLOS FUENTES LOPEZ', 'email' => null, 'username' => 'JFUENTES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'HERBERT ELISEO ALVARENGA PACHECO', 'email' => null, 'username' => 'HALVARENGA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'JOSE ANTONIO BACILIO ANDRES', 'email' => null, 'username' => 'JBACILIO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'MANUEL ALBERTO PONCE OSORIO', 'email' => null, 'username' => 'MPONCE', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'FREDI BALTAZAR BARAHONA CHAVEZ', 'email' => null, 'username' => 'FBARAHONA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'FERMIN DE JESUS ABARCA CRUZ', 'email' => null, 'username' => 'FABARCA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'OSCAR ALCIDES PONCE OSORIO', 'email' => null, 'username' => 'OPONCE', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'CARLOS REYES CHACON', 'email' => null, 'username' => 'CREYES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'JOSE AUDELINO TRUJILLO ESCOBAR', 'email' => null, 'username' => 'JTRUJILLO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'KEVIN ALEXANDER DELGADO ROGEL', 'email' => null, 'username' => 'KDELGADO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'ABRAHAM ISRAEL RAMOS SOLA', 'email' => null, 'username' => 'ARAMOS', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'HECTOR EMERSON MEDRANO GIRON', 'email' => null, 'username' => 'HMEDRANO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'KEVIN OSWALDO CERON RAMOS', 'email' => null, 'username' => 'KCERON', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'MOISES ELIAS HERNANDEZ LOPEZ', 'email' => null, 'username' => 'MHERNANDEZL', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'LEONEL AMILCAR SANCHEZ SANCHEZ', 'email' => null, 'username' => 'LSANCHEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
        User::create(['name' => 'JULIO CESAR GUERRA BELTRAN', 'email' => null, 'username' => 'JGUERRA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
        User::create(['name' => 'WILMER ALEXANDER MORALES CARTAGENA', 'email' => null, 'username' => 'WMORALES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'ROBER ELENILSON PEÑA VASQUEZ', 'email' => null, 'username' => 'RPEÑA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
        User::create(['name' => 'BRIAM URIEL TREJO GONZALEZ', 'email' => null, 'username' => 'BTREJO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
        User::create(['name' => 'DANIEL ALEXANDER PAREDES FLORES', 'email' => null, 'username' => 'DPAREDES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);


    }
}
