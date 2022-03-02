<?php

namespace App\Transformers;

use App\Usuario;
use League\Fractal\TransformerAbstract;

class UsuarioTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A  transformer.
     *
     * @return array
     */
    public function transform(Usuario $usuario)
    {
        return [
            'identificador' => (int)$usuario->id,
            'nombre' => (string)$usuario->nombre,
            'correo' => (string)$usuario->email,
            'fechaCreacion' => (string)$usuario->created_at,
            'fechaActualizacion' => (string)$usuario->update_at,
            'libros' => $usuario->libros,
            // 'links'=> [
            //     'rel' => 'self',
            //     'href' => route('usuarios.store', $usuario->id),
            // ],
            // [
            //     'rel' => 'self',
            //     'href' => route('usuarios.index', $usuario->id),
            // ],
            [
                'rel' => 'self',
                'metodo' => 'delete',
                'href' => route('usuarios.destroy', $usuario->id),
            ],
            [
                'rel' => 'self',
                'metodo' => 'path',
                'href' => route('usuarios.update', $usuario->id),
            ],
            [
                'rel' => 'self',
                'href' => route('usuarios.show', $usuario->id),
            ],
            
        ];
    }
    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'nombre'=> 'nombre',
            'correo' => 'email',
            'contraseña_confirmation' => 'password_confirmation',
            'contraseña' => 'password',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'libros' => 'libros',
            
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identificador',
            'email' => 'correo',
            'nombre'=> 'nombre',
            'password' => 'contraseña',
            'password_confirmation' => 'contraseña_confirmation',
            'created_at' => 'fechaCreacion', 
            'updated_at' => 'fechaActualizacion',
            'libros' => 'libros',
            
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
