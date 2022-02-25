<?php

namespace App\Http\Controllers\Prestamo;

use App\Libro;
use App\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PrestamoController extends Controller
{
    // public function __construct(){
    //     $this->middleware('transform.input'.UsuarioTransformer::class)->only(['store','update']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Usuario $usuario, Libro $libro)
    {
        $prestamos = $usuario->libro();
        
        return $this->showAll($prestamos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
        //  if( !$usuario->libros()->find($usuario->id)){
        //     return $this->errorResponse('Este usuario no tiene prestado ese libro',404);
        // }
        // $prestamos = $usuario->libros;
        // return $this->show($prestamos);
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
        return $this->showAll($usuario->libros);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario, Libro $libro)
    {
        if( !$usuario->libros()->find($libro->id)){
            return $this->errorResponse('Este usuario no tiene prestado ese libro',404);
        }
        $usuario->libros()->detach($libro->id);
        return $this->showAll($usuario->libros);

    }
}
