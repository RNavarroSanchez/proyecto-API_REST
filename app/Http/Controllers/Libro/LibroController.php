<?php

namespace App\Http\Controllers\Libro;

use App\Libro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\LibroTransformer;


class LibroController extends Controller
{
    public function __construct(){
        $this->middleware('transform.input:' . LibroTransformer::class)->only(['store', 'update']);
    }
     /**
   
    * @OA\Get(
    *    
    *     path="/api/libros",
    *     tags={"Libros"},
    *     summary="Mostrar todos los libros",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los libros."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function index()
    {
        return $this->showAll(Libro::all());
    }
   
     /**
    * @OA\Post(
    *     path="/api/libros",
    *       tags={"Libros"},
    *     summary="Añadir Usuario",
   *         @OA\Parameter(
     *         name="titulo",
     *         in="query",
     *         description="Titulo del libro nuevo",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
     *        @OA\Parameter(
     *         name="descripcion",
     *         in="query",
     *         description="Descripcion del titulo nuevo",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
    *     @OA\Response(
    *         response=201,
    *         description="Añadir Libro y te devuelve el libro insertado."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
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
   
    * @OA\Get(
    *    
    *     path="/api/usuarios/{idLibro}",
    *     tags={"Libros"},
    *     summary="Mostrar un libro por su id",
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
    *         description="Mostrar un libro por su id."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function show(Libro $libro)
    {
        return $this->showOne($libro,201);
    }

   /**
    * @OA\Patch(
    *     path="/api/usuarios/{idLibro}",
    *       tags={"Libros"},
    *     summary="Editar Libro",
    *          @OA\Parameter(
     *         name="idLibro",
     *         in="path",
     *         description="La id del libro",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
   *         @OA\Parameter(
     *         name="titulo",
     *         in="query",
     *         description="Nuevo titulo de libro",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
     *        @OA\Parameter(
     *         name="descripcion",
     *         in="query",
     *         description="Nueva descripcion del libro",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
    *     @OA\Response(
    *         response=201,
    *         description="Modificar un libro"
    *           ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
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
    * @OA\Delete(
    *     path="/api/usuarios/{idlibro} ",
    *       tags={"Libros"},
    *     summary="Eliminar Libro",
    *          @OA\Parameter(
     *         name="idLibro",
     *         in="path",
     *         description="La id del libro",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Eliminar un libro por su Id y te muestra el libro borrado"),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function destroy(Libro $libro)
    {
        $libro->delete();
        return $this->showOne($libro);
    }
}
