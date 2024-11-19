<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Actividad;

class Proceso extends Model
{
    protected $table = 'procesos';
    protected $fillable = ['identificacion', 'nombre', 'descripcion'];

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    protected function estado(): Attribute
    {
        return Attribute::make(
            get: fn () => 'activo'
        );
    }
}
