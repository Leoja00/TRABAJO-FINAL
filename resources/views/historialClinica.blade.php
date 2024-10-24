@extends('layouts.app')

@section('title', 'Historial clínico')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center"
    style="background-image: url('{{ asset('img/medico.jpeg') }}')">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative flex min-h-screen">

        <!-- Sidebar -->
        <div class="w-1/4 bg-gray-100 p-4">
            <h3 class="text-xl font-bold mb-4 mt-32">Historiales Clínicos</h3>
            <ul class="space-y-2">
                @foreach ($historiales as $historial)
                    <a href="#" class="block p-2 bg-cyan-600 text-white rounded hover:bg-cyan-800" onclick="mostrarHistorial({
                                       id: {{ $historial->id }},

                                       tension_arterial: '{{ $historial->tension_arterial }}',
                                       peso: '{{ $historial->peso }}',
                                       motivo_consulta: '{{ $historial->motivo_consulta }}',
                                       datos_relevantes_examen_fisico: '{{ $historial->datos_relevantes_examen_fisico }}',
                                       diagnostico: '{{ $historial->diagnostico }}',
                                       tratamiento_indicaciones: '{{ $historial->tratamiento_indicaciones }}',
                                       documentacion: '{{ $historial->documentacion }}',
                                       created_at: '{{ $historial->created_at->format('d/m/Y') }}'
                                   })">Historial del {{ $historial->created_at->format('d/m/Y') }}</a>

                @endforeach
            </ul>
            <button class="mt-4 w-full bg-green-600 text-white py-2 rounded hover:bg-green-800"
                onclick="crearNuevoHistorial()">Crear Nuevo Historial</button>
            <!--<p>ID del Profesional: {{ $profesional_id }}</p>-->

        </div>


        <!-- Contenido Principal -->
        <div class="w-3/4 p-8">
            <!-- Información del Paciente -->
            <div class="bg-white p-8 rounded shadow-md w-full mt-12">
                <h3 class="text-xl font-bold mb-4">Información del Paciente</h3>
                @if($paciente)
                    <p><strong>Nombre:</strong> {{ $paciente->user->name }}</p>
                    <p><strong>DNI:</strong> {{ $paciente->user->dni }}</p>
                    <p><strong>Teléfono:</strong> {{ $paciente->user->telefono }}</p>
                    <p><strong>Obra Social:</strong> {{ $paciente->obra_social }}</p>
                    <p><strong>Número de Afiliado:</strong> {{ $paciente->numero_afiliado }}</p>
                    <a href="{{ route('paciente.historiales.descargar', $paciente->id) }}"
                        class="flex items-center px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8l-8 8-8-8" />
                        </svg>
                        Descargar Historial Clínico Completo
                    </a>

                @elseif($pacienteNoLogueado)
                    <p><strong>Nombre:</strong> {{ $pacienteNoLogueado->name }}</p>
                    <p><strong>DNI:</strong> {{ $pacienteNoLogueado->dni }}</p>
                    <p><strong>Teléfono:</strong> {{ $pacienteNoLogueado->telefono }}</p>
                    <p><strong>Obra Social:</strong> {{ $pacienteNoLogueado->obra_social }}</p>
                    <p><strong>Número de Afiliado:</strong> {{ $pacienteNoLogueado->numero_afiliado }}</p>
                    <a href="{{ route('paciente.historiales.descargar', $pacienteNoLogueado->dni) }}"
                        class="flex items-center px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8l-8 8-8-8" />
                        </svg>
                        Descargar Historial Clínico Completo
                    </a>



                @endif
            </div>

            <!-- Formulario Único -->
            <div id="formNuevoHistorial" class="hidden mt-8">

                <form
                    action="{{ isset($historial) ? route('historial.actualizar', $historial->id) : route('historial.guardar') }}"
                    method="POST" enctype="multipart/form-data" id="historiaClinicaForm" class="w-full max-w-4xl">
                    @csrf
                    @if (isset($historial))
                        @method('PUT')
                        <input type="hidden" id="historialIdDisplay" name="historialId" value="{{ $historial->id }}">
                    @endif

                    <input type="hidden" name="paciente_id" value="{{ $paciente ? $paciente->id : '' }}">
                    <input type="hidden" name="paciente_no_logueado_id"
                        value="{{ $pacienteNoLogueado ? $pacienteNoLogueado->id : '' }}">
                    <input type="hidden" name="profesional_id" value="{{ Auth::user()->id }}">


                    <!-- Opciones -->
                    <div class="flex space-x-4 bg-transparent-70 p-4 rounded-2xl shadow-md w-full max-w-4xl border-2">
                        <button type="button"
                            class="bg-cyan-600 text-white text-lg border-2 border-cyan-600 hover:bg-sky-900 hover:border-sky-900 rounded-lg flex-1 shadow-md transition duration-300 ease-in-out"
                            onclick="mostrarSeccion('prescripcion')">
                            Prescripción
                        </button>
                        <button type="button"
                            class="bg-cyan-600 text-white text-lg border-2 border-cyan-600 hover:bg-sky-900 hover:border-sky-900 rounded-lg flex-1 shadow-md transition duration-300 ease-in-out"
                            onclick="mostrarSeccion('historia_clinica')">Historia Clínica</button>
                        <button type="button"
                            class="bg-cyan-600 text-white text-lg border-2 border-cyan-600 hover:bg-sky-900 hover:border-sky-900 rounded-lg flex-1 shadow-md transition duration-300 ease-in-out"
                            onclick="mostrarSeccion('documentacion')">Documentación</button>
                    </div>

                    <!-- Secciones -->
                    <div class="w-full max-w-4xl">
                        <!-- Prescripción -->
                        <div id="prescripcion" class="form-section hidden bg-white p-8 rounded-lg shadow-md">
                            <!-- Prescripción Form -->
                            <h3 class="text-xl font-bold mb-4">Prescripción</h3>
                            <label for="motivo_consulta" class="block text-gray-700 font-semibold mt-4">Motivo de la
                                Consulta:</label>
                            <textarea name="motivo_consulta" id="motivo_consulta"
                                class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                placeholder="Agregar motivo de consulta">{{ $historial->motivo_consulta ?? '' }}</textarea>

                            <label for="tratamiento_indicaciones"
                                class="block text-gray-700 font-semibold mt-4">Tratamiento o Indicaciones:</label>
                            <textarea name="tratamiento_indicaciones" id="tratamiento_indicaciones"
                                class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                placeholder="Agregar tratamiento o indicaciones">{{ $historial->tratamiento_indicaciones ?? '' }}</textarea>
                        </div>

                        <!-- Historia Clínica -->
                        <div id="historia_clinica" class="form-section hidden bg-white p-8 rounded-lg shadow-md">
                            <!-- Historia Clínica Form -->
                            <h3 class="text-xl font-bold mb-4">Historia Clínica</h3>
                            <label for="tension_arterial" class="block text-gray-700 font-semibold mt-4">Tensión
                                Arterial:</label>
                            <input type="text" name="tension_arterial" id="tension_arterial" placeholder="Ej. 120/80"
                                class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                value="{{ $historial->tension_arterial ?? '' }}">

                            <label for="peso" class="block text-gray-700 font-semibold mt-4">Peso:</label>
                            <input type="text" name="peso" id="peso" placeholder="Ej. 70 kg"
                                class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                value="{{ $historial->peso ?? '' }}">

                            <label for="diagnostico" class="block text-gray-700 font-semibold mt-4">Diagnóstico:</label>
                            <textarea name="diagnostico" id="diagnostico" placeholder="Escribe el diagnóstico aquí..."
                                class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none">{{ $historial->diagnostico ?? '' }}</textarea>
                        </div>

                        <!-- Documentación -->
                        <div id="documentacion" class="form-section hidden bg-white p-8 rounded-lg shadow-md">
                            <h3 class="text-xl font-bold mb-4">Documentación</h3>

                            <!-- Mostrar documentos existentes -->
                            <div id="documentosExistentes" class="mb-4">
                                <h4 class="font-semibold text-gray-700">Documentos Existentes:</h4>
                                <ul id="listaDocumentos" class="list-disc pl-5">
                                    @if(isset($historial->documentacion))
                                        @foreach(json_decode($historial->documentacion, true) as $doc)
                                            <li>
                                                <a href="/consultorio/public/{{ $doc }}" target="_blank"
                                                    class="text-blue-600 hover:underline">{{ basename($doc) }}</a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="text-gray-500">No hay documentos adjuntos.</li>
                                    @endif
                                </ul>
                            </div>

                            <!-- Opción para subir nuevos documentos -->
                            <label for="documento" class="block text-gray-700 font-semibold mt-4">Agregar Nuevos
                                Documentos:</label>
                            <input type="file" name="documento[]" id="documento"
                                class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                multiple>
                        </div>
                    </div>

                    <!-- Botón para guardar -->
                    <div class="w-full max-w-4xl mt-4 flex justify-center">
                        <button type="button" id="guardarBtn"
                            class="bg-cyan-600 hover:bg-cyan-800 text-white font-bold py-2 px-4 rounded-full mt-2 mb-12 hidden"
                            onclick="confirmarGuardar()">Guardar historia clínica</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function mostrarHistorial(historial) {
        const historialId = historial.id;
        console.log("Historial ID:", historialId); // Mostrar el ID en la consola para verificar

        document.getElementById('historialIdDisplay').value = historialId;

        // Rellenar los campos con los datos del historial
        document.getElementById('tension_arterial').value = historial.tension_arterial || '';
        document.getElementById('peso').value = historial.peso || '';
        document.getElementById('motivo_consulta').value = historial.motivo_consulta || '';
        document.getElementById('tratamiento_indicaciones').value = historial.tratamiento_indicaciones || '';
        document.getElementById('diagnostico').value = historial.diagnostico || '';

        const form = document.getElementById('historiaClinicaForm');
        form.action = "{{ url('/historial') }}/" + historialId + "/actualizar";


        form.method = 'POST';

        let methodInput = document.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);
        }

        let historialIdInput = document.querySelector('input[name="historial_id"]');
        if (!historialIdInput) {
            historialIdInput = document.createElement('input');
            historialIdInput.type = 'hidden';
            historialIdInput.name = 'historial_id';
            historialIdInput.value = historial.id;
            form.appendChild(historialIdInput);
        }

        document.getElementById('formNuevoHistorial').classList.remove('hidden');
    }


    function mostrarSeccion(id) {
        document.querySelectorAll('.form-section').forEach(section => section.classList.add('hidden'));
        document.getElementById(id).classList.remove('hidden');
    }

    function crearNuevoHistorial() {
        document.getElementById('formNuevoHistorial').classList.remove('hidden');
        document.getElementById('historiaClinicaForm').reset();
        const form = document.getElementById('historiaClinicaForm');
        form.action = "{{ route('historial.guardar') }}";

        const methodInput = document.querySelector('input[name="_method"]');
        if (methodInput) {
            methodInput.remove();
        }
        const historialIdInput = document.querySelector('input[name="historial_id"]');
        if (historialIdInput) {
            historialIdInput.remove();
        }

        document.querySelectorAll('.form-section textarea, .form-section input').forEach(input => {
            input.value = '';
        });
        document.getElementById('guardarBtn').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const formSections = document.querySelectorAll('.form-section textarea, .form-section input');
        const guardarBtn = document.getElementById('guardarBtn');

        function verificarCampos() {
            let camposCompletados = false;

            formSections.forEach(function (campo) {
                if (campo.value.trim() !== '') {
                    camposCompletados = true;
                }
            });

            if (camposCompletados) {
                guardarBtn.classList.remove('hidden');
            } else {
                guardarBtn.classList.add('hidden');
            }
        }

        formSections.forEach(function (campo) {
            campo.addEventListener('input', verificarCampos);
        });
    });

    function confirmarGuardar() {
        // Verifica si el campo 'historial_id' tiene el valor correcto
        const historialIdInput = document.querySelector('input[name="historial_id"]');
        console.log('Historial ID en el formulario:', historialIdInput ? historialIdInput.value : 'No existe el campo historial_id');

        Swal.fire({
            title: '¿Estás seguro de que deseas guardar esta historia clínica?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('historiaClinicaForm').submit();
            }
        });
    }

</script>

<!-- Menú de selección -->
<div id="seccionesMenu" class="hidden">
    <button onclick="mostrarSeccion('prescripcion')">Prescripción</button>
    <button onclick="mostrarSeccion('historia_clinica')">Historia Clínica</button>
    <button onclick="mostrarSeccion('documentacion')">Documentación</button>
</div>

@endsection