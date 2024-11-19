<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tarea;
use App\Models\Actividad;
use App\EstructurasDeDatos\Pila\Pila;
use App\EstructurasDeDatos\ListaEnlazada\ListaEnlazada;
use Exception;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($actividad_id)
    {
        //$actividad_tarea = DB::table('actividad_tarea')->where('actividad_id', $actividad_id)->get();
        // $tarea_ids = $actividad_tarea->pluck('tarea_id');
        //$tareas = Tarea::whereIn('id', $tarea_ids)->get();

        $tareas = $this->findListaTareas($actividad_id);
        $tareaPrincipal = $tareas->popFirst();

        $actividad = Actividad::where('id', $actividad_id)->firstOrFail();


        return view('tareas.index', compact('tareas', 'actividad', 'tareaPrincipal')) ->with('isSearch', false);
    }

    public function buscar(Request $request, $actividad_id)
    {
        $validated = $request->validate([
            'buscar' => 'required|string',
        ]);

        $buscar = $request ->input('buscar');

        $tareaPrincipal = Tarea::where('actividad_id', $actividad_id)
            ->where('identificacion', $buscar)
            ->first();

        $tareas = $this->findListaTareas($actividad_id);
        $actividad = Actividad::where('id', $actividad_id)->firstOrFail();

        return view('tareas.index', compact('tareas', 'actividad', 'tareaPrincipal'))->with('isSearch', true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($actividad_id)
    {
        $actividad = Actividad::where('id', $actividad_id)->firstOrFail();
        $tareas = Tarea::where('actividad_id', $actividad_id)->get();
        return view('tareas.create', compact('actividad', 'tareas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $actividad_id)
    {
       
        $validated = $request->validate([
            'identificacion' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'tiempoDuracion' => 'required|integer|min:1|max:1440', // máximo un día
            'obligatoria' => 'boolean',
            'nivelPrioridad' => 'required|integer|min:1|max:5',
            'posicionamiento' => 'required|in:final,despues',
        ]);
        $posicionamiento = $request->input('posicionamiento');
        $pila = $this->findPilaTarea($actividad_id);
        $validated['obligatorio'] = (bool) $request->input('obligatorio');
        $validated['actividad_id'] = $actividad_id;
        $tarea = Tarea::create($validated);

        DB::table('actividad_tarea')->insert([
            'actividad_id' => $actividad_id,
            'tarea_id' => $tarea->id,
            'created_at' => now(), // Si usas timestamps
            'updated_at' => now(),
        ]);

        switch ($posicionamiento) {
            case 'final':
                $this->agregarAlFinal($request, $pila, $tarea, $actividad_id);
                break;

            case 'despues':
                $this->agregarDespuesDe($request, $pila, $tarea, $actividad_id);
                break;

            default:
                // Manejar un valor no esperado
                return back()->withErrors(['posicionamiento' => 'Opción no válida']);
        }

        // Redirigir con un mensaje de éxito
        return redirect()->route('tareas.index', $actividad_id)
                        ->with('success', 'Actividad creada con éxito');
    }

    public function finish($tarea_id)
    {
        $tarea = Tarea::find($tarea_id);

        if (!$tarea) {
            return redirect()->back()->withErrors('La tarea no existe.');
        }

        $actividad_id = $tarea->actividad_id;

        DB::transaction(function () use ($tarea) {
            // Obtener la cima de la pila para la actividad asociada
            $cima_pila = DB::table('cima_pilas_tareas')
                ->where('actividad_id', $tarea->actividad_id)
                ->first();
    
            if (!$cima_pila) {
                throw new \Exception('No se encontró la cima de la pila para esta actividad.');
            }
    
            // Actualizar la cima de la pila al siguiente elemento
            DB::table('cima_pilas_tareas')
                ->where('actividad_id', $tarea->actividad_id)
                ->update(['tarea_id' => $tarea->siguiente]);
    
            // Eliminar la relación de la tarea con la actividad
            DB::table('actividad_tarea')->where('tarea_id', $tarea->id)->delete();
    
            // Eliminar la tarea
            $tarea->delete();
        });

        return redirect()
        ->route('tareas.index', $actividad_id)
        ->with('success', 'Tarea marcada como completada y eliminada.');
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

    public function findPilaTarea($actividad_id)
    {
        $pila = new Pila();
        $cimaTareas = DB::table('cima_pilas_tareas')->where('actividad_id', $actividad_id)->first();

        if(!is_null($cimaTareas))
        {
            $tarea = Tarea::where('id', $cimaTareas->tarea_id)->firstOrFail();
            if ($tarea) {
                $this->llenarPila($pila, $tarea);
            }
        }
        return $pila;
    }

    public function llenarPila($pila, $tarea)
    {
        if($tarea === null)
        {
            return;
        }
        $current = Tarea::find($tarea->siguiente);
        if ($current) {
            $this->llenarPila($pila, $current);
        }
        $pila->push($tarea);
    }

    public function agregarAlFinal($request, $pila, $tarea, $actividad_id)
    {
        if($pila->isEmpty()){
            $pila->push($tarea);
            DB::table('cima_pilas_tareas')->insert([
                'actividad_id' => $actividad_id,
                'tarea_id' => $tarea->id,
                'created_at' => now(), // Si usas timestamps en tu tabla
                'updated_at' => now(),
            ]);
        }else{
            $this->addToBottom($pila, $tarea);
        }
    }

    public function agregarDespuesDe($request, $pila, $tarea, $actividad_id)
    {
        if($pila->isEmpty()){
            $pila->push($tarea);
            DB::table('cima_pilas_tareas')->insert([
                'actividad_id' => $actividad_id,
                'tarea_id' => $tarea->id,
                'created_at' => now(), // Si usas timestamps en tu tabla
                'updated_at' => now(),
            ]);
        }else{
            $idTareaSeleccionada = $request->input('despues_de');
            $this->addAfterTask($pila, $tarea, $idTareaSeleccionada);
        } 
    }

    function addToBottom($pila, $tarea) {
        $auxiliar = new Pila();
        $lastElementId = null;
    
        while (!$pila->isEmpty()) {
            $lastElement = $pila->pop();
            $auxiliar->push($lastElement);
            $lastElementId = $lastElement->id; 
        }
    
        if ($lastElementId !== null) {
            $lastElement = Tarea::find($lastElementId);
            if ($lastElement) {
                $lastElement->siguiente = $tarea->id;
                $lastElement->save(); 
            }
        }
       
        $pila->push($tarea);
    
        while (!$auxiliar->isEmpty()) {
            $pila->push($auxiliar->pop());
        }
    }

    function addAfterTask($pila, $newTask, $previousTaskId) {
        $auxiliar = new Pila();
        $found = false;
    
        // Vaciar la pila en la pila auxiliar hasta encontrar la tarea específica
        while (!$pila->isEmpty()) {
            $currentTask = $pila->pop();
            $auxiliar->push($currentTask);
    
            // Si encontramos la tarea que precede a la nueva, marcamos que debemos insertar
            if ($currentTask->id == $previousTaskId) {
                $found = true;
    
                // Actualizar la columna `siguiente` del registro en la base de datos
                $previousTask = Tarea::find($previousTaskId);
                if ($previousTask) {
                    $newTask->siguiente = $previousTask->siguiente;
                    $previousTask->siguiente = $newTask->id;
                    $previousTask->save();
                    $newTask->save();
                }
    
                // Insertar la nueva tarea después de la encontrada
                $auxiliar->push($newTask);
            }
        }
    
        // Verificar si se encontró el ID proporcionado
        if (!$found) {
            throw new Exception("La tarea con ID $previousTaskId no se encontró en la pila.");
        }
    
        // Restaurar la pila desde la auxiliar
        while (!$auxiliar->isEmpty()) {
            $pila->push($auxiliar->pop());
        }
    }

    public function findListaTareas($actividad_id)
    {
        $lista = new ListaEnlazada();
        $nodoTarea = DB::table('cima_pilas_tareas')->where('actividad_id', $actividad_id)->first();

        if(!is_null($nodoTarea)){

            $tarea = Tarea::where('id', $nodoTarea->tarea_id)->firstOrFail();

            while($tarea != null){
                $lista->append($tarea);
                if ($tarea->siguiente === null)
                    break;
                
                $tarea = Tarea::where('id', $tarea->siguiente)->firstOrFail();
            }
        }
        return $lista;
    }
}
