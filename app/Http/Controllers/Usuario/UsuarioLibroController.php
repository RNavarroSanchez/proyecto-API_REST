<?php

namespace App\Http\Controllers\Usuario;

use App\Libro;
use App\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsuarioLibroController extends Controller
{
       /**
   
    * @OA\Get(
    *    
    *     path="/api/usuarios/{idUsuario}/libros",
    *     tags={"Prestamos"},
    *     summary="Mostrar todos los libros que tiene determinado usuario",
    *        @OA\Parameter(
     *         name="idUsuario",
     *         in="path",
     *         description="La id del usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los libros de ese usuario."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function index(Usuario $usuario)
    {
            $libros = $usuario->libros;
         
            return $this->showAll($libros);
    }


        /**
   
    * @OA\Post(
    *    
    *     path="/api/usuarios/{idUsuario}/libros",
    *     tags={"Prestamos"},
    *     summary="Añadir un libro a determinado usuario",
    *        @OA\Parameter(
     *         name="idUsuario",
     *         in="path",
     *         description="La id del usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
       *         @OA\Parameter(
     *         name="libro_id",
     *         in="query",
     *         description="Id del libro prestado a usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los usuarios con libros."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
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
    * @OA\Delete(
    *    
    *     path="/api/usuarios/{idUsuario}/libros/{idLibro}",
    *     tags={"Prestamos"},
    *     summary="Borrar un libro a  un determinado usuario",
    *        @OA\Parameter(
     *         name="idUsuario",
     *         in="path",
     *         description="La id del usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *           @OA\Parameter(
     *         name="idLibro",
     *         in="path",
     *         description="La id del libro a borrar",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los usuarios con libros."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function destroy(Usuario $usuario, Libro $libro)
    {
        if( !$usuario->libros()->find($libro->id)){
            return $this->errorResponse('Este usuario no tiene prestado ese libro',404);
        }
        $usuario->libros()->detach($libro->id);
        
        $prestamos = $usuario-> with('libros')->wherehas('libros')->get();
        return $this->showAll($prestamos);
    }
}
