<?php

namespace App\Http\Controllers\Libro;

use App\Libro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showAll(Libro::all());
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'titulo' => 'required|max:255|unique:titulo',
            'descripcion' => 'required|max:1000',
            
        ];
        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'unique' => 'El libro ya está en la base de datos'
        ];
        $validatedData = $request->validate($rules, $messages);
        $libro = Libro::create($validatedData);
        return $this->showOne($libro,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function show(Libro $libro)
    {
        return $this->showOne($libro,201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Libro $libro)
    {
        $rules = [
            'titulo' => 'required|max:255|unique:libros',
            'descripcion' => 'required|max:1000',
        ];
        $messages = [
            'titulo.unique' => 'El titulo del libro ya existe',
            'required' => 'El :attribute es requerido'
        ];
        
        $validatedData = $request->validate($rules,$messages);

        $libro->fill($validatedData);

        if(!$libro->isDirty()){
            return response()->json(['error'=>['code' => 422, 'message' => 'Por favor escribe un valor diferente' ]], 422);
        }
        $libro->save();
        return $this->showOne($libro);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Libro  $libro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Libro $libro)
    {
        $libro->delete();
        return $this->showOne($libro);
    }
}
