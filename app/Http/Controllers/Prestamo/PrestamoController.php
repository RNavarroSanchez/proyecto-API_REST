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
   
    * @OA\Get(
    *    
    *     path="/api/prestamos",
    *     tags={"Prestamos"},
    *     summary="Mostrar todos los prestamos",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los prestamos."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function index(Usuario $usuario)
    {

       $prestamos = $usuario-> with('libros')->wherehas('libros')->get();

        return $this->showAll($prestamos);
       
    }
}
