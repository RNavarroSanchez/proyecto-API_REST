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
}
