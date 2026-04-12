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

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Listado</h3>
                    </div>
                </div>

                <div class="p-0 sm:p-6">
                    <div class="overflow-hidden overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        
                        <table class="min-w-[1000px] w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                            <thead class="bg-gray-50 text-gray-700 dark:bg-gray-900/60 dark:text-gray-200">
                                <tr>
                                    <th class="whitespace-nowrap px-6 py-4 text-left font-semibold">Nombre</th>
                                    <th class="whitespace-nowrap px-6 py-4 text-left font-semibold">Teléfono</th>
                                    <th class="whitespace-nowrap px-6 py-4 text-left font-semibold">Membresía</th>
                                    <th class="whitespace-nowrap px-6 py-4 text-left font-semibold">Vigencia</th>
                                    <th class="whitespace-nowrap px-6 py-4 text-center font-semibold">Estado</th>
                                    <th class="whitespace-nowrap px-6 py-4 text-center font-semibold">Acciones</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($clientes as $c)
                                    <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/40 transition-colors">
                                        <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                            {{ $c->nombre }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-200">
                                            {{ $c->telefono ?? '-' }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-200 capitalize">
                                            <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ring-gray-200 dark:ring-gray-700 bg-gray-50 dark:bg-gray-900">
                                                {{ $c->membresia }}
                                            </span>
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-gray-700 dark:text-gray-200">
                                            {{ \Carbon\Carbon::parse($c->vigencia_hasta)->format('d/m/Y') }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-center">
                                            @if($c->estado === 'activa')
                                                <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-500/20">
                                                    Activa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-900/20 dark:text-red-400 dark:ring-red-500/20">
                                                    Inactiva
                                                </span>
                                            @endif
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('clientes.show', $c) }}"
                                                   class="rounded-md bg-white px-2.5 py-1.5 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:hover:bg-gray-600">
                                                    Ver
                                                </a>

                                                @if(auth()->user()->role === 'admin')
                                                    <a href="{{ route('clientes.edit', $c) }}"
                                                       class="rounded-md bg-indigo-50 px-2.5 py-1.5 text-xs font-semibold text-indigo-600 shadow-sm ring-1 ring-inset ring-indigo-500/30 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300 dark:ring-indigo-500/30 dark:hover:bg-indigo-900/50">
                                                        Editar
                                                    </a>

                                                    <form action="{{ route('clientes.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar miembro definitivamente?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="rounded-md bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-500/30 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-500/30 dark:hover:bg-red-900/50">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-3">
                                                <svg class="h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No hay miembros registrados todavía.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 px-4 sm:px-0">
                        {{ $clientes->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>