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
                            {{ ucfirst($proceso->estado) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Encabezado del formulario -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800">
                            Crear Nueva Actividad
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Complete la información básica para crear una nueva actividad.
                        </p>
                    </div>

                    <!-- Formulario -->
                    <form action="{{ route('actividades.store', $proceso) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Campo de Posicionamiento de Actividad -->
                        <div>
                            <x-label for="posicionamiento" value="Posicionamiento de la Actividad" />
                            <div class="mt-2">
                                <select 
                                    id="posicionamiento" 
                                    name="posicionamiento" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="final">Agregar al Final</option>
                                    <option value="ultimo">Agregar después del Último Agregado</option>
                                    <option value="despues">Agregar después de</option>
                                </select>
                            </div>
                        </div>

                        <!-- Campo Despues De (Inicialmente Oculto) -->
                        <div id="despues-de-container" style="display: none;">
                            <x-label for="despues_de" value="Nombre de la Actividad Anterior" />
                            <select 
                                id="despues_de" 
                                name="despues_de" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                @foreach($actividades as $actividad)
                                    <option value="{{ $actividad->id }}">{{ $actividad->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Campo de Nombre -->
                        <div>
                            <x-label for="nombre" value="Nombre" />
                            <x-input 
                                id="nombre" 
                                name="nombre" 
                                type="text" 
                                class="mt-1 block w-full" 
                                value="{{ old('nombre') }}" 
                                required 
                            />
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo de Descripción -->
                        <div>
                            <x-label for="descripcion" value="Descripción" />
                            <textarea 
                                id="descripcion" 
                                name="descripcion" 
                                rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Ingrese la descripción de la actividad"
                            >{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <a 
                                href="{{ route('actividades.index', $proceso) }}" 
                                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Cancelar
                            </a>
                            <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-700">
                                Crear Actividad
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('El script se está ejecutando');
            const posicionamientoSelect = document.getElementById('posicionamiento');
            const despuesDeContainer = document.getElementById('despues-de-container');

            // Función para mostrar/ocultar el contenedor basado en el valor del select
            function toggleDespuesDe() {
                if (posicionamientoSelect.value === 'despues') {
                    despuesDeContainer.style.display = 'block';
                } else {
                    despuesDeContainer.style.display = 'none';
                }
            }

            // Llamar inicialmente para establecer el estado correcto al cargar la página
            toggleDespuesDe();

            // Agregar evento de cambio al select
            posicionamientoSelect.addEventListener('change', toggleDespuesDe);
        });
    </script>
@endpush
</x-app-layout>