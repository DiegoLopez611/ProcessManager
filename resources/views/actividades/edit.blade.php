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
                    <form action="{{ route('actividades.update' , $actividad->id ) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                    

                        <!-- Campo de Nombre -->
                        <div>
                            <x-label for="nombre" value="Nombre" />
                            <x-input 
                                id="nombre" 
                                name="nombre" 
                                type="text" 
                                class="mt-1 block w-full" 
                                value="{{ old('nombre' , $actividad->nombre) }}" 
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
                            >{{ old('nombre' , $actividad->nombre) }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center">
                                <!-- Agregamos un campo oculto que siempre enviará 0 -->
                                <input type="hidden" name="obligatorio" value="0">
                                <input 
                                    type="checkbox" 
                                    id="obligatorio" 
                                    name="obligatorio" 
                                    value="1"
                                    {{ old('obligatorio', false) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                <x-label for="obligatorio" value="¿Es una actividad obligatoria?" class="ml-2" />
                            </div>
                            @error(old('obligatorio' , $actividad->obligatorio))
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
                                Editar Actividad
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