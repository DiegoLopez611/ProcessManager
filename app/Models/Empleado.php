<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notificacion;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $fillable = ['usuario', 'contrasenia', 'identificacion', 'nombre', 'correo_electronico'];

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }
}
