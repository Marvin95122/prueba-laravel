<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="card shadow-sm border-0" style="border-radius: 10px; border-top: 5px solid #0d6efd;">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i> Editar Cliente</h5>
                        <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nombre Completo</label>
                                <input type="text" name="nombre" class="form-control" required value="{{ old('nombre', $cliente->nombre) }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Membresía</label>
                                <select name="membresia_id" class="form-select" required>
                                    <option value="">Selecciona una membresía</option>

                                    @foreach($membresias as $membresia)
                                        <option value="{{ $membresia->id }}" 
                                            @selected(old('membresia_id', $cliente->membresia_id) == $membresia->id)>
                                            {{ $membresia->nombre }} - ${{ number_format($membresia->precio, 2) }} / {{ $membresia->duracion_dias }} días
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Vigencia hasta</label>
                                <input type="date" name="vigencia_hasta" class="form-control" required value="{{ old('vigencia_hasta', $cliente->vigencia_hasta) }}">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Estado</label>
                                <select name="estado" class="form-select" required>
                                    <option value="activa" {{ $cliente->estado == 'activa' ? 'selected' : '' }}>Activa (Permitir acceso)</option>
                                    <option value="inactiva" {{ $cliente->estado == 'inactiva' ? 'selected' : '' }}>Inactiva (Bloquear acceso)</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                                <i class="bi bi-arrow-repeat me-1"></i> Actualizar Datos
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>