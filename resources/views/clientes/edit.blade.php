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

                        @include('clientes.partials.form', ['cliente' => $cliente])

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