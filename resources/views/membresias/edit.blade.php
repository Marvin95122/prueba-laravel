<x-app-layout>
    <div class="container py-4">
        <div class="mb-4">
            <h2 class="h4 fw-bold text-dark">
                <i class="bi bi-pencil-square text-success me-2"></i> Editar membresía
            </h2>
            <small class="text-muted">Actualiza la información del plan seleccionado.</small>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @include('membresias.partials.form', [
                    'action' => route('membresias.update', $membresia),
                    'method' => 'PUT',
                    'membresia' => $membresia
                ])
            </div>
        </div>
    </div>
</x-app-layout>