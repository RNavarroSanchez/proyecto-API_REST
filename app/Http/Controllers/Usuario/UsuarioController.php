<?php

namespace App\Http\Controllers\Usuario;

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Transformers\UsuarioTransformer;

/**
* @OA\Info(title="API Biblioteca", version="1.0")
*
* @OA\Server(url="http://localhost:8000")
*/
class UsuarioController extends Controller
{
    public function __construct(){
        $this->middleware('transform.input:' . UsuarioTransformer::class)->only(['store', 'update']);
    }
   /**
   
    * @OA\Get(
    *    
    *     path="/api/usuarios",
    *     tags={"Usuarios"},
    *     summary="Mostrar todos los usuarios",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los usuarios."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function index()
    {
        
        return $this->showAll(Usuario::all());
    }

   /**
    * @OA\Post(
    *     path="/api/usuarios",
    *       tags={"Usuarios"},
    *     summary="Añadir Usuario",
   *         @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         description="Nombre de usuario nuevo",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
     *        @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="correo de usuario nuevo",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
     * @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Contraseña de usuario nuevo",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         description="Confirmacion de Contraseña de usuario nuevo",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
    *     @OA\Response(
    *         response=201,
    *         description="Añadir Usuario y te devuelve el usuario insertado."
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
            'nombre' => 'required|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6|confirmed',
        ];
        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'email.required' => 'El campo correo no tiene el formato adecuado.',
            'password' => 'El password es campo obligatorio',
        ];
        $validatedData = $request->validate($rules, $messages);
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = Usuario::create($validatedData);
        return $this->showOne($user,201);
    }

  /**
   
    * @OA\Get(
    *    
    *     path="/api/usuarios/{idusuario}",
    *     tags={"Usuarios"},
    *     summary="Mostrar un usuario por su id",
    *        @OA\Parameter(
     *         name="idusuario",
     *         in="path",
     *         description="La id del usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar un usuario por su id."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function show(Usuario $usuario)
    {
        return $this->showOne($usuario);
    }

    /**
    * @OA\Patch(
    *     path="/api/usuarios/{idusuario}",
    *       tags={"Usuarios"},
    *     summary="Editar Usuario",
    *          @OA\Parameter(
     *         name="idusuario",
     *         in="path",
     *         description="La id del usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
   *         @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         description="Nuevo nombre de usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
     *        @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Nuevo email de usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
    *     @OA\Response(
    *         response=201,
    *         description="Modificar un usuario"
    *           ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function update(Request $request, Usuario $usuario)
    {
        $rules = [
            'nombre' => 'min:5|max:255',
            'email' => ['email', Rule::unique('usuarios')->ignore($usuario->id)],
            'password' => 'min:6', // si no hacemos ninguna validacion para este, debemos ponerle '' aunque sea para tenerlo disponible en la vista
        ];
        $validatedData = $request->validate($rules);

        if ($request->filled('password')){
            $validatedData['password'] = bcrypt($request->input('password'));
        }

        $usuario->fill($validatedData);

        if(!$usuario->isDirty()){
            return response()->json(['error'=>['code' => 422, 'message' => 'Por favor indica otros campos' ]], 422);
        }
        $usuario->save();
        return $this->showOne($usuario);
    }

     /**
    * @OA\Delete(
    *     path="/api/usuarios/{idusuario} ",
    *       tags={"Usuarios"},
    *     summary="Eliminar Usuario",
    *          @OA\Parameter(
     *         name="idusuario",
     *         in="path",
     *         description="La id del usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Eliminar un usuario por su Id y te muestra el usuario borrado"),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return $this->showOne($usuario);
    }
}
