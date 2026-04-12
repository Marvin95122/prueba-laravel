<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Panel de Control</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">Resumen general de GymControl.</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('clientes.index') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition-colors">
                    Ir a Clientes
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold">¡Hola, {{ Auth::user()->name ?? 'Administrador' }}! 👋</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Aquí tienes un resumen en tiempo real del estado de las membresías.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                
                <div class="flex items-center rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/30">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Clientes Totales</p>
                        <p class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">{{ \App\Models\Cliente::count() }}</p>
                    </div>
                </div>

                <div class="flex items-center rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/30">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Activas</p>
                        <p class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">{{ \App\Models\Cliente::where('estado','activa')->count() }}</p>
                    </div>
                </div>

                <div class="flex items-center rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="rounded-full bg-red-100 p-3 dark:bg-red-900/30">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Inactivas</p>
                        <p class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">{{ \App\Models\Cliente::where('estado','inactiva')->count() }}</p>
                    </div>
                </div>

                <div class="flex items-center rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="rounded-full bg-yellow-100 p-3 dark:bg-yellow-900/30">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Vencen en 7 días</p>
                        <p class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">{{ \App\Models\Cliente::whereDate('vigencia_hasta','<=', now()->addDays(7))->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                
                <div class="lg:col-span-1 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/60 rounded-t-xl">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Acciones Rápidas</h3>
                    </div>

                    <div class="p-6 space-y-3">
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <a href="{{ route('clientes.create') }}"
                               class="flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                + Registrar nuevo cliente
                            </a>
                        @endif

                        <a href="{{ route('clientes.index') }}"
                           class="flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition-colors">
                            Ver listado completo
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 flex justify-between items-center dark:border-gray-700 dark:bg-gray-900/60 rounded-t-xl">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Últimos Registros</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Los 6 miembros más recientes.</p>
                        </div>
                        <a href="{{ route('clientes.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Ver todos &rarr;</a>
                    </div>

                    <div class="p-0 sm:p-6">
                        <div class="overflow-x-auto overflow-hidden sm:rounded-lg border-t sm:border border-gray-200 dark:border-gray-700">
                            <table class="min-w-[700px] w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                                <thead class="bg-gray-50 text-gray-700 dark:bg-gray-900/60 dark:text-gray-200">
                                    <tr>
                                        <th class="whitespace-nowrap px-6 py-3 text-left font-semibold">Nombre</th>
                                        <th class="whitespace-nowrap px-6 py-3 text-left font-semibold">Membresía</th>
                                        <th class="whitespace-nowrap px-6 py-3 text-left font-semibold">Vigencia</th>
                                        <th class="whitespace-nowrap px-6 py-3 text-center font-semibold">Estado</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse(\App\Models\Cliente::orderBy('id','desc')->take(6)->get() as $c)
                                        <tr class="bg-white hover:bg-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700/40 transition-colors">
                                            <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-gray-100">
                                                {{ $c->nombre }}
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center">
                                                <div class="flex flex-col items-center justify-center space-y-2">
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">No hay clientes todavía.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>