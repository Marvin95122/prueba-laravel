<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GymControl - Bienvenidos</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">

    <nav class="border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <div class="flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="text-xl font-bold tracking-wider">GymControl</span>
                </div>

                <div>
                    @if (Route::has('login'))
                        <div class="flex gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-700 transition-colors">
                                    Ir al Panel
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 transition-colors">
                                    Iniciar Sesión
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        
        <div class="text-center py-16 px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                <span class="block">Tus instalaciones de</span>
                <span class="block text-indigo-600 dark:text-indigo-400 mt-2">Primer Nivel</span>
            </h1>
            <p class="mt-4 max-w-md mx-auto text-base text-gray-500 dark:text-gray-400 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Conoce nuestras áreas de entrenamiento equipadas con la mejor tecnología para que alcances tus objetivos.
            </p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <img src="{{ asset('imagenes/cardio.jpg') }}" alt="Área de Cardio" class="w-full h-48 object-cover bg-gray-200 dark:bg-gray-700">
                    <div class="p-5 flex flex-col h-full">
                        <h3 class="text-lg font-bold mb-2 text-gray-900 dark:text-gray-100">Área de Cardio</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 flex-grow">Caminadoras y elípticas con pantallas integradas.</p>
                        <div>
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-500/20">Plan Básico</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <img src="{{ asset('imagenes/maquinas.jpg') }}" alt="Peso Integrado" class="w-full h-48 object-cover bg-gray-200 dark:bg-gray-700">
                    <div class="p-5 flex flex-col h-full">
                        <h3 class="text-lg font-bold mb-2 text-gray-900 dark:text-gray-100">Peso Integrado</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 flex-grow">Máquinas para aislar cada grupo muscular de forma segura.</p>
                        <div>
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/20 dark:text-green-400 dark:ring-green-500/20">Plan Básico</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <img src="{{ asset('imagenes/crossfit.jpg') }}" alt="Crossfit Zone" class="w-full h-48 object-cover bg-gray-200 dark:bg-gray-700">
                    <div class="p-5 flex flex-col h-full">
                        <h3 class="text-lg font-bold mb-2 text-gray-900 dark:text-gray-100">Crossfit Zone</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 flex-grow">Cajas de salto, cuerdas de batalla y pesas rusas.</p>
                        <div>
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-900/20 dark:text-blue-400 dark:ring-blue-500/20">Plan Plus</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300 hover:-translate-y-2">
                    <img src="{{ asset('imagenes/vestidores.jpg') }}" alt="Regaderas y Lockers" class="w-full h-48 object-cover bg-gray-200 dark:bg-gray-700">
                    <div class="p-5 flex flex-col h-full">
                        <h3 class="text-lg font-bold mb-2 text-gray-900 dark:text-gray-100">Regaderas y Lockers</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 flex-grow">Vestidores limpios, seguros y con agua caliente 24/7.</p>
                        <div>
                            <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-600/20 dark:bg-purple-900/20 dark:text-purple-400 dark:ring-purple-500/20">Todos los planes</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} GymControl. Todos los derechos reservados.
            </p>
            <div class="flex gap-4 text-sm text-gray-500 dark:text-gray-400">
                <span>soporte@gymcontrol.com</span>
                <span class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">Ayuda</span>
            </div>
        </div>
    </footer>
</body>
</html>