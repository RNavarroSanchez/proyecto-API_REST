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
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Usuario $usuario)
    {
        return [
            'identificador' => (int)$usuario->id,
            'fechaCreacion' => (string)$usuario->created_at,
            'fechaActualizacion' => (string)$usuario->update_at,
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
                'methodo' => 'delete',
                'href' => route('usuarios.destroy', $usuario->id),
            ],
            [
                'rel' => 'self',
                'methodo' => 'path',
                'href' => route('usuarios.update', $usuario->id),
            ],
            [
                'rel' => 'self',
                'href' => route('usuarios.show', $usuario->id),
            ],
            
        ];
    }
    public static function originalAttribute($index, $attributes){
        $attributes = [
            'identificador' => 'id',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identificador',
            'created_at' => 'fechaCreacion', 
            'updated_at' => 'fechaActualizacion',
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
