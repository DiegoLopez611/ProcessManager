<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\Actividad;

class ActividadController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index($proceso_id)
    {
        $actividades = Actividad::where('proceso_id', $proceso_id)->get();
        $proceso = Proceso::where('id', $proceso_id)->firstOrFail();
        return view('actividades.index', compact('actividades', 'proceso'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create($proceso_id)
    {
        $proceso = Proceso::where('id', $proceso_id)->firstOrFail();
        $actividades = Actividad::where('proceso_id', $proceso_id)->get();
        return view('actividades.create', compact('proceso','actividades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $proceso_id)
    {
        
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
