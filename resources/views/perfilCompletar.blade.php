@extends('layouts.app')

@section('title', 'Perfil')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center overflow-x-hidden"
    style="background-image: url('{{ asset('img/perfil.png') }}')">
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <div class="relative z-10 w-full px-4 py-8">
        <div class="flex items-center justify-center mt-32">
            <div
                class="relative bg-clip-border rounded-xl bg-white bg-opacity-80 text-gray-700 shadow-md w-full sm:max-w-[68rem] gap-8">
                <div id="perfil" class="w-full rounded-lg shadow-2xl bg-white opacity-75 mx-auto lg:mx-0">
                    <div class="p-4 md:p-12 text-center lg:text-left">
                        <h1 class="text-3xl font-bold pt-8 lg:pt-0">{{ Auth::user()->name }}</h1>
                        <div class="mx-auto lg:mx-0 w-4/5 pt-3 border-b-2 border-green-500 opacity-25"></div>

                        @php
                        $missingFields = [];
                        $formData = [];

                        // Verificar campos que faltan según el rol
                        if (Auth::user()->role === 'profesional') {
                        if (is_null(Auth::user()->profesional->especialidad)) {
                        $missingFields['especialidad'] = '';
                        }
                        if (is_null(Auth::user()->profesional->matricula)) {
                        $missingFields['matricula'] = '';
                        }
                        if (is_null(Auth::user()->profesional->imagen)) {
                        $missingFields['imagen'] = '';
                        }
                        }

                        if (Auth::user()->role === 'paciente') {
                        if (is_null(Auth::user()->paciente->obra_social)) {
                        $missingFields['obra_social'] = '';
                        }
                        if (is_null(Auth::user()->paciente->numero_afiliado)) {
                        $missingFields['numero_afiliado'] = '';
                        }
                        }

                        // Verificar campos que faltan para el usuario
                        if (is_null(Auth::user()->telefono)) {
                        $missingFields['telefono'] = '';
                        }
                        if (is_null(Auth::user()->fechaNacimiento)) {
                        $missingFields['fechaNacimiento'] = '';
                        }
                        if (is_null(Auth::user()->dni)) {
                        $missingFields['dni'] = '';
                        }
                        if (is_null(Auth::user()->direccion)) {
                        $missingFields['direccion'] = '';
                        }
                        @endphp

                        @if ($missingFields)
                        <p class="text-red-500 pt-4">Completar los siguientes campos:</p>
                        <form action="{{ route('perfil.actualizar') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Campos para completar -->
                            @foreach ($missingFields as $field => $value)
                            @switch($field)
                            @case('telefono')
                            <div class="mb-4">
                                <label for="telefono">Teléfono:</label>
                                <input type="text" name="telefono" id="telefono" class="border rounded w-full py-2 px-3"
                                    value="{{ old('telefono', Auth::user()->telefono) }}" pattern="[0-9]{6,10}"
                                    title="Solo números, entre 6 y 10 caracteres">
                            </div>
                            @break

                            @case('fechaNacimiento')
                            <div class="mb-4">
                                <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                                <input type="date" name="fechaNacimiento" id="fechaNacimiento"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('fechaNacimiento', Auth::user()->fechaNacimiento) }}"
                                    max="{{ date('Y-m-d') }}">
                            </div>
                            @break

                            @case('dni')
                            <div class="mb-4">
                                <label for="dni">DNI:</label>
                                <input type="text" name="dni" id="dni" class="border rounded w-full py-2 px-3"
                                    value="{{ old('dni', Auth::user()->dni) }}" pattern="[0-9]{7,9}"
                                    title="Solo números, entre 7 y 9 caracteres">
                            </div>
                            @break

                            @case('direccion')
                            <div class="mb-4">
                                <label for="direccion">Dirección:</label>
                                <input type="text" name="direccion" id="direccion"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('direccion', Auth::user()->direccion) }}" minlength="5" maxlength="40"
                                    title="Entre 5 y 40 caracteres">
                            </div>
                            @break

                            @case('especialidad')
                            <div class="mb-4">
                                <label for="especialidad">Especialidad:</label>
                                <input type="text" name="especialidad" id="especialidad"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('especialidad', Auth::user()->profesional->especialidad) }}">
                            </div>
                            @break

                            @case('matricula')
                            <div class="mb-4">
                                <label for="matricula">Matrícula:</label>
                                <input type="text" name="matricula" id="matricula"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('matricula', Auth::user()->profesional->matricula) }}">
                            </div>
                            @break
                            @case('imagen')
                            <div class="mb-4">
                                <label for="imagen">Imagen:</label>
                                <input type="file" name="imagen" id="imagen" class="border rounded w-full py-2 px-3"
                                    accept="image/*">
                            </div>
                            @break


                            @case('obra_social')
                            <div class="mb-4">
                                <label for="obra_social">Obra Social:</label>
                                <input list="obras_sociales" name="obra_social" id="obra_social"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('obra_social', Auth::user()->paciente->obra_social ? Auth::user()->paciente->obra_social->nombre : '') }}"
                                    onchange="checkPrepaga()">

                                <datalist id="obras_sociales">
                                    @foreach($obrasSociales as $obra)
                                    <option value="{{ $obra->nombre }}"></option>
                                    @endforeach
                                </datalist>
                            </div>

                            @break

                            @case('numero_afiliado')
                            <div class="mb-4" id="numero_afiliado_div"
                                style="display: {{ old('obra_social', Auth::user()->paciente->obra_social) == 'SIN OBRA SOCIAL' ? 'none' : 'block' }}">
                                <label for="numero_afiliado">Número de Afiliado:</label>
                                <input type="text" name="numero_afiliado" id="numero_afiliado"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('numero_afiliado', Auth::user()->paciente->numero_afiliado) }}"
                                    pattern="[0-9]{8,10}" title="Solo números, entre 8 y 10 caracteres"
                                    {{ Auth::user()->paciente->obra_social == 'SIN OBRA SOCIAL' ? 'disabled' : '' }}>
                            </div>


                            @break

                            @endswitch
                            @endforeach

                            <button type="submit"
                                class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded-full">
                                Guardar cambios
                            </button>
                        </form>

                        @else
                        <p class="text-green-500 pt-4">Todos los campos están completos.</p>
                        <div class="pt-12 pb-8">
                            <a href="#"
                                class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded-full">
                                Editar Perfil
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function checkPrepaga() {
    const obraSocialInput = document.getElementById('obra_social');
    const numeroAfiliadoDiv = document.getElementById('numero_afiliado_div');
    const numeroAfiliadoInput = document.getElementById('numero_afiliado');

    // Verificar si se selecciona "SIN PREPAGA"
    if (obraSocialInput.value === 'SIN OBRA SOCIAL') {
        numeroAfiliadoDiv.style.display = 'none'; // Ocultar el campo
        numeroAfiliadoInput.value = ''; // Limpiar el valor
        numeroAfiliadoInput.disabled = true; // Deshabilitar el campo
    } else {
        numeroAfiliadoDiv.style.display = 'block'; // Mostrar el campo
        numeroAfiliadoInput.disabled = false; // Habilitar el campo
    }
}
</script>



@endsection