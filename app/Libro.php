<?php

namespace App;

use App\Transformers\LibroTransformer;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    public $transformer = LibroTransformer::class;
    
    protected $fillable = [
        'titulo', 'descripcion', 
    ];

    public function Usuarios(){
        return $this->belongsTo(Usuario::class);
    }

}
