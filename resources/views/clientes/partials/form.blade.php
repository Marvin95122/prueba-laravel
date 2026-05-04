<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Nombre</label>
        <input name="nombre" value="{{ old('nombre', $cliente->nombre ?? '') }}" required
               class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900
                      focus:border-indigo-500 focus:ring-indigo-500
                      dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:placeholder-gray-400" />
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ej. Juan Pérez</p>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Teléfono</label>
        <input name="telefono" value="{{ old('telefono', $cliente->telefono ?? '') }}"
               class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900
                      focus:border-indigo-500 focus:ring-indigo-500
                      dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:placeholder-gray-400" />
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Opcional</p>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Membresía</label>
        <select name="membresia_id" class="form-select" required>
            <option value="">Selecciona una membresía</option>

            @foreach($membresias as $membresia)
                <option value="{{ $membresia->id }}" @selected(old('membresia_id', $cliente->membresia_id) == $membresia->id)>
                    {{ $membresia->nombre }} - ${{ number_format($membresia->precio, 2) }} / {{ $membresia->duracion_dias }} días
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Vigencia hasta</label>
        <input type="date" name="vigencia_hasta" value="{{ old('vigencia_hasta', $cliente->vigencia_hasta ?? '') }}" required
               class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900
                      focus:border-indigo-500 focus:ring-indigo-500
                      dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100" />
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200">Estado</label>
        <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <label class="flex items-center gap-3 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3
                          dark:border-gray-600 dark:bg-gray-900">
                <input type="radio" name="estado" value="activa" required
                       @checked(old('estado', $cliente->estado ?? 'activa') === 'activa')>
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Activa</p>
                    <p class="text-xs text-gray-600 dark:text-gray-300">Permite acceso al gimnasio.</p>
                </div>
            </label>

            <label class="flex items-center gap-3 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3
                          dark:border-gray-600 dark:bg-gray-900">
                <input type="radio" name="estado" value="inactiva" required
                       @checked(old('estado', $cliente->estado ?? '') === 'inactiva')>
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Inactiva</p>
                    <p class="text-xs text-gray-600 dark:text-gray-300">No permite acceso.</p>
                </div>
            </label>
        </div>
    </div>
</div>