<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Encabezado del formulario -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800">
                            Editar Proceso
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Complete la información básica para editar correctamente el proceso.
                        </p>
                    </div>

                    <!-- Formulario -->
                    <form action="{{ route('procesos.update', $proceso->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Campo de Identificación -->
                        <div>
                            <x-label for="identificacion" value="Identificación" />
                            <x-input 
                                id="identificacion" 
                                name="identificacion" 
                                type="text" 
                                class="mt-1 block w-full" 
                                value="{{ old('identificacion' , $proceso->identificacion) }}" 
                                required 
                                autofocus 
                            />
                            @error('identificacion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo de Nombre -->
                        <div>
                            <x-label for="nombre" value="Nombre" />
                            <x-input 
                                id="nombre" 
                                name="nombre" 
                                type="text" 
                                class="mt-1 block w-full" 
                                value="{{ old('nombre', $proceso->nombre) }}" 
                                required 
                            />
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="descripcion" value="Descripcion" />
                            <x-input 
                                id="descripcion" 
                                name="descripcion" 
                                type="text" 
                                class="mt-1 block w-full" 
                                value="{{ old('descripcion', $proceso->descripcion) }}" 
                                required 
                            />
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <a 
                                href="{{ route('procesos.index') }}" 
                                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Cancelar
                            </a>
                            <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-700">
                                Editar Proceso
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>