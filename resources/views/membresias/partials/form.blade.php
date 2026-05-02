@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Revisa los siguientes errores:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $action }}" method="POST">
    @csrf

    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Nombre del plan</label>
            <input type="text" name="nombre" class="form-control"
                   value="{{ old('nombre', $membresia->nombre ?? '') }}"
                   placeholder="Ej. Básica, Plus, Premium" required>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Precio</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" step="0.01" name="precio" class="form-control"
                       value="{{ old('precio', $membresia->precio ?? '') }}" required>
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Duración en días</label>
            <input type="number" name="duracion_dias" class="form-control"
                   value="{{ old('duracion_dias', $membresia->duracion_dias ?? 30) }}" required>
        </div>

        <div class="col-md-12">
            <label class="form-label fw-semibold">Descripción</label>
            <textarea name="descripcion" rows="3" class="form-control"
                      placeholder="Describe brevemente la membresía">{{ old('descripcion', $membresia->descripcion ?? '') }}</textarea>
        </div>

        <div class="col-md-12">
            <label class="form-label fw-semibold">Beneficios</label>
            <textarea name="beneficios" rows="4" class="form-control"
                      placeholder="Ej. Acceso a pesas, cardio, clases grupales...">{{ old('beneficios', $membresia->beneficios ?? '') }}</textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Estado</label>
            <select name="estado" class="form-select" required>
                <option value="activa" @selected(old('estado', $membresia->estado ?? 'activa') === 'activa')>
                    Activa
                </option>
                <option value="inactiva" @selected(old('estado', $membresia->estado ?? '') === 'inactiva')>
                    Inactiva
                </option>
            </select>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('membresias.index') }}" class="btn btn-outline-secondary">
            Cancelar
        </a>

        <button type="submit" class="btn btn-success fw-bold">
            Guardar
        </button>
    </div>
</form>