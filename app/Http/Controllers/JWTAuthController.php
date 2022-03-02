<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Usuario;

class JWTAuthController extends Controller
{
/**
* Create a new AuthController instance.
*
* @return void
*/
public function __construct()
{
$this->middleware('auth:api', ['except' => ['login', 'register']]);
}

 /**
    * @OA\Post(
    *     path="/api/auth/register",
    *       tags={"UsuariosJWT"},
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
     *         description="Email de usuario nuevo",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
     * @OA\Parameter(
     *         name="contraseña",
     *         in="query",
     *         description="Contraseña de usuario nuevo",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * @OA\Parameter(
     *         name="contraseña_confirmation",
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

public function register(Request $request)
{
$rules = [
'nombre' => 'required|between:2,100',
'email' => 'required|email|unique:usuarios,email',
'password' => 'required|min:6|string|confirmed',
];
$messages = [
'required' => 'El campo :attribute es obligatorio.',
'email.email' => 'El campo correo no tiene el formato adecuado.',
'password' => 'La password es campo obligatorio',
];
$validatedData = $request->validate($rules, $messages);
$validatedData['password'] = bcrypt($validatedData['password']);
$user = Usuario::create($validatedData);
return $this->showOne($user,201);
}

   /**
    * @OA\Post(
    *     path="/api/auth/login",
    *       tags={"UsuariosJWT"},
    *     summary="Logear Usuario",
     *        @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Email de usuario ",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
     * @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Contraseña de usuario",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Devuelve Access Token"
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function login(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

     /**
    * @OA\Get(
    *     path="/api/auth/profile",
    *       tags={"UsuariosJWT"},
    *     summary="Ver Perfil de Usuario por su token",
     *        @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         description="Introducir el token precedido de Bearer ",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
  
    *     @OA\Response(
    *         response=201,
    *         description="Ver Perfil por token"
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function profile()
    {
        return response()->json(auth()->user());
    }

     /**
    * @OA\Post(
    *     path="/api/auth/logout",
    *       tags={"UsuariosJWT"},
    *     summary="Logout del Usuario",
    *     @OA\Response(
    *         response=201,
    *         description="Se sale de la sesion"
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

   /**
    * @OA\Post(
    *     path="/api/auth/refresh",
    *       tags={"UsuariosJWT"},
    *     summary="Renovar token de Usuario",
     *        @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         description="Introducir el token precedido de Bearer ",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )),
  
    *     @OA\Response(
    *         response=201,
    *         description="Ver token nuevo"
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}