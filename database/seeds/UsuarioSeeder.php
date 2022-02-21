<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
       // DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        // DB::table('usuarios')->insert([
        //     'name' => 'Escuela Estech',
        //     'email' => 'info@escuelaestech.es',
        //     'password' => bcrypt('laravel'),
        // ]);

        //factory(\App\User::class)->times(50)->create(); 

    }
}