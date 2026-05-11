<x-app-layout>
    <style>
        .exercise-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,.04);
        }

        .exercise-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
        }

        .exercise-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #eaf7ee;
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .instruction-box {
            background: #f8f9fa;
            border-left: 4px solid #198754;
            border-radius: 10px;
            padding: 1rem;
            color: #495057;
        }

        .filter-panel {
            border-top: 4px solid #198754;
        }

        .empty-state {
            text-align: center;
            color: #6c757d;
            padding: 3rem 1rem;
        }

        .empty-state i {
            font-size: 3rem;
            display: block;
            margin-bottom: 1rem;
        }
    </style>

    <div class="container py-4">

        {{-- Encabezado --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-0 text-dark">
                    <i class="bi bi-activity text-success me-2"></i>
                    Buscador de Ejercicios
                </h2>
                <small class="text-muted">
                    Consulta ejercicios desde Wger Exercises API por músculo, tipo y dificultad.
                </small>
            </div>

            <div class="px-4 py-2 text-center border border-success rounded bg-success bg-opacity-10">
                <span class="d-block small text-success fw-bold text-uppercase">API externa</span>
                <span class="fw-bold text-dark">Wger Exercises API</span>
            </div>
        </div>

        @if($error)
            <div class="alert alert-danger shadow-sm">
                <i class="bi bi-x-circle-fill me-2"></i>
                {{ $error }}
            </div>
        @endif

        <div class="row g-4">

            {{-- Filtros --}}
            <div class="col-lg-4">
                <div class="exercise-card filter-panel h-100">
                    <div class="exercise-header p-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-funnel-fill text-success me-2"></i>
                            Filtros de búsqueda
                        </h6>
                    </div>

                    <div class="p-3">
                        <form method="GET" action="{{ route('ejercicios.index') }}">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nombre del ejercicio</label>
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ request('name') }}"
                                       placeholder="Ej. press, squat, curl">
                                <small class="text-muted">
                                    Puedes escribir una palabra parcial, por ejemplo: press.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tipo de ejercicio</label>
                                <select name="type" class="form-select">
                                    <option value="">Todos</option>
                                    @foreach($tipos as $value => $label)
                                        <option value="{{ $value }}" @selected(request('type') === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Músculo</label>
                                <select name="muscle" class="form-select">
                                    <option value="">Todos</option>
                                    @foreach($musculos as $value => $label)
                                        <option value="{{ $value }}" @selected(request('muscle') === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Dificultad</label>
                                <select name="difficulty" class="form-select">
                                    <option value="">Todas</option>
                                    @foreach($dificultades as $value => $label)
                                        <option value="{{ $value }}" @selected(request('difficulty') === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 mb-2">
                                <i class="bi bi-search me-1"></i>
                                Buscar ejercicios
                            </button>

                            <a href="{{ route('ejercicios.index') }}" class="btn btn-outline-secondary w-100">
                                Limpiar filtros
                            </a>
                        </form>

                        <div class="alert alert-info small mt-4 mb-0">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            Esta sección consume una API web externa y muestra resultados en tiempo real.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resultados --}}
            <div class="col-lg-8">
                <div class="exercise-card">
                    <div class="exercise-header p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-0">
                                <i class="bi bi-list-task text-success me-2"></i>
                                Resultados encontrados
                            </h6>
                            <small class="text-muted">
                                La API devuelve ejercicios con instrucciones generales de ejecución. Algunos ejercicios pueden aparecer en inglés si no tienen traducción disponible.
                            </small>
                        </div>

                        <span class="badge bg-success px-3 py-2">
                            {{ count($ejercicios) }} resultado(s)
                        </span>
                    </div>

                    <div class="p-3">
                        @if(!$consultado)
                            <div class="empty-state">
                                <i class="bi bi-search text-success"></i>
                                <h6 class="fw-bold">Realiza una búsqueda</h6>
                                <p class="mb-0">
                                    Selecciona un músculo, tipo, dificultad o escribe el nombre de un ejercicio.
                                </p>
                            </div>
                        @elseif(empty($ejercicios))
                            <div class="empty-state">
                                <i class="bi bi-inbox text-secondary"></i>
                                <h6 class="fw-bold">No se encontraron ejercicios</h6>
                                <p class="mb-0">
                                    Intenta cambiar los filtros de búsqueda.
                                </p>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach($ejercicios as $ejercicio)
                                    <div class="col-12">
                                        <div class="border rounded p-3 bg-white shadow-sm">
                                            <div class="d-flex gap-3">
                                                <div class="exercise-icon flex-shrink-0">
                                                    <i class="bi bi-lightning-charge-fill"></i>
                                                </div>

                                                <div class="w-100">
                                                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                                                        <div>
                                                            <h5 class="fw-bold mb-1">
                                                                {{ $ejercicio['name'] ?? 'Ejercicio sin nombre' }}
                                                            </h5>

                                                            <div class="d-flex gap-2 flex-wrap mb-2">
                                                                <span class="badge bg-success">
                                                                    {{ $ejercicio['type'] ?? 'Sin tipo' }}
                                                                </span>

                                                                <span class="badge bg-primary">
                                                                    {{ $ejercicio['muscle'] ?? 'Sin músculo' }}
                                                                </span>

                                                                <span class="badge bg-warning text-dark">
                                                                    {{ $ejercicio['difficulty'] ?? 'Sin dificultad' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="instruction-box mt-2">
                                                        <div class="fw-bold mb-1">
                                                            <i class="bi bi-card-text me-1"></i>
                                                            Instrucciones
                                                        </div>

                                                        <p class="mb-0">
                                                            {{ $ejercicio['instructions'] ?? 'Sin instrucciones disponibles.' }}
                                                        </p>
                                                    </div>

                                                    <div class="mt-3 small text-muted">
                                                        <i class="bi bi-cloud-check me-1"></i>
                                                        Información obtenida desde Wger Exercises API.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>