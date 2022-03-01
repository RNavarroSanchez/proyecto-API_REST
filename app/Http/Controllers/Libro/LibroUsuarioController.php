<?php

namespace App\Http\Controllers\Libro;

use App\Libro;
use App\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LibroUsuarioController extends Controller
{
  // public function __construct(){
    //     $this->middleware('transform.input'.UsuarioTransformer::class)->only(['store','update']);
    // }

    /**
     * Display a listing of the resource.
     *@param  \App\Libros  $libro
     * @return \Illuminate\Http\Response
     */
    public function index(Libro $libro)
    {
        $usuarios = $libro->usuarios;
        return $this->showAll($usuarios);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Usuario $usuario, Libro $libro )
    {
        $usuario->libros()->syncWithoutDetaching($libro->id);
        return $this->showAll($usuario);
    }

   
}
