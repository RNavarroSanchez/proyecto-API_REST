<?php

namespace App\Transformers;

use App\Libro;
use League\Fractal\TransformerAbstract;

class LibroTransformer extends TransformerAbstract
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
    public function transform(Libro $libro)
    {
        return [
            'identificador' => (int)$libro->id,
            'titulo' => (string)$libro->titulo,
            'descripcion' => (string)$libro->descripcion,
            'fechaCreacion' => (string)$libro->created_at,
            'fechaActualizacion' => (string)$libro->update_at,
            // 'links'=> [
            //     'rel' => 'self',
            //     'href' => route('libros.index', $libro->id),
            // ],
            // [
            //     'rel' => 'self',
            //     'href' => route('libros.store', $libro->id),
            // ],
            [
                'rel' => 'self',
                'href' => route('libros.show', $libro->id),
            ],
            [
                'rel' => 'self',
                'metodo' => 'path',
                'href' => route('libros.update', $libro->id),
            ],
            [
                'rel' => 'self',
                'metodo' => 'delete',
                'href' => route('libros.destroy', $libro->id),
            ],
            
        ];
    }
    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'titulo'=> 'titulo',
            'descripcion' => 'descripcion',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identificador',
            'titulo'=> 'titulo',
            'descripcion' => 'descripcion',
            'created_at' => 'fechaCreacion', 
            'updated_at' => 'fechaActualizacion',
            
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
 }
