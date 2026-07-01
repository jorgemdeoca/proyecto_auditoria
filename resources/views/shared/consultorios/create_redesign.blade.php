@extends('layouts.admin')

@section('title', 'Nuevo Consultorio')

@section('content')
    <div class="min-h-[calc(100vh-8rem)] flex items-center justify-center p-4">
        <div class="w-full max-w-4xl animate-fade-in-up">

            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-2">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-medical-600 to-medical-400">
                        Registrar Consultorio
                    </span>
                </h1>
                <p class="text-gray-500 text-lg">Indica los detalles del nuevo espacio médico</p>
            </div>

            <div
                class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-hard border border-white/20 overflow-hidden relative">
                <!-- Decorative Elements -->
                <div
                    class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-medical-500 via-premium-500 to-medical-500">
                </div>
                <div
                    class="absolute -top-24 -right-24 w-64 h-64 bg-medical-500/5 rounded-full blur-3xl pointer-events-none">
                </div>
                <div
                    class="absolute -bottom-24 -left-24 w-64 h-64 bg-premium-500/5 rounded-full blur-3xl pointer-events-none">
                </div>

                <form method="POST" action="{{ route('consultorios.store') }}" id="consultorioForm"
                    class="p-8 md:p-10 relative z-10">
                    @csrf

                    <!-- Section: Información General -->
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-full bg-medical-50 flex items-center justify-center text-medical-600 shadow-sm">
                                <i class="bi bi-building text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Información General</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 space-y-2">
                                <label for="nombre" class="text-sm font-semibold text-gray-700 ml-1">Nombre del Consultorio
                                    <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i
                                            class="bi bi-card-heading text-gray-400 group-focus-within:text-medical-500 transition-colors"></i>
                                    </div>
                                    <input type="text" id="nombre" name="nombre"
                                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 transition-all placeholder-gray-400 text-gray-800 font-medium @error('nombre') border-rose-300 ring-rose-100 @enderror"
                                        placeholder="Ej: Consultorio 305 - Ala Norte" value="{{ old('nombre') }}" required>
                                </div>
                                @error('nombre')
                                    <p class="text-rose-500 text-xs ml-1 flex items-center gap-1"><i
                                            class="bi bi-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label for="descripcion"
                                    class="text-sm font-semibold text-gray-700 ml-1">Descripción</label>
                                <div class="relative group">
                                    <div class="absolute top-3 left-0 pl-4 flex items-start pointer-events-none">
                                        <i
                                            class="bi bi-text-paragraph text-gray-400 group-focus-within:text-medical-500 transition-colors"></i>
                                    </div>
                                    <textarea id="descripcion" name="descripcion" rows="3"
                                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 transition-all placeholder-gray-400 text-gray-800 resize-none"
                                        placeholder="Detalles sobre el equipamiento o uso específico...">{{ old('descripcion') }}</textarea>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="horario_inicio"
                                    class="text-sm font-semibold text-gray-700 ml-1">Apertura</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="bi bi-clock text-gray-400"></i>
                                    </div>
                                    <input type="time" id="horario_inicio" name="horario_inicio"
                                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 transition-all text-gray-800"
                                        value="{{ old('horario_inicio') }}">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="horario_fin" class="text-sm font-semibold text-gray-700 ml-1">Cierre</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="bi bi-clock-history text-gray-400"></i>
                                    </div>
                                    <input type="time" id="horario_fin" name="horario_fin"
                                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-medical-500/20 focus:border-medical-500 transition-all text-gray-800"
                                        value="{{ old('horario_fin') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full h-px bg-gray-100 my-8"></div>

                    <!-- Section: Ubicación -->
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-full bg-premium-50 flex items-center justify-center text-premium-600 shadow-sm">
                                <i class="bi bi-geo-alt text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Ubicación Física</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Selects Dependientes -->
                            <div class="space-y-2">
                                <label for="estado_id" class="text-sm font-semibold text-gray-700 ml-1">Estado <span
                                        class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select id="estado_id" name="estado_id"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-premium-500/20 focus:border-premium-500 transition-all text-gray-800 appearance-none @error('estado_id') border-rose-300 @enderror"
                                        required>
                                        <option value="">Seleccionar...</option>
                                        @foreach($estados as $estado)
                                            <option value="{{ $estado->id_estado }}" {{ old('estado_id') == $estado->id_estado ? 'selected' : '' }}>{{ $estado->estado }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="bi bi-chevron-down text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                                @error('estado_id') <p class="text-rose-500 text-xs ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="ciudad_id" class="text-sm font-semibold text-gray-700 ml-1">Ciudad <span
                                        class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select id="ciudad_id" name="ciudad_id"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-premium-500/20 focus:border-premium-500 transition-all text-gray-800 appearance-none disabled:bg-gray-100 disabled:text-gray-400"
                                        {{ old('estado_id') ? '' : 'disabled' }} required>
                                        <option value="">Seleccionar...</option>
                                        @if(isset($ciudades) && count($ciudades) > 0)
                                            @foreach($ciudades as $ciudad)
                                                <option value="{{ $ciudad->id_ciudad }}" {{ old('ciudad_id') == $ciudad->id_ciudad ? 'selected' : '' }}>{{ $ciudad->ciudad }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="bi bi-chevron-down text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                                @error('ciudad_id') <p class="text-rose-500 text-xs ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="municipio_id" class="text-sm font-semibold text-gray-700 ml-1">Municipio</label>
                                <div class="relative">
                                    <select id="municipio_id" name="municipio_id"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-premium-500/20 focus:border-premium-500 transition-all text-gray-800 appearance-none disabled:bg-gray-100 disabled:text-gray-400"
                                        {{ old('estado_id') ? '' : 'disabled' }}>
                                        <option value="">Seleccionar...</option>
                                        @if(isset($municipios) && count($municipios) > 0)
                                            @foreach($municipios as $municipio)
                                                <option value="{{ $municipio->id_municipio }}" {{ old('municipio_id') == $municipio->id_municipio ? 'selected' : '' }}>
                                                    {{ $municipio->municipio }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="bi bi-chevron-down text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="parroquia_id" class="text-sm font-semibold text-gray-700 ml-1">Parroquia</label>
                                <div class="relative">
                                    <select id="parroquia_id" name="parroquia_id"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-premium-500/20 focus:border-premium-500 transition-all text-gray-800 appearance-none disabled:bg-gray-100 disabled:text-gray-400"
                                        {{ old('municipio_id') ? '' : 'disabled' }}>
                                        <option value="">Seleccionar...</option>
                                        @if(isset($parroquias) && count($parroquias) > 0)
                                            @foreach($parroquias as $parroquia)
                                                <option value="{{ $parroquia->id_parroquia }}" {{ old('parroquia_id') == $parroquia->id_parroquia ? 'selected' : '' }}>
                                                    {{ $parroquia->parroquia }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                        <i class="bi bi-chevron-down text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label for="direccion_detallada" class="text-sm font-semibold text-gray-700 ml-1">Dirección
                                    Exacta</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i
                                            class="bi bi-pin-map text-gray-400 group-focus-within:text-premium-500 transition-colors"></i>
                                    </div>
                                    <input type="text" id="direccion_detallada" name="direccion_detallada"
                                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-premium-500/20 focus:border-premium-500 transition-all placeholder-gray-400 text-gray-800"
                                        placeholder="Av. Bolívar, Torre Médica, Nivel 2, Local 5"
                                        value="{{ old('direccion_detallada') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full h-px bg-gray-100 my-8"></div>

                    <!-- Section: Contacto y Especialidades -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                        <!-- Contacto -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 shadow-sm">
                                    <i class="bi bi-Telephone text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Contacto</h3>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label for="telefono" class="text-sm font-semibold text-gray-700 ml-1">Teléfono</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i
                                                class="bi bi-phone text-gray-400 group-focus-within:text-amber-500 transition-colors"></i>
                                        </div>
                                        <input type="tel" id="telefono" name="telefono"
                                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-gray-800"
                                            placeholder="(0414) 123-4567" value="{{ old('telefono') }}">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label for="email" class="text-sm font-semibold text-gray-700 ml-1">Correo
                                        Electrónico</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i
                                                class="bi bi-envelope text-gray-400 group-focus-within:text-amber-500 transition-colors"></i>
                                        </div>
                                        <input type="email" id="email" name="email"
                                            class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all text-gray-800 @error('email') border-rose-300 ring-rose-100 @enderror"
                                            placeholder="contacto@consultorio.com" value="{{ old('email') }}">
                                    </div>
                                    @error('email') <p class="text-rose-500 text-xs ml-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Especialidades -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600 shadow-sm">
                                    <i class="bi bi-stars text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Especialidades</h3>
                            </div>

                            <div
                                class="p-4 bg-gray-50 rounded-xl border border-gray-100 max-h-48 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300">
                                <div class="space-y-2">
                                    @foreach($especialidades as $especialidad)
                                        <label
                                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white hover:shadow-sm transition-all cursor-pointer group">
                                            <div class="relative flex items-center">
                                                <input type="checkbox" name="especialidades[]" value="{{ $especialidad->id }}"
                                                    class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 transition-all checked:border-purple-500 checked:bg-purple-500"
                                                    {{ is_array(old('especialidades')) && in_array($especialidad->id, old('especialidades')) ? 'checked' : '' }}>
                                                <i
                                                    class="bi bi-check text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-sm opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                            </div>
                                            <span
                                                class="text-sm font-medium text-gray-600 group-hover:text-purple-700 transition-colors">{{ $especialidad->nombre }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @error('especialidades')
                                <p class="text-rose-500 text-xs mt-2 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div
                        class="flex flex-col-reverse md:flex-row items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('consultorios.index') }}"
                            class="w-full md:w-auto px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 hover:border-gray-300 transition-all text-center">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="w-full md:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-medical-600 to-medical-500 text-white font-bold shadow-lg shadow-medical-500/30 hover:shadow-medical-500/40 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2">
                            <i class="bi bi-save"></i>
                            Registrar Consultorio
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Location Logic (Same logic, preserved functionality)
                const estadoSelect = document.getElementById('estado_id');
                const ciudadSelect = document.getElementById('ciudad_id');
                const municipioSelect = document.getElementById('municipio_id');
                const parroquiaSelect = document.getElementById('parroquia_id');

                estadoSelect.addEventListener('change', function () {
                    const estadoId = this.value;

                    // Reset dependent fields visually
                    [ciudadSelect, municipioSelect, parroquiaSelect].forEach(select => {
                        select.innerHTML = '<option value="">Seleccionar...</option>';
                        select.disabled = true;
                        select.parentElement.classList.add('opacity-70');
                    });

                    if (estadoId) {
                        // Fetch Ciudades
                        fetch(`{{ url('get-ciudades-consultorio') }}/${estadoId}`)
                            .then(response => response.json())
                            .then(data => {
                                ciudadSelect.innerHTML = '<option value="">Seleccionar...</option>';
                                data.forEach(ciudad => {
                                    ciudadSelect.innerHTML += `<option value="${ciudad.id_ciudad}">${ciudad.ciudad}</option>`;
                                });
                                ciudadSelect.disabled = false;
                                ciudadSelect.parentElement.classList.remove('opacity-70');

                                // Also load municipios
                                fetch(`{{ url('get-municipios-consultorio') }}/${estadoId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        municipioSelect.innerHTML = '<option value="">Seleccionar...</option>';
                                        data.forEach(municipio => {
                                            municipioSelect.innerHTML += `<option value="${municipio.id_municipio}">${municipio.municipio}</option>`;
                                        });
                                        municipioSelect.disabled = false;
                                        municipioSelect.parentElement.classList.remove('opacity-70');
                                    });
                            })
                            .catch(error => console.error('Error loading locations:', error));
                    }
                });

                municipioSelect.addEventListener('change', function () {
                    const municipioId = this.value;
                    parroquiaSelect.innerHTML = '<option value="">Cargando...</option>';
                    parroquiaSelect.disabled = true;

                    if (municipioId) {
                        fetch(`{{ url('get-parroquias-consultorio') }}/${municipioId}`)
                            .then(response => response.json())
                            .then(data => {
                                parroquiaSelect.innerHTML = '<option value="">Seleccionar...</option>';
                                data.forEach(parroquia => {
                                    parroquiaSelect.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.parroquia}</option>`;
                                });
                                parroquiaSelect.disabled = false;
                                parroquiaSelect.parentElement.classList.remove('opacity-70');
                            })
                            .catch(error => console.error('Error loading parroquias:', error));
                    } else {
                        parroquiaSelect.innerHTML = '<option value="">Seleccionar...</option>';
                    }
                });
            });
        </script>
    @endpush
@endsection