<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Proceso;
use App\Models\Tarea;

class Actividad extends Model
{
    protected $table = 'actividades';
    protected $fillable = ['nombre', 'descripcion', 'obligatoria', 'siguiente'];

    public function proceso()
    {
        return $this->belongsTo(Proceso::class);
    }

    public function tareas()
    {
        return $this->belongsToMany(Tarea::class);
    }
}
