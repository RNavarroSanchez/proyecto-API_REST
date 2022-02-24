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
        DB::table('libro_usuario')->truncate();

        //crea 1000 usuarios
        $this->call(UsuarioSeeder::class); 
        $cantUsuarios = 1000;
        factory(Usuario::class,$cantUsuarios)->create();

        //crea 100 libros
        $this->call(LibroSeeder::class); 
        $cantLibros = 100;
        factory(Libro::class,$cantLibros)->create();

        //crea 30 prestamos
        $cantPrestamos=30;

        for ($i=0; $i<$cantPrestamos;$i++)
        {
            $usuario = Usuario::all()->random();
            $libro = Libro::all()->random()->id;
                // DB::table('prestamos')->insert(
                //     [
                //         'id_libro' => $libro,
                //         'id_usuario'=> $usuario,
                //         'created_at' =>now(),
                //     ]
                // );
                $usuario->libros()->attach($libro);
        }


        Schema::enableForeignKeyConstraints(); 
    }
    
}
