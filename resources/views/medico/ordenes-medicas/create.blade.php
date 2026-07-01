@extends('layouts.medico')

@section('title', 'Nueva Orden Médica')

@section('content')
<div x-data="ordenMedicaForm()" class="space-y-6">
    <!-- Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed top-20 right-6 z-50">
        <div class="flex items-center gap-3 px-6 py-4 rounded-xl shadow-2xl border-l-4"
             :class="{
                 'bg-green-50 border-green-500 text-green-800': toastType === 'success',
                 'bg-blue-50 border-blue-500 text-blue-800': toastType === 'info'
             }">
            <div class="flex-shrink-0">
                <i class="bi text-2xl" :class="{
                    'bi-check-circle-fill text-green-500': toastType === 'success',
                    'bi-info-circle-fill text-blue-500': toastType === 'info'
                }"></i>
            </div>
            <div>
                <p class="font-semibold" x-text="toastTitle"></p>
                <p class="text-sm opacity-80" x-text="toastMessage"></p>
            </div>
            <button @click="showToast = false" class="ml-4 text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('ordenes-medicas.index') }}" class="btn btn-outline">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-display font-bold text-gray-900">Nueva Orden Médica</h1>
            <p class="text-gray-600 mt-1">Crear receta, orden de laboratorio, imagenología o referencia</p>
        </div>
    </div>

    <form action="{{ route('ordenes-medicas.store-con-items') }}" method="POST" class="space-y-6" id="ordenForm" @submit="prepararEnvio">
        @csrf
        
        <!-- Campo oculto para las órdenes confirmadas -->
        <input type="hidden" name="ordenes_json" :value="JSON.stringify(ordenesConfirmadas)">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tipo de Orden -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-ui-checks text-medical-600"></i>
                        Tipo de Orden
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="card card-hover p-4 cursor-pointer transition-all duration-200"
                               :class="tipoOrden === 'Receta' ? 'ring-2 ring-green-500 bg-green-50' : ''">
                            <input type="radio" name="tipo_orden_select" value="Receta" class="sr-only" 
                                   x-model="tipoOrden">
                            <div class="text-center">
                                <i class="bi bi-capsule text-3xl text-green-600 mb-2"></i>
                                <p class="font-semibold text-gray-900">Receta</p>
                                <p class="text-xs text-gray-500 mt-1">Medicamentos</p>
                            </div>
                        </label>

                        <label class="card card-hover p-4 cursor-pointer transition-all duration-200"
                               :class="tipoOrden === 'Laboratorio' ? 'ring-2 ring-blue-500 bg-blue-50' : ''">
                            <input type="radio" name="tipo_orden_select" value="Laboratorio" class="sr-only" 
                                   x-model="tipoOrden">
                            <div class="text-center">
                                <i class="bi bi-droplet text-3xl text-blue-600 mb-2"></i>
                                <p class="font-semibold text-gray-900">Laboratorio</p>
                                <p class="text-xs text-gray-500 mt-1">Exámenes</p>
                            </div>
                        </label>

                        <label class="card card-hover p-4 cursor-pointer transition-all duration-200"
                               :class="tipoOrden === 'Imagenologia' ? 'ring-2 ring-orange-500 bg-orange-50' : ''">
                            <input type="radio" name="tipo_orden_select" value="Imagenologia" class="sr-only" 
                                   x-model="tipoOrden">
                            <div class="text-center">
                                <i class="bi bi-x-ray text-3xl text-orange-600 mb-2"></i>
                                <p class="font-semibold text-gray-900">Imagenología</p>
                                <p class="text-xs text-gray-500 mt-1">Estudios</p>
                            </div>
                        </label>

                        <label class="card card-hover p-4 cursor-pointer transition-all duration-200"
                               :class="tipoOrden === 'Referencia' ? 'ring-2 ring-purple-500 bg-purple-50' : ''">
                            <input type="radio" name="tipo_orden_select" value="Referencia" class="sr-only" 
                                   x-model="tipoOrden">
                            <div class="text-center">
                                <i class="bi bi-person-badge text-3xl text-purple-600 mb-2"></i>
                                <p class="font-semibold text-gray-900">Referencia</p>
                                <p class="text-xs text-gray-500 mt-1">Especialista</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Patient Selection -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-person-circle text-blue-600"></i>
                        Información del Paciente
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="form-label form-label-required">Paciente</label>
                            <select name="seleccion_paciente" class="form-select" required>
                                <option value="">Seleccionar paciente...</option>
                                
                                {{-- Pacientes Regulares --}}
                                @if(isset($pacientes) && count($pacientes) > 0)
                                    @foreach($pacientes as $paciente)
                                        <option value="REGULAR_{{ $paciente->id }}" {{ request('paciente') == $paciente->id ? 'selected' : '' }}>
                                            {{ $paciente->primer_nombre }} {{ $paciente->primer_apellido }} - {{ $paciente->tipo_documento }}-{{ $paciente->numero_documento }}
                                        </option>
                                    @endforeach
                                @endif

                                {{-- Pacientes Especiales --}}
                                @if(isset($pacientesEspeciales) && count($pacientesEspeciales) > 0)
                                    @foreach($pacientesEspeciales as $pe)
                                        @php
                                            $rep = $pe->representantes->first();
                                            $nombreRep = $rep ? $rep->primer_nombre . ' ' . $rep->primer_apellido : 'S/R';
                                            
                                            // Mostrar documento propio o del representante si no tiene
                                            if ($pe->tiene_documento) {
                                                $docInfo = $pe->tipo_documento . '-' . $pe->numero_documento;
                                            } else {
                                                $docInfo = $rep ? ($rep->tipo_documento . '-' . $rep->numero_documento) : 'S/D';
                                            }
                                        @endphp
                                        <option value="SPECIAL_{{ $pe->id }}">
                                            {{ $pe->primer_nombre }} {{ $pe->primer_apellido }} (Rep: {{ $nombreRep }}) - {{ $docInfo }}
                                        </option>
                                    @endforeach
                                @endif
                                
                                @if((!isset($pacientes) || count($pacientes) == 0) && (!isset($pacientesEspeciales) || count($pacientesEspeciales) == 0))
                                    <option value="" disabled>No se encontraron pacientes con historial de citas.</option>
                                @endif
                            </select>
                        </div>
                        
                        @if(request('cita') || request('cita_id'))
                        <input type="hidden" name="cita_id" value="{{ request('cita_id') ?? request('cita') }}">
                        <div class="md:col-span-2">
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <p class="text-sm font-semibold text-blue-900">
                                    <i class="bi bi-info-circle"></i> Orden asociada a cita médica
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Diagnóstico e Indicaciones Generales - OCULTO: Se maneja en la evolución clínica -->
                <!-- Campos hidden para mantener compatibilidad -->
                <input type="hidden" name="diagnostico_principal" value="">
                <input type="hidden" name="indicaciones" value="">

                <!-- ============================================================ -->
                <!-- RECETA - Medicamentos -->
                <!-- ============================================================ -->
                <div x-show="tipoOrden === 'Receta'" x-cloak class="card p-6 border-2 border-green-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-capsule text-green-600"></i>
                            Nuevo Medicamento
                        </h3>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="md:col-span-2">
                                <label class="form-label form-label-required">Medicamento</label>
                                <input type="text" x-model="formMedicamento.medicamento" 
                                       class="form-input" placeholder="Nombre del medicamento">
                            </div>
                            <div>
                                <label class="form-label">Presentación</label>
                                <div class="flex gap-2">
                                    <input type="number" x-model="formMedicamento.presentacion_valor" 
                                           class="form-input w-2/3" placeholder="Ej: 500" min="1">
                                    <select x-model="formMedicamento.presentacion_unidad" class="form-select w-1/3">
                                        <option value="mg">mg</option>
                                        <option value="g">g</option>
                                        <option value="ml">ml</option>
                                        <option value="mcg">mcg</option>
                                        <option value="UI">UI</option>
                                        <option value="cc">cc</option>
                                        <option value="%">%</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Cantidad (Unidades)</label>
                                <input type="number" x-model="formMedicamento.cantidad" 
                                       class="form-input" placeholder="Ej: 1" min="1">
                            </div>
                            <div>
                                <label class="form-label">Dosis (Frecuencia)</label>
                                <select x-model="formMedicamento.dosis" class="form-select">
                                    <option value="">Seleccionar frecuencia...</option>
                                    <template x-for="i in 24" :key="i">
                                        <option :value="'c/'+i+'h'" x-text="'Cada '+i+' horas ('+'c/'+i+'h)'"></option>
                                    </template>
                                    <option value="Interdiario">Interdiario (Un día sí, un día no)</option>
                                    <option value="c/48h">Cada 48 horas</option>
                                    <option value="c/72h">Cada 72 horas</option>
                                    <option value="Orden Diaria">Orden Diaria</option>
                                    <option value="Stat">Stat (Inmediato)</option>
                                    <option value="SOS">S.O.S (Si es necesario)</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Vía Administración</label>
                                <select x-model="formMedicamento.via_administracion" class="form-select">
                                    <option value="Oral">Oral</option>
                                    <option value="Intravenosa">Intravenosa</option>
                                    <option value="Intramuscular">Intramuscular</option>
                                    <option value="Subcutanea">Subcutánea</option>
                                    <option value="Topica">Tópica</option>
                                    <option value="Inhalada">Inhalada</option>
                                    <option value="Sublingual">Sublingual</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Duración (días)</label>
                                <input type="number" x-model="formMedicamento.duracion_dias" 
                                       class="form-input" placeholder="Días" min="1">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label">Indicaciones</label>
                                <textarea x-model="formMedicamento.indicaciones" 
                                          class="form-textarea" rows="2" 
                                          placeholder="Indicaciones específicas..."></textarea>
                            </div>
                        </div>
                        
                        <!-- Botón Confirmar -->
                        <div class="pt-4 border-t border-green-100">
                            <button type="button" @click="confirmarMedicamento()" 
                                    class="btn bg-blue-600 hover:bg-blue-700 text-white w-full">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Confirmar y Agregar Medicamento
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ============================================================ -->
                <!-- LABORATORIO - Exámenes -->
                <!-- ============================================================ -->
                <div x-show="tipoOrden === 'Laboratorio'" x-cloak class="card p-6 border-2 border-blue-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-droplet text-blue-600"></i>
                            Nuevo Examen de Laboratorio
                        </h3>
                    </div>

                    <!-- Exámenes Rápidos -->
                    <div class="mb-4 p-4 bg-blue-50 rounded-xl">
                        <p class="text-sm font-medium text-blue-800 mb-2">Exámenes Frecuentes (clic para agregar):</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="agregarExamenRapido('Hematológico', 'Hemograma Completo')" class="badge badge-outline hover:bg-blue-100 cursor-pointer">Hemograma</button>
                            <button type="button" @click="agregarExamenRapido('Bioquímica', 'Glicemia')" class="badge badge-outline hover:bg-blue-100 cursor-pointer">Glicemia</button>
                            <button type="button" @click="agregarExamenRapido('Bioquímica', 'Perfil Lipídico')" class="badge badge-outline hover:bg-blue-100 cursor-pointer">Perfil Lipídico</button>
                            <button type="button" @click="agregarExamenRapido('Bioquímica', 'Creatinina')" class="badge badge-outline hover:bg-blue-100 cursor-pointer">Creatinina</button>
                            <button type="button" @click="agregarExamenRapido('Orina', 'Examen de Orina')" class="badge badge-outline hover:bg-blue-100 cursor-pointer">Orina</button>
                            <button type="button" @click="agregarExamenRapido('Heces', 'Examen de Heces')" class="badge badge-outline hover:bg-blue-100 cursor-pointer">Heces</button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="form-label">Tipo de Examen</label>
                                <select x-model="formExamen.tipo_examen" class="form-select">
                                    <option value="Hematológico">Hematológico</option>
                                    <option value="Bioquímica">Bioquímica</option>
                                    <option value="Orina">Orina</option>
                                    <option value="Heces">Heces</option>
                                    <option value="Serología">Serología</option>
                                    <option value="Hormonal">Hormonal</option>
                                    <option value="Microbiología">Microbiología</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label form-label-required">Nombre del Examen</label>
                                <input type="text" x-model="formExamen.nombre_examen" 
                                       class="form-input" placeholder="Nombre del examen">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label">Indicación Clínica</label>
                                <input type="text" x-model="formExamen.indicacion_clinica" 
                                       class="form-input" placeholder="Indicación clínica">
                            </div>
                            <div class="flex items-center">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" x-model="formExamen.urgente" class="form-checkbox">
                                    <span class="text-sm text-red-600 font-medium">Urgente</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Botón Confirmar -->
                        <div class="pt-4 border-t border-blue-100">
                            <button type="button" @click="confirmarExamen()" 
                                    class="btn bg-blue-600 hover:bg-blue-700 text-white w-full">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Confirmar y Agregar Examen
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ============================================================ -->
                <!-- IMAGENOLOGÍA - Estudios -->
                <!-- ============================================================ -->
                <div x-show="tipoOrden === 'Imagenologia'" x-cloak class="card p-6 border-2 border-orange-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-x-ray text-orange-600"></i>
                            Nuevo Estudio de Imagenología
                        </h3>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="form-label form-label-required">Tipo de Estudio</label>
                                <select x-model="formImagen.tipo_estudio" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="Rayos X">Radiografía</option>
                                    <option value="Ecografia">Ecografía</option>
                                    <option value="TAC">Tomografía (TAC)</option>
                                    <option value="Resonancia Magnetica">Resonancia Magnética</option>
                                    <option value="Mamografia">Mamografía</option>
                                    <option value="Densitometria">Densitometría Ósea</option>
                                    <option value="Electrocardiograma">Electrocardiograma</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label form-label-required">Región Anatómica</label>
                                <input type="text" x-model="formImagen.region_anatomica" 
                                       class="form-input" placeholder="Ej: Tórax, Abdomen...">
                            </div>
                            <div>
                                <label class="form-label">Proyecciones</label>
                                <input type="text" x-model="formImagen.proyecciones" 
                                       class="form-input" placeholder="AP, lateral...">
                            </div>
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" x-model="formImagen.contraste" class="form-checkbox">
                                    <span class="text-sm font-medium">Con contraste</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" x-model="formImagen.urgente" class="form-checkbox">
                                    <span class="text-sm text-red-600 font-medium">Urgente</span>
                                </label>
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label">Indicación Clínica</label>
                                <textarea x-model="formImagen.indicacion_clinica" 
                                          class="form-textarea" rows="2" 
                                          placeholder="Motivo del estudio..."></textarea>
                            </div>
                        </div>
                        
                        <!-- Botón Confirmar -->
                        <div class="pt-4 border-t border-orange-100">
                            <button type="button" @click="confirmarImagen()" 
                                    class="btn bg-blue-600 hover:bg-blue-700 text-white w-full">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Confirmar y Agregar Estudio
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ============================================================ -->
                <!-- REFERENCIA - Referencias -->
                <!-- ============================================================ -->
                <div x-show="tipoOrden === 'Referencia'" x-cloak class="card p-6 border-2 border-purple-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-display font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-person-badge text-purple-600"></i>
                            Nueva Referencia a Especialista
                        </h3>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="form-label form-label-required">Especialidad</label>
                                <select x-model="formReferencia.especialidad_destino" class="form-select">
                                    <option value="">Seleccionar...</option>
                                    <option value="Cardiología">Cardiología</option>
                                    <option value="Neurología">Neurología</option>
                                    <option value="Traumatología">Traumatología</option>
                                    <option value="Gastroenterología">Gastroenterología</option>
                                    <option value="Dermatología">Dermatología</option>
                                    <option value="Psiquiatría">Psiquiatría</option>
                                    <option value="Oftalmología">Oftalmología</option>
                                    <option value="Ginecología">Ginecología</option>
                                    <option value="Urología">Urología</option>
                                    <option value="Endocrinología">Endocrinología</option>
                                    <option value="Neumología">Neumología</option>
                                    <option value="Otra">Otra</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Prioridad</label>
                                <select x-model="formReferencia.prioridad" class="form-select">
                                    <option value="Normal">Normal</option>
                                    <option value="Preferente">Preferente</option>
                                    <option value="Urgente">Urgente</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label form-label-required">Motivo de Referencia</label>
                                <textarea x-model="formReferencia.motivo_referencia" 
                                          class="form-textarea" rows="2" 
                                          placeholder="Motivo de la referencia..."></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label form-label-required">Resumen Clínico</label>
                                <textarea x-model="formReferencia.resumen_clinico" 
                                          class="form-textarea" rows="3" 
                                          placeholder="Resumen clínico del paciente..."></textarea>
                            </div>
                        </div>
                        
                        <!-- Botón Confirmar -->
                        <div class="pt-4 border-t border-purple-100">
                            <button type="button" @click="confirmarReferencia()" 
                                    class="btn bg-blue-600 hover:bg-blue-700 text-white w-full">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Confirmar y Agregar Referencia
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="card p-6 sticky top-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">Acciones</h3>
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-success w-full" 
                                :disabled="ordenesConfirmadas.length === 0"
                                :class="ordenesConfirmadas.length === 0 ? 'opacity-50 cursor-not-allowed' : ''">
                            <i class="bi bi-check-lg"></i>
                            Crear Orden (<span x-text="ordenesConfirmadas.length"></span> items)
                        </button>
                        <a href="{{ route('ordenes-medicas.index') }}" class="btn btn-outline w-full">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- Help -->
                <div class="card p-6">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4">
                        <i class="bi bi-info-circle text-blue-600"></i> Guía Rápida
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex gap-2">
                            <i class="bi bi-1-circle text-medical-600 mt-0.5"></i>
                            <p class="text-gray-700">Selecciona el tipo de orden</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-2-circle text-medical-600 mt-0.5"></i>
                            <p class="text-gray-700">Completa los campos</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-3-circle text-medical-600 mt-0.5"></i>
                            <p class="text-gray-700">Presiona "Confirmar" para agregar</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-4-circle text-medical-600 mt-0.5"></i>
                            <p class="text-gray-700">Repite para más items</p>
                        </div>
                        <div class="flex gap-2">
                            <i class="bi bi-5-circle text-medical-600 mt-0.5"></i>
                            <p class="text-gray-700">Presiona "Crear Orden" al finalizar</p>
                        </div>
                    </div>
                </div>

                <!-- Órdenes Confirmadas (Pendientes de Guardar) -->
                <div class="card p-6" x-show="ordenesConfirmadas.length > 0">
                    <h3 class="text-lg font-display font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-clipboard-check text-medical-600"></i>
                        Órdenes Pendientes
                        <span class="badge badge-success" x-text="ordenesConfirmadas.length"></span>
                    </h3>
                    
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <template x-for="(orden, idx) in ordenesConfirmadas" :key="idx">
                            <div class="p-3 rounded-lg border relative"
                                 :class="{
                                     'bg-green-50 border-green-200': orden.tipo === 'Receta',
                                     'bg-blue-50 border-blue-200': orden.tipo === 'Laboratorio',
                                     'bg-orange-50 border-orange-200': orden.tipo === 'Imagenologia',
                                     'bg-purple-50 border-purple-200': orden.tipo === 'Referencia'
                                 }">
                                <!-- Botón eliminar -->
                                <button type="button" @click="eliminarOrdenConfirmada(idx)" 
                                        class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                
                                <!-- Título del tipo -->
                                <p class="text-xs font-bold uppercase tracking-wider mb-1"
                                   :class="{
                                       'text-green-700': orden.tipo === 'Receta',
                                       'text-blue-700': orden.tipo === 'Laboratorio',
                                       'text-orange-700': orden.tipo === 'Imagenologia',
                                       'text-purple-700': orden.tipo === 'Referencia'
                                   }">
                                    <i class="bi" :class="{
                                        'bi-capsule': orden.tipo === 'Receta',
                                        'bi-droplet': orden.tipo === 'Laboratorio',
                                        'bi-x-ray': orden.tipo === 'Imagenologia',
                                        'bi-person-badge': orden.tipo === 'Referencia'
                                    }"></i>
                                    <span x-text="orden.tipo === 'Receta' ? 'Medicamento' : (orden.tipo === 'Laboratorio' ? 'Examen' : (orden.tipo === 'Imagenologia' ? 'Estudio' : 'Referencia'))"></span>
                                </p>
                                
                                <!-- Contenido según tipo -->
                                <template x-if="orden.tipo === 'Receta'">
                                    <div class="text-sm text-gray-800">
                                        <p class="font-semibold" x-text="orden.data.medicamento"></p>
                                        <p class="text-xs text-gray-600">
                                            <span x-text="orden.data.presentacion || ''"></span>
                                            <span x-show="orden.data.via_administracion">, <span x-text="orden.data.via_administracion"></span></span>
                                            <span x-show="orden.data.duracion_dias">, <span x-text="orden.data.duracion_dias"></span> días</span>
                                        </p>
                                    </div>
                                </template>
                                
                                <template x-if="orden.tipo === 'Laboratorio'">
                                    <div class="text-sm text-gray-800">
                                        <p class="font-semibold" x-text="orden.data.nombre_examen"></p>
                                        <p class="text-xs text-gray-600">
                                            <span x-text="orden.data.tipo_examen"></span>
                                            <span x-show="orden.data.urgente" class="text-red-600 font-medium ml-1">• Urgente</span>
                                        </p>
                                    </div>
                                </template>
                                
                                <template x-if="orden.tipo === 'Imagenologia'">
                                    <div class="text-sm text-gray-800">
                                        <p class="font-semibold" x-text="orden.data.tipo_estudio"></p>
                                        <p class="text-xs text-gray-600">
                                            <span x-text="orden.data.region_anatomica"></span>
                                            <span x-show="orden.data.contraste" class="ml-1">• Con contraste</span>
                                            <span x-show="orden.data.urgente" class="text-red-600 font-medium ml-1">• Urgente</span>
                                        </p>
                                    </div>
                                </template>
                                
                                <template x-if="orden.tipo === 'Referencia'">
                                    <div class="text-sm text-gray-800">
                                        <p class="font-semibold" x-text="orden.data.especialidad_destino"></p>
                                        <p class="text-xs text-gray-600">
                                            <span x-text="orden.data.prioridad"></span>
                                        </p>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                    
                    <!-- Botón limpiar todo -->
                    <div class="mt-4 pt-3 border-t border-gray-200" x-show="ordenesConfirmadas.length > 1">
                        <button type="button" @click="limpiarTodo()" 
                                class="btn btn-sm btn-outline text-red-600 border-red-300 w-full">
                            <i class="bi bi-trash mr-1"></i> Limpiar Todo
                        </button>
                    </div>
                </div>
                
                <!-- Mensaje cuando no hay órdenes -->
                <div class="card p-6 bg-gray-50" x-show="ordenesConfirmadas.length === 0">
                    <div class="text-center text-gray-500">
                        <i class="bi bi-inbox text-3xl mb-2"></i>
                        <p class="text-sm">No hay órdenes confirmadas</p>
                        <p class="text-xs mt-1">Completa un formulario y presiona "Confirmar"</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function ordenMedicaForm() {
    return {
        tipoOrden: '{{ request('tipo_orden', 'Receta') }}',
        
        // Sistema de notificaciones toast
        showToast: false,
        toastType: 'success',
        toastTitle: '',
        toastMessage: '',
        
        // Método para mostrar toast
        mostrarToast(titulo, mensaje, tipo = 'success') {
            this.toastTitle = titulo;
            this.toastMessage = mensaje;
            this.toastType = tipo;
            this.showToast = true;
            
            // Auto-ocultar después de 3 segundos
            setTimeout(() => {
                this.showToast = false;
            }, 3000);
        },
        
        // Array de órdenes confirmadas (pendientes de guardar)
        ordenesConfirmadas: [],
        
        // Formularios individuales
        formMedicamento: {
            medicamento: '',
            presentacion_valor: '',
            presentacion_unidad: 'mg',
            cantidad: 1,
            dosis: 'c/8h',
            via_administracion: 'Oral',
            duracion_dias: '',
            indicaciones: ''
        },
        
        formExamen: {
            tipo_examen: 'Hematológico',
            nombre_examen: '',
            indicacion_clinica: '',
            urgente: false
        },
        
        formImagen: {
            tipo_estudio: '',
            region_anatomica: '',
            proyecciones: '',
            contraste: false,
            urgente: false,
            indicacion_clinica: ''
        },
        
        formReferencia: {
            especialidad_destino: '',
            motivo_referencia: '',
            resumen_clinico: '',
            prioridad: 'Normal'
        },
        
        // Confirmar medicamento
        confirmarMedicamento() {
            if (!this.formMedicamento.medicamento.trim()) {
                alert('Por favor ingrese el nombre del medicamento');
                return;
            }
            
            // Combinar presentación
            let presFinal = '';
            if (this.formMedicamento.presentacion_valor) {
                presFinal = this.formMedicamento.presentacion_valor + this.formMedicamento.presentacion_unidad;
            }

            // Crear objeto de datos con presentación combinada
            let dataOrden = { ...this.formMedicamento };
            dataOrden.presentacion = presFinal;
            
            this.ordenesConfirmadas.push({
                tipo: 'Receta',
                data: dataOrden
            });
            
            // Mostrar mensaje de éxito
            this.mostrarToast('¡Medicamento Agregado!', this.formMedicamento.medicamento + ' se añadió a la orden', 'success');
            
            // Limpiar formulario
            this.formMedicamento = {
                medicamento: '',
                presentacion_valor: '',
                presentacion_unidad: 'mg',
                cantidad: 1,
                dosis: 'c/8h',
                via_administracion: 'Oral',
                duracion_dias: '',
                indicaciones: ''
            };
        },
        
        // Confirmar examen
        confirmarExamen() {
            if (!this.formExamen.nombre_examen.trim()) {
                alert('Por favor ingrese el nombre del examen');
                return;
            }
            
            const nombreExamen = this.formExamen.nombre_examen;
            
            this.ordenesConfirmadas.push({
                tipo: 'Laboratorio',
                data: { ...this.formExamen }
            });
            
            // Mostrar mensaje de éxito
            this.mostrarToast('¡Examen Agregado!', nombreExamen + ' se añadió a la orden', 'success');
            
            // Limpiar formulario
            this.formExamen = {
                tipo_examen: 'Hematológico',
                nombre_examen: '',
                indicacion_clinica: '',
                urgente: false
            };
        },
        
        // Agregar examen rápido
        agregarExamenRapido(tipo, nombre) {
            this.ordenesConfirmadas.push({
                tipo: 'Laboratorio',
                data: {
                    tipo_examen: tipo,
                    nombre_examen: nombre,
                    indicacion_clinica: '',
                    urgente: false
                }
            });
            
            // Mostrar mensaje de éxito
            this.mostrarToast('¡Examen Agregado!', nombre + ' se añadió a la orden', 'success');
        },
        
        // Confirmar imagen
        confirmarImagen() {
            if (!this.formImagen.tipo_estudio || !this.formImagen.region_anatomica.trim()) {
                alert('Por favor complete el tipo de estudio y la región anatómica');
                return;
            }
            
            const tipoEstudio = this.formImagen.tipo_estudio;
            const region = this.formImagen.region_anatomica;
            
            this.ordenesConfirmadas.push({
                tipo: 'Imagenologia',
                data: { ...this.formImagen }
            });
            
            // Mostrar mensaje de éxito
            this.mostrarToast('¡Estudio Agregado!', tipoEstudio + ' de ' + region + ' se añadió a la orden', 'success');
            
            // Limpiar formulario
            this.formImagen = {
                tipo_estudio: '',
                region_anatomica: '',
                proyecciones: '',
                contraste: false,
                urgente: false,
                indicacion_clinica: ''
            };
        },
        
        // Confirmar referencia
        confirmarReferencia() {
            if (!this.formReferencia.especialidad_destino || !this.formReferencia.motivo_referencia.trim()) {
                alert('Por favor complete la especialidad y el motivo de referencia');
                return;
            }
            
            const especialidad = this.formReferencia.especialidad_destino;
            
            this.ordenesConfirmadas.push({
                tipo: 'Referencia',
                data: { ...this.formReferencia }
            });
            
            // Mostrar mensaje de éxito
            this.mostrarToast('¡Referencia Agregada!', 'Referencia a ' + especialidad + ' se añadió a la orden', 'success');
            
            // Limpiar formulario
            this.formReferencia = {
                especialidad_destino: '',
                motivo_referencia: '',
                resumen_clinico: '',
                prioridad: 'Normal'
            };
        },
        
        // Eliminar orden confirmada
        eliminarOrdenConfirmada(index) {
            this.ordenesConfirmadas.splice(index, 1);
        },
        
        // Limpiar todo
        limpiarTodo() {
            if (confirm('¿Está seguro de eliminar todas las órdenes pendientes?')) {
                this.ordenesConfirmadas = [];
            }
        },
        
        // Preparar envío del formulario
        prepararEnvio(event) {
            if (this.ordenesConfirmadas.length === 0) {
                event.preventDefault();
                alert('No hay órdenes confirmadas para guardar. Por favor agregue al menos una orden.');
                return false;
            }
            return true;
        }
    }
}
</script>
@endpush
@endsection
