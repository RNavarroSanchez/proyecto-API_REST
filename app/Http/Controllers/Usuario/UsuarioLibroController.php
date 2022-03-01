<?php

namespace App\Http\Controllers\Usuario;

use App\Libro;
use App\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsuarioLibroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Usuario $usuario)
    {
            $libros = $usuario->libros;
         
            return $this->showAll($libros);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Usuario $usuario)
    {
        $rules = [
            'libro_id' => 'required|integer',
        ];
        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'integer' => 'EL campo :attribute debe de ser un numero entero'
        ];

       
        $validatedData = $request->validate($rules, $messages);

            $usuario->libros()->attach($validatedData);   
        
       return $this->showAll($usuario->libros);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario, Libro $libro)
    
    {
        $rules = [
            'libro_id' => 'required|integer',
        ];
        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'integer' => 'EL campo :attribute debe de ser un numero entero'
        ];
        $validatedData = $request->validate($rules, $messages);

        $usuario->libros()->syncWithoutDetaching($validatedData);
        return $this->showAll($usuario->libros());
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

        $listaUsuarios = $libro-> with('usuarios')->whereHas('libros')->get();

        return $this->showAll($listaUsuarios);
    }
}
