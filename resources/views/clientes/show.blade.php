<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Detalle del cliente</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">Información completa del miembro.</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-outline-secondary">
                    Ver
                </a>

                @if(in_array(auth()->user()->role, ['admin', 'gerente']))
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-outline-primary">
                        Editar
                    </a>
                @endif

                @if(auth()->user()->role === 'admin')
                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" type="submit">
                            Eliminar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="p-6">

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Nombre</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $cliente->nombre }}</p>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Teléfono</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $cliente->telefono ?? '-' }}</p>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Membresía</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100 capitalize">{{ $cliente->nombre_membresia }}</p>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Vigencia hasta</p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $cliente->vigencia_hasta }}</p>
                        </div>

                        <div class="sm:col-span-2 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Estado</p>
                            <div class="mt-2">
                                @if($cliente->estado === 'activa')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-800
                                                 dark:bg-green-900/40 dark:text-green-200">
                                        Activa
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-800
                                                 dark:bg-red-900/40 dark:text-red-200">
                                        Inactiva
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <div class="mt-6 flex justify-end">
                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar cliente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="rounded-lg border border-red-300 bg-red-50 px-4 py-2 text-sm font-semibold text-red-800 hover:bg-red-100
                                               dark:border-red-700 dark:bg-red-900/30 dark:text-red-200 dark:hover:bg-red-900/40">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>