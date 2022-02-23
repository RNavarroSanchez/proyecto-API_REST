<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = "libros";


    protected $fillable = [
        'titulo', 'descripcion', 
    ];

    public function Usuarios(){
        return $this->belongsTo(Usuario::class);
    }

}
