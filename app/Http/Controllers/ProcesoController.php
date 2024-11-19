<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\Actividad;
use App\Models\Tarea;

class ProcesoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $procesos = Proceso::all();
        
        foreach ($procesos as $proceso) {
            $duracion = 0; 
            $actividades = Actividad::where('proceso_id', $proceso->id)->get();
            
            foreach($actividades as $actividad){
                $tareas = Tarea::where('actividad_id', $actividad->id)->get();

                foreach($tareas as $tarea){
                    $duracion+= $tarea->tiempoDuracion;
                }

            }
            $proceso->duracion = $duracion;
        }

        return view('procesos.index', compact('procesos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('procesos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'identificacion'=>'required|string',
            'nombre'=>'required|string',
            'descripcion'=>'required|string',  
        ]);

        $proceso = new Proceso();
        $proceso->fill($request->all()); 
        $proceso->save();

        return redirect('procesos');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $proceso = Proceso::where('id', $id)->firstOrFail();
        return view('procesos.edit', compact('proceso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'identificacion'=>'required|string',
            'nombre'=>'required|string',
            'descripcion'=>'required|string',  
        ]);

        $proceso = Proceso::where('id', $id)->firstOrFail();
        $proceso->update($request->all());

        return redirect('procesos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
