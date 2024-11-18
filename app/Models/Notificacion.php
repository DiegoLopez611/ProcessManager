<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empleado;
use App\Models\Tarea;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    protected $fillable = ['mensaje', 'fecha'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
}
