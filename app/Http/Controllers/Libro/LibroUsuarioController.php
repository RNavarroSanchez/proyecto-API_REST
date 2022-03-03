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
   
    * @OA\Get(
    *    
    *     path="/api/libros/{idLibro}/usuarios",
    *     tags={"Prestamos"},
    *     summary="Mostrar todos los usuarios que tienen determinado libro",
    *        @OA\Parameter(
     *         name="idLibro",
     *         in="path",
     *         description="La id del libro",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los usuarios con ese libro."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function index(Libro $libro)
    {
        $usuarios = $libro->usuarios;
        if ($usuarios->isEmpty()){
          return $this->errorResponse('Este libro no está prestado',404);
        }

        return $this->showAll($usuarios);
    }
}
