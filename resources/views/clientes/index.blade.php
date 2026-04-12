<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Clientes</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Administración de miembros y estado de su membresía.
                </p>
            </div>

            <a href="{{ route('clientes.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                + Nuevo cliente
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">

            @if(session('ok'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800
                            dark:border-green-900/40 dark:bg-green-900/30 dark:text-green-200">
                    {{ session('ok') }}
                </div>
            @endif

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm
                        dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Listado</h3>
                    </div>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-[900px] w-full">
                            <thead class="bg-gray-50 text-gray-700 dark:bg-gray-900/60 dark:text-gray-200">
                                <tr>
                                    <th class="px-5 py-3 text-left text-sm font-semibold">Nombre</th>
                                    <th class="px-5 py-3 text-left text-sm font-semibold">Teléfono</th>
                                    <th class="px-5 py-3 text-left text-sm font-semibold">Membresía</th>
                                    <th class="px-5 py-3 text-left text-sm font-semibold">Vigencia</th>
                                    <th class="px-5 py-3 text-left text-sm font-semibold">Estado</th>
                                    <th class="px-5 py-3 text-left text-sm font-semibold">Acciones</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($clientes as $c)
                                    <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/40">
                                        <td class="px-5 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $c->nombre }}
                                        </td>

                                        <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $c->telefono ?? '-' }}
                                        </td>

                                        <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-200 capitalize">
                                            {{ $c->membresia }}
                                        </td>

                                        <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $c->vigencia_hasta }}
                                        </td>

                                        <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-200">
                                            @if($c->estado === 'activa')
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
                                        </td>

                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('clientes.show', $c) }}"
                                                   class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100
                                                          dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                                    Ver
                                                </a>

                                                @if(auth()->user()->role === 'admin')
                                                    <a href="{{ route('clientes.edit', $c) }}"
                                                       class="rounded-lg border border-yellow-300 bg-yellow-50 px-3 py-1.5 text-xs font-semibold text-yellow-900 hover:bg-yellow-100
                                                              dark:border-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-200 dark:hover:bg-yellow-900/40">
                                                        Editar
                                                    </a>

                                                    <form action="{{ route('clientes.destroy', $c) }}" method="POST"
                                                          onsubmit="return confirm('¿Eliminar cliente?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="rounded-lg border border-red-300 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-800 hover:bg-red-100
                                                                       dark:border-red-700 dark:bg-red-900/30 dark:text-red-200 dark:hover:bg-red-900/40">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>

                                            @if(auth()->user()->role !== 'admin')
                                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                    *Recepción solo puede ver y registrar.
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-600 dark:text-gray-300">
                                            No hay clientes registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $clientes->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>