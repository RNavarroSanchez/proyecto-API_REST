<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $fillable = [
        'titulo', 'descripcion', 
    ];

    public function Usuarios(){
        return $this->belongsToMany(Usuario::class);
    }

}
