<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Actividad;

class Proceso extends Model
{
    protected $table = 'procesos';
    protected $fillable = ['identificacion', 'nombre'];

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }
}
