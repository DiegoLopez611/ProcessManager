<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información del Proceso -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">
                                {{ $proceso->nombre }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Identificación: {{ $proceso->identificacion }}
                            </p>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $proceso->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($proceso->estado ?? 'activo') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Header de Actividades -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">
                    Actividades del Proceso
                </h3>
                <a href="{{ route('actividades.create', $proceso) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nueva Actividad
                </a>
            </div>

            <!-- Grid de Actividades -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($actividades as $actividad)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-900">
                                    {{ $actividad->nombre }}
                                </h4>
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $actividad->es_obligatoria ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $actividad->obligatoria ? 'Obligatoria' : 'Opcional' }}
                                </span>
                            </div>
                            
                            <div class="space-y-3">
                                <p class="text-sm text-gray-600">
                                    {{ Str::limit($actividad->descripcion, 150) }}
                                </p>
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Fecha Creación: {{ $actividad->created_at->format('d/m/Y') }}
                                </div>

                            <div class="mt-4 flex justify-end space-x-2">
                                <a href="{{ route('procesos.actividades.show', [$proceso, $actividad]) }}" class="inline-flex items-center px-3 py-1 bg-gray-100 border border-transparent rounded-md font-medium text-xs text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150">
                                    Ver detalles
                                </a>
                                <a href="{{ route('procesos.actividades.edit', [$proceso, $actividad]) }}" class="inline-flex items-center px-3 py-1 bg-blue-100 border border-transparent rounded-md font-medium text-xs text-blue-700 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-gray-50 rounded-lg p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay actividades</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva actividad para este proceso.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>