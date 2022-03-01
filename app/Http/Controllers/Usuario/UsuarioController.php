<?php

namespace App\Http\Controllers\Usuario;

use App\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Transformers\UsuarioTransformer;

class UsuarioController extends Controller
{
    public function __construct(){
        $this->middleware('transform.input:' . UsuarioTransformer::class)->only(['store', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return $this->showAll(Usuario::all());
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
            'nombre' => 'required|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6|confirmed',
        ];
        $messages = [
            'required' => 'El campo :attribute es obligatorio.',
            'email.required' => 'El campo correo no tiene el formato adecuado.',
            'password' => 'La password es campo obligatorio',
        ];
        $validatedData = $request->validate($rules, $messages);
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = Usuario::create($validatedData);
        return $this->showOne($user,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
        return $this->showOne($usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return $this->showOne($usuario);
    }
}
