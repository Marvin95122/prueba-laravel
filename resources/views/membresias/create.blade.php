<x-app-layout>
    <div class="container py-4">
        <div class="mb-4">
            <h2 class="h4 fw-bold text-dark">
                <i class="bi bi-plus-circle text-success me-2"></i> Nueva membresía
            </h2>
            <small class="text-muted">Registra un nuevo plan para el gimnasio.</small>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @include('membresias.partials.form', [
                    'action' => route('membresias.store'),
                    'method' => 'POST',
                    'membresia' => null
                ])
            </div>
        </div>
    </div>
</x-app-layout>