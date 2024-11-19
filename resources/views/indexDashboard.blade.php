<x-app-layout>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-64 bg-white border-r shadow-sm p-4">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Dashboard</h2>
                
                <nav class="space-y-2">
                    <div class="flex items-center text-gray-600 hover:text-indigo-600 cursor-pointer p-2 rounded hover:bg-gray-50">
                        Resumen
                    </div>
                    <div class="flex items-center text-gray-600 hover:text-indigo-600 cursor-pointer p-2 rounded hover:bg-gray-50">
                        Procesos
                    </div>
                    <div class="flex items-center text-gray-600 hover:text-indigo-600 cursor-pointer p-2 rounded hover:bg-gray-50">
                        Actividades
                    </div>
                    <div class="flex items-center text-gray-600 hover:text-indigo-600 cursor-pointer p-2 rounded hover:bg-gray-50">
                        Notificaciones
                    </div>
                </nav>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Procesos</h3>
                @foreach ($procesos as $proceso)
                    <div 
                        wire:click="selectProceso({{ $proceso->id }})"
                        class="p-2 rounded cursor-pointer {{ $selectedProceso == $proceso->id ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-gray-50' }}"
                    >
                        {{ $proceso->nombre }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8 overflow-y-auto">
            <div class="max-w-4xl mx-auto">
                <!-- Selected Process Header -->
                @if ($selectedProceso)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                        <div class="p-6">
                            <h2 class="text-2xl font-semibold text-gray-800">
                                {{ $selectedProceso->nombre }}
                            </h2>
                            <div class="mt-2 flex space-x-2">
                                @foreach ($selectedProceso->actividades as $actividad)
                                    <button
                                        wire:click="selectActividad({{ $actividad->id }})"
                                        class="px-3 py-1 text-xs rounded-full {{ $selectedActividad == $actividad->id ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}"
                                    >
                                        {{ $actividad->nombre }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tasks Section -->
                @if ($selectedActividad)
                    <div class="space-y-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Tareas de {{ $selectedActividad->nombre }}
                            </h3>
                            <a 
                                href="{{ route('tareas.create', $selectedActividad) }}" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Nueva Tarea
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse ($selectedActividad->tareas as $tarea)
                                <div class="bg-white overflow-hidden shadow-sm rounded-lg {{ $tarea->estado === 'pendiente' ? '' : 'opacity-50' }}">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="text-lg font-semibold text-gray-800">
                                                {{ $tarea->identificacion }}
                                            </h4>
                                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $tarea->obligatorio ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $tarea->obligatorio ? 'Obligatoria' : 'Opcional' }}
                                            </span>
                                        </div>
                                        
                                        <div class="space-y-3 mb-4">
                                            <p class="text-sm text-gray-700">
                                                {{ $tarea->descripcion }}
                                            </p>
                                        </div>

                                        <div class="flex justify-between items-center text-sm text-gray-600 mb-4">
                                            <div class="flex items-center">
                                                Duración: {{ $tarea->tiempoDuracion }} min
                                            </div>
                                            <div class="flex items-center">
                                                Prioridad: 
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= ($tarea->nivelPrioridad ?? 0))
                                                        <span class="text-yellow-400">★</span>
                                                    @else
                                                        <span class="text-gray-300">★</span>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mt-4 flex justify-between items-center">
                                            <span class="px-3 py-1 text-xs rounded-full {{ $tarea->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $tarea->estado === 'pendiente' ? 'Pendiente' : 'Completada' }}
                                            </span>
                                            
                                            @if ($tarea->estado === 'pendiente')
                                                <form method="POST" action="{{ route('tareas.finish', $tarea->id) }}" class="inline">
                                                    @csrf
                                                    <button 
                                                        type="submit" 
                                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                    >
                                                        Terminar Tarea
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full">
                                    <div class="bg-gray-50 rounded-lg p-8 text-center">
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tareas</h3>
                                        <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva tarea para esta actividad.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

