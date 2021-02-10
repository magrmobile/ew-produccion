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
        
        User::create(['name' => 'SANTOS EFRAIN CHAVARRIA LOVO', 'email' => 'schavarria@enerwire.com', 'username' => 'SCHAVARRIA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'DAVID ORLANDO VASQUEZ PAREDES', 'email' => 'dvasquez@enerwire.com', 'username' => 'DVASQUEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'VICTOR MANUEL GARCIA MORALES', 'email' => 'vgarcia@enerwire.com', 'username' => 'VGARCIA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'JOSE ALFREDO MENDOZA', 'email' => 'jmendoza@enerwire.com', 'username' => 'JMENDOZA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'MOISES PAREDES FLORES', 'email' => 'mparedes@enerwire.com', 'username' => 'MPAREDES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'DAVID DE JESUS PORTILLO MOLINA', 'email' => 'dportillo@enerwire.com', 'username' => 'DPORTILLO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'RONY RENZO GONZALEZ DIAZ', 'email' => 'rgonzalez@enerwire.com', 'username' => 'RGONZALEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'JORGE DARIO RODRIGUEZ MEJIA', 'email' => 'jrodriguez@enerwire.com', 'username' => 'JRODRIGUEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'EDWIN ALEXANDER SANCHEZ SANCHEZ', 'email' => 'esanchez@enerwire.com', 'username' => 'ESANCHEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'LUDWIN ERNESTO MARTINEZ VIGIL', 'email' => 'lmartinez@enerwire.com', 'username' => 'LMARTINEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'GERBER ANTONIO SOLA LOPEZ', 'email' => 'gsola@enerwire.com', 'username' => 'GSOLA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'JULIO ALBERTO HERNANDEZ BERRIOS', 'email' => 'jhernandez@enerwire.com', 'username' => 'JHERNANDEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'JULIO EDUARDO MELENDEZ ECHEVERRIA', 'email' => 'jmelendez@enerwire.com', 'username' => 'JMELENDEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'SELVYN PATRICIO NERIO SANCHEZ', 'email' => 'snerio@enerwire.com', 'username' => 'SNERIO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'EFRAIN ALEXANDER CERRATO ESCOLERO', 'email' => 'ecerrato@enerwire.com', 'username' => 'ECERRATO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'OSCAR ARMANDO GONZALEZ CRUZ', 'email' => 'ogonzalez@enerwire.com', 'username' => 'OGONZALEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'SAUL ERNESTO VARGAS ARRIAZA', 'email' => 'svargas@enerwire.com', 'username' => 'SVARGAS', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'ALVARO ALEJANDRO CORTEZ ORTEGA', 'email' => 'acortez@enerwire.com', 'username' => 'ACORTEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'FRANCISCO JAVIER VASQUEZ PEREZ', 'email' => 'fvasquez@enerwire.com', 'username' => 'FVASQUEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'EDGARDO RAFAEL SANCHEZ MEJIA', 'email' => 'esanchezm@enerwire.com', 'username' => 'ESANCHEZM', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
User::create(['name' => 'JONNY NOE VELASQUEZ QUIÑONEZ', 'email' => 'jvelasquez@enerwire.com', 'username' => 'JVELASQUEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'IRVING ALEXANDER ORELLANA RAMIREZ', 'email' => 'iorellana@enerwire.com', 'username' => 'IORELLANA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'CARLOS HERIBERTO BASILIO', 'email' => 'cbasilio@enerwire.com', 'username' => 'CBASILIO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'EDGAR JAVIER FLORES PEREZ', 'email' => 'eflores@enerwire.com', 'username' => 'EFLORES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'RONALD ERNESTO DUQUE ALAS', 'email' => 'rduque@enerwire.com', 'username' => 'RDUQUE', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'RAUL ALEXANDER ESCOBAR CAMPOS', 'email' => 'rescobar@enerwire.com', 'username' => 'RESCOBAR', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
User::create(['name' => 'EDWIN ALEXANDER MARTINEZ RAMOS', 'email' => 'emartinez@enerwire.com', 'username' => 'EMARTINEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'SAMUEL OSEAS PAIZ CORTEZ', 'email' => 'spaiz@enerwire.com', 'username' => 'SPAIZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
User::create(['name' => 'DENYS JOSE MEJIA MARTINEZ', 'email' => 'dmejia@enerwire.com', 'username' => 'DMEJIA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
User::create(['name' => 'ISAI OTONIEL MARTEL FUENTES', 'email' => 'imartel@enerwire.com', 'username' => 'IMARTEL', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'CRISTIAN MAURICIO PEREZ HERNANDEZ', 'email' => 'cperez@enerwire.com', 'username' => 'CPEREZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'JONATHAN NEHEMIAS SOSA HERNANDEZ', 'email' => 'jsosa@enerwire.com', 'username' => 'JSOSA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'MIGUEL ELEAZAR HERNANDEZ BELTRAN', 'email' => 'mhernandez@enerwire.com', 'username' => 'MHERNANDEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'CARLOS DAVID GONZALEZ ARTIGA', 'email' => 'cgonzalez@enerwire.com', 'username' => 'CGONZALEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'LUIS MIGUEL REVELO FLORES', 'email' => 'lrevelo@enerwire.com', 'username' => 'LREVELO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'EVER NAHUN HERNANDEZ BELTRAN', 'email' => 'ehernandez@enerwire.com', 'username' => 'EHERNANDEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
User::create(['name' => 'NILSON BENJAMIN MONTERROSA MEJIA', 'email' => 'nmonterrosa@enerwire.com', 'username' => 'NMONTERROSA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'JAIRO ENRIQUE LUARCA BONILLA', 'email' => 'jluarca@enerwire.com', 'username' => 'JLUARCA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'JUAN CARLOS FUENTES LOPEZ', 'email' => 'jfuentes@enerwire.com', 'username' => 'JFUENTES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
User::create(['name' => 'HERBERT ELISEO ALVARENGA PACHECO', 'email' => 'halvarenga@enerwire.com', 'username' => 'HALVARENGA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'JOSE ANTONIO BACILIO ANDRES', 'email' => 'jbacilio@enerwire.com', 'username' => 'JBACILIO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'MANUEL ALBERTO PONCE OSORIO', 'email' => 'mponce@enerwire.com', 'username' => 'MPONCE', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
User::create(['name' => 'FREDI BALTAZAR BARAHONA CHAVEZ', 'email' => 'fbarahona@enerwire.com', 'username' => 'FBARAHONA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'FERMIN DE JESUS ABARCA CRUZ', 'email' => 'fabarca@enerwire.com', 'username' => 'FABARCA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
User::create(['name' => 'OSCAR ALCIDES PONCE OSORIO', 'email' => 'oponce@enerwire.com', 'username' => 'OPONCE', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'CARLOS REYES CHACON', 'email' => 'creyes@enerwire.com', 'username' => 'CREYES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'JOSE AUDELINO TRUJILLO ESCOBAR', 'email' => 'jtrujillo@enerwire.com', 'username' => 'JTRUJILLO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'KEVIN ALEXANDER DELGADO ROGEL', 'email' => 'kdelgado@enerwire.com', 'username' => 'KDELGADO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'ABRAHAM ISRAEL RAMOS SOLA', 'email' => 'aramos@enerwire.com', 'username' => 'ARAMOS', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'HECTOR EMERSON MEDRANO GIRON', 'email' => 'hmedrano@enerwire.com', 'username' => 'HMEDRANO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'KEVIN OSWALDO CERON RAMOS', 'email' => 'kceron@enerwire.com', 'username' => 'KCERON', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'MOISES ELIAS HERNANDEZ LOPEZ', 'email' => 'mhernandezl@enerwire.com', 'username' => 'MHERNANDEZL', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'LEONEL AMILCAR SANCHEZ SANCHEZ', 'email' => 'lsanchez@enerwire.com', 'username' => 'LSANCHEZ', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 2]);
User::create(['name' => 'JULIO CESAR GUERRA BELTRAN', 'email' => 'jguerra@enerwire.com', 'username' => 'JGUERRA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 4]);
User::create(['name' => 'WILMER ALEXANDER MORALES CARTAGENA', 'email' => 'wmorales@enerwire.com', 'username' => 'WMORALES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'ROBER ELENILSON PEÑA VASQUEZ', 'email' => 'rpena@enerwire.com', 'username' => 'RPENA', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 3]);
User::create(['name' => 'BRIAM URIEL TREJO GONZALEZ', 'email' => 'btrejo@enerwire.com', 'username' => 'BTREJO', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);
User::create(['name' => 'DANIEL ALEXANDER PAREDES FLORES', 'email' => 'dparedes@enerwire.com', 'username' => 'DPAREDES', 'email_verified_at' => now(), 'password' => bcrypt('secret'), 'active_user' => 'enabled', 'role' => 'operator', 'process_id' => 1]);


    }
}
