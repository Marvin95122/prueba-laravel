<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Editar cliente</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">Actualiza datos de membresía y estado.</p>
            </div>

            <a href="{{ route('clientes.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-100
                      dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Editar: {{ $cliente->nombre }}</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Guarda cambios cuando termines.</p>
                </div>

                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800
                                    dark:border-red-900/40 dark:bg-red-900/30 dark:text-red-200">
                            <p class="font-semibold">Revisa los siguientes errores:</p>
                            <ul class="mt-2 list-disc pl-5">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('clientes.update', $cliente) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Nombre</label>
                                <input name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required
                                       class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900
                                              focus:border-indigo-500 focus:ring-indigo-500
                                              dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100" />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Teléfono</label>
                                <input name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                                       class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900
                                              focus:border-indigo-500 focus:ring-indigo-500
                                              dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100" />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Membresía</label>
                                <select name="membresia" required
                                        class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900
                                               focus:border-indigo-500 focus:ring-indigo-500
                                               dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                    <option value="basica" @selected(old('membresia',$cliente->membresia)==='basica')>Básica</option>
                                    <option value="plus" @selected(old('membresia',$cliente->membresia)==='plus')>Plus</option>
                                    <option value="premium" @selected(old('membresia',$cliente->membresia)==='premium')>Premium</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Vigencia hasta</label>
                                <input type="date" name="vigencia_hasta"
                                       value="{{ old('vigencia_hasta', $cliente->vigencia_hasta) }}" required
                                       class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900
                                              focus:border-indigo-500 focus:ring-indigo-500
                                              dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100" />
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Estado</label>
                                <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <label class="flex items-center gap-3 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3
                                                  dark:border-gray-600 dark:bg-gray-900">
                                        <input type="radio" name="estado" value="activa" required
                                               @checked(old('estado', $cliente->estado)==='activa')>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Activa</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-300">Permite acceso al gimnasio.</p>
                                        </div>
                                    </label>

                                    <label class="flex items-center gap-3 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3
                                                  dark:border-gray-600 dark:bg-gray-900">
                                        <input type="radio" name="estado" value="inactiva" required
                                               @checked(old('estado', $cliente->estado)==='inactiva')>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Inactiva</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-300">No permite acceso.</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <a href="{{ route('clientes.index') }}"
                               class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-100
                                      dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow
                                           hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>