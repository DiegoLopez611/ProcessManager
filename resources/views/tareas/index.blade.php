<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información de la Actividad -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">
                                {{ $actividad->nombre }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 mb-2">
                                {{ $actividad->descripcion }}
                            </p>
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $actividad->es_obligatoria ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $actividad->obligatorio ? 'Obligatoria' : 'Opcional' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header de Tareas -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">
                    Tareas de la Actividad
                </h3>
                <form action="{{ route('tareas.buscar', $actividad->id) }}" method="GET" class="flex items-center">
                    <input 
                        type="text" 
                        name="buscar" 
                        placeholder="Buscar Tarea" 
                        value="{{ request('buscar') }}"
                        class="mr-2 px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Buscar
                    </button>
                </form>
                <a href="{{ route('tareas.create', $actividad) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nueva Tarea
                </a>
            </div>

            <!-- Tarea Principal Destacada -->
            @if ($tareaPrincipal)
            <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xl font-semibold text-gray-800">
                            {{ $tareaPrincipal->identificacion }}
                        </h4>
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $tareaPrincipal->obligatorio ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $tareaPrincipal->obligatorio ? 'Obligatoria' : 'Opcional' }}
                        </span>
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <p class="text-sm text-gray-700">
                            {{ $tareaPrincipal->descripcion }}
                        </p>
                    </div>

                    <div class="flex justify-between items-center text-sm text-gray-600 mb-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Duración: {{ $tareaPrincipal->tiempoDuracion }} min
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Prioridad: 
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= ($tareaPrincipal->nivelPrioridad ?? 0))
                                    <span class="text-yellow-400">★</span>
                                @else
                                    <span class="text-gray-300">★</span>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <div class="mt-4 flex justify-between items-center">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                            Pendiente
                        </span>
                        @if (!$isSearch)
                        <form method="POST" action="{{ route('tareas.finish', $tareaPrincipal->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Terminar Tarea
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Grid de Tareas Secundarias -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($tareas as $tarea)
                    @if ($tareaPrincipal && $tarea->id === $tareaPrincipal->id)
                        @continue
                    @endif
                    <div class="bg-gray-100 overflow-hidden shadow-sm rounded-lg opacity-50 cursor-not-allowed">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-500">
                                    {{ $tarea->identificacion }}
                                </h4>
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-500">
                                    {{ $tarea->obligatorio ? 'Obligatoria' : 'Opcional' }}
                                </span>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                <p class="text-sm text-gray-400">
                                    {{ Str::limit($tarea->descripcion, 150) }}
                                </p>
                            </div>

                            <div class="flex justify-between items-center text-sm text-gray-400 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Duración: {{ $tarea->tiempoDuracion }} min
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Prioridad: 
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= ($tarea->prioridad ?? 0))
                                            <span class="text-gray-300">★</span>
                                        @else
                                            <span class="text-gray-300">★</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end space-x-2">
                                <span class="inline-flex items-center px-3 py-1 bg-gray-200 border border-transparent rounded-md font-medium text-xs text-gray-400 cursor-not-allowed">
                                    Editar
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-gray-50 rounded-lg p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tareas</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva tarea para esta actividad.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>