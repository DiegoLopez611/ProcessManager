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
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $actividad->descripcion }}
                            </p>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $actividad->es_obligatoria ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $actividad->obligatorio ? 'Obligatoria' : 'Opcional' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Encabezado del formulario -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800">
                            Crear Nueva Tarea
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Complete la información para crear una nueva tarea en esta actividad.
                        </p>
                    </div>

                    <!-- Formulario -->
                    <form action="{{ route('tareas.store', $actividad->id) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Campo de Posicionamiento de Tarea -->
                        <div>
                            <x-label for="posicionamiento" value="Posicionamiento de la Tarea" />
                            <div class="mt-2">
                                <select 
                                    id="posicionamiento" 
                                    name="posicionamiento" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="final">Agregar al Final</option>
                                    <option value="despues">Agregar después de</option>
                                </select>
                            </div>
                        </div>

                        <!-- Campo Despues De (Inicialmente Oculto) -->
                        <div id="despues-de-container" style="display: none;">
                            <x-label for="despues_de" value="Identificación de la Tarea Anterior" />
                            <select 
                                id="despues_de" 
                                name="despues_de" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                @foreach($tareas as $tarea)
                                    <option value="{{ $tarea->id }}">{{ $tarea->identificacion }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Campo de Identificación -->
                        <div>
                            <x-label for="identificacion" value="Identificación" />
                            <x-input 
                                id="identificacion" 
                                name="identificacion" 
                                type="text" 
                                class="mt-1 block w-full" 
                                value="{{ old('identificacion') }}" 
                                required 
                            />
                            @error('identificacion')
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
                                placeholder="Ingrese la descripción de la tarea"
                            >{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo de Tiempo de Duración -->
                        <div>
                            <x-label for="tiempoDuracion" value="Tiempo de Duración (minutos)" />
                            <x-input 
                                id="tiempoDuracion" 
                                name="tiempoDuracion" 
                                type="number" 
                                min="1"
                                class="mt-1 block w-full" 
                                value="{{ old('tiempoDuracion') }}" 
                                required 
                            />
                            @error('tiempoDuracion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo de Nivel de Prioridad -->
                        <div>
                            <x-label for="nivelPrioridad" value="Nivel de Prioridad" />
                            <div class="mt-2 flex items-center space-x-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="inline-flex items-center">
                                        <input 
                                            type="radio" 
                                            name="nivelPrioridad" 
                                            value="{{ $i }}" 
                                            {{ old('nivelPrioridad') == $i ? 'checked' : '' }}
                                            class="form-radio text-indigo-600"
                                        >
                                        <span class="ml-1">
                                            @for ($j = 1; $j <= $i; $j++)
                                                <span class="text-yellow-500">★</span>
                                            @endfor
                                        </span>
                                    </label>
                                @endfor
                            </div>
                            @error('nivelPrioridad')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo de Obligatoriedad -->
                        <div>
                            <div class="flex items-center">
                                <input type="hidden" name="obligatoria" value="0">
                                <input 
                                    type="checkbox" 
                                    id="obligatoria" 
                                    name="obligatoria" 
                                    value="1"
                                    {{ old('obligatoria', false) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                <x-label for="obligatoria" value="¿Es una tarea obligatoria?" class="ml-2" />
                            </div>
                            @error('obligatoria')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <a 
                                href="{{ route('tareas.index', $actividad->id) }}" 
                                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Cancelar
                            </a>
                            <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-700">
                                Crear Tarea
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
            const posicionamientoSelect = document.getElementById('posicionamiento');
            const despuesDeContainer = document.getElementById('despues-de-container');

            function toggleDespuesDe() {
                if (posicionamientoSelect.value === 'despues') {
                    despuesDeContainer.style.display = 'block';
                } else {
                    despuesDeContainer.style.display = 'none';
                }
            }

            toggleDespuesDe();
            posicionamientoSelect.addEventListener('change', toggleDespuesDe);
        });
    </script>
    @endpush
</x-app-layout>