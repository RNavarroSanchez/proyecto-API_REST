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
    public function index(Usuario $usuario)
    {

       $prestamos = $usuario-> with('libros')->whereHas('libros')->get();

        return $this->showAll($prestamos);
       
    }
}
