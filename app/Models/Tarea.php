<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Actividad;
use App\Models\Notificacion;

class Tarea extends Model
{
    protected $table = 'tareas';
    protected $fillable = ['identificacion', 'descripcion', 'tiempoDuracion', 'obligatoria', 'nivelPrioridad'];

    public function notificacion()
    {
        return $this->hasOne(Notificacion::class);
    }
    

    public function actividades()
    {
        return $this->belongsToMany(Actividad::class);
    }
}
