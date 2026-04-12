<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Dashboard</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">Resumen general del gimnasio (GymControl).</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('clientes.index') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700">
                    Ir a Clientes
                </a>

                <a href="{{ route('clientes.create') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-100
                          dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">
                    Registrar cliente
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8 space-y-6">

            {{-- Tarjetas KPI --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Clientes registrados</p>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                        {{ \App\Models\Cliente::count() }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Total en el sistema.</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Membresías activas</p>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                        {{ \App\Models\Cliente::where('estado','activa')->count() }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Clientes con estado activa.</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Membresías inactivas</p>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                        {{ \App\Models\Cliente::where('estado','inactiva')->count() }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Clientes sin acceso.</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Vencen pronto (7 días)</p>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                        {{ \App\Models\Cliente::whereDate('vigencia_hasta','<=', now()->addDays(7))->count() }}
                    </p>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Recomendado renovar.</p>
                </div>
            </div>

            {{-- Sección: Acciones rápidas + Últimos clientes --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="lg:col-span-1 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Acciones rápidas</h3>
                    </div>

                    <div class="p-6 space-y-3">
                        <a href="{{ route('clientes.create') }}"
                           class="block rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                            + Registrar nuevo cliente
                        </a>

                        <a href="{{ route('clientes.index') }}"
                           class="block rounded-lg border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-gray-100
                                  dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">
                            Ver listado de clientes
                        </a>

                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700
                                    dark:border-gray-700 dark:bg-gray-900/40 dark:text-gray-200">
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Últimos clientes</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Los más recientes registrados.</p>
                    </div>

                    <div class="p-6">
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-[700px] w-full">
                                <thead class="bg-gray-50 text-gray-700 dark:bg-gray-900/60 dark:text-gray-200">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-sm font-semibold">Nombre</th>
                                        <th class="px-5 py-3 text-left text-sm font-semibold">Membresía</th>
                                        <th class="px-5 py-3 text-left text-sm font-semibold">Vigencia</th>
                                        <th class="px-5 py-3 text-left text-sm font-semibold">Estado</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse(\App\Models\Cliente::orderBy('id','desc')->take(6)->get() as $c)
                                        <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/40">
                                            <td class="px-5 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $c->nombre }}</td>
                                            <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-200 capitalize">{{ $c->membresia }}</td>
                                            <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $c->vigencia_hasta }}</td>
                                            <td class="px-5 py-4">
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-600 dark:text-gray-300">
                                                No hay clientes todavía. Registra el primero.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('clientes.index') }}" class="text-indigo-500 hover:underline text-sm font-semibold">
                                Ver todos los clientes →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>