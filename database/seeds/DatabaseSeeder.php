<?php

use App\Libro;
use App\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Libro::truncate();
        Usuario::truncate();
        DB::table('prestamos')->truncate();

        //crea 1000 usuarios
        $this->call(UsuarioSeeder::class); 
        $cantUsuarios = 1000;
        factory(Usuario::class,$cantUsuarios)->create();

        //crea 100 libros
        $this->call(LibroSeeder::class); 
        $cantLibros = 100;
        factory(Libro::class,$cantLibros)->create();

        $cantPrestamos=30;

        for ($i=0; $i<$cantPrestamos;$i++)
        {
            $usuario = Usuario::all()->random()->id;
            $libro = Libro::all()->random()->id;
                DB::table('prestamos')->insert(
                    [
                        'id_libro' => $libro,
                        'id_usuario'=> $usuario,
                        'created_at' =>now(),
                    ]
                );
        }


        Schema::enableForeignKeyConstraints(); 
    }
    
}
