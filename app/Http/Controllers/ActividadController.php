<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proceso;
use App\Models\Actividad;
use App\EstructurasDeDatos\ListaEnlazada\ListaEnlazada;
use Illuminate\Support\Facades\DB;

class ActividadController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index($proceso_id)
    {
        $actividades = $this->findListaActividades($proceso_id);
        //$actividades = Actividad::where('proceso_id', $proceso_id)->get();
        $proceso = Proceso::where('id', $proceso_id)->firstOrFail();
        return view('actividades.index', compact('actividades', 'proceso'));
    }

    public function buscar(Request $request, $proceso_id)
    {

        $validated = $request->validate([
            'buscar' => 'required|string',
        ]);

        $buscar = $request ->input('buscar');
        $actividades = new ListaEnlazada();

        $actividad = Actividad::where('proceso_id', $proceso_id)
            ->where('nombre', $buscar)
            ->first();

        $actividades->append($actividad);
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
        $validated = $request->validate([
            'posicionamiento' => 'required|string',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'obligatorio' => 'required|boolean',
        ]);

        $posicionamiento = $request->input('posicionamiento');
        $lista = $this->findListaActividades($proceso_id);
        $validated['obligatorio'] = (bool) $request->input('obligatorio');
        $validated['proceso_id'] = $proceso_id;
        $actividad = Actividad::create($validated);

        switch ($posicionamiento) {
            case 'final':
                $this->agregarAlFinal($proceso_id, $request, $lista, $actividad);
                break;

            case 'ultimo':
                $this->agregarDespuesDelUltimo($proceso_id, $request, $lista, $actividad);
                break;

            case 'despues':
                $this->agregarDespuesDe($proceso_id, $request, $lista, $actividad);
                break;

            default:
                // Manejar un valor no esperado
                return back()->withErrors(['posicionamiento' => 'Opción no válida']);
        }

        // Redirigir con un mensaje de éxito
        return redirect()->route('actividades.index', $proceso_id)
                        ->with('success', 'Actividad creada con éxito');
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
        $actividad = Actividad::where('id', $id)->firstOrFail();
        $proceso = Proceso::where('id', $actividad->proceso_id)->firstOrFail();
        return view('actividades.edit', compact('actividad', 'proceso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'obligatorio' => 'required|boolean',
        ]);

        $actividad = Actividad::where('id', $id)->firstOrFail();
        $actividad->update($request->all());
        $proceso_id = $actividad->proceso_id;

        return redirect()->route('actividades.index', $proceso_id)
                        ->with('success', 'Actividad creada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function findListaActividades($proceso_id)
    {
        $lista = new ListaEnlazada();
        $nodoActividad = DB::table('nodo_listas_actividades')->where('proceso_id', $proceso_id)->first();

        if(!is_null($nodoActividad)){

            $actividad = Actividad::where('id', $nodoActividad->actividad_id)->firstOrFail();

            while($actividad != null){
                $lista->append($actividad);
                if ($actividad->siguiente === null)
                    break;
                
                $actividad = Actividad::where('id', $actividad->siguiente)->firstOrFail();
            }
        }
        return $lista;

    }

    public function agregarAlFinal($proceso_id, $request, $lista, $actividad)
    {
        if($lista->isEmpty()){
            $lista->append($actividad);
            DB::table('nodo_listas_actividades')->insert([
                'proceso_id' => $proceso_id,
                'actividad_id' => $actividad->id,
                'created_at' => now(), // Si usas timestamps en tu tabla
                'updated_at' => now(),
            ]);
        }else{
            $ultimaActividadLista = $lista->getLast();
            $ultimaActividad = Actividad::where('nombre', $ultimaActividadLista->nombre)->firstOrFail();
            $ultimaActividad->siguiente = $actividad->id;
            $ultimaActividad->save();
        }
        
    }

    public function agregarDespuesDe($proceso_id, $request, $lista, $actividad)
    {
        if($lista->isEmpty()){
            $lista->append($actividad);
            DB::table('nodo_listas_actividades')->insert([
                'proceso_id' => $proceso_id,
                'actividad_id' => $actividad->id,
                'created_at' => now(), // Si usas timestamps en tu tabla
                'updated_at' => now(),
            ]);
        }else{
            $idActividadSeleccionada = $request->input('despues_de');
            $actividadSeleccionada = Actividad::where('id', $idActividadSeleccionada)->firstOrFail();

            $actividad->siguiente = $actividadSeleccionada->siguiente;
            $actividadSeleccionada->siguiente = $actividad->id;

            $actividad->save();
            $actividadSeleccionada->save();
            $lista->insertAfter($actividadSeleccionada, $actividad);
        }
    }

    public function agregarDespuesDelUltimo($proceso_id, $request, $lista, $actividad)
    {
        
        if($lista->isEmpty()){
            $lista->append($actividad);
            DB::table('nodo_listas_actividades')->insert([
                'proceso_id' => $proceso_id,
                'actividad_id' => $actividad->id,
                'created_at' => now(), // Si usas timestamps en tu tabla
                'updated_at' => now(),
            ]);
        }else{
            
            $ultimo = Actividad::where('proceso_id', $proceso_id)
            ->orderBy('id', 'desc') // Ordena por `id` descendente
            ->skip(1) // Salta el último registro
            ->first();

            $actividad->siguiente = $ultimo->siguiente;
            $ultimo->siguiente = $actividad->id;

            $actividad->save();
            $ultimo->save();
        }
    }
}
