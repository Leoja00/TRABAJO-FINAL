@extends('layouts.app')

@section('title', 'Editar Perfil')

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

                        <form action="{{ route('perfil.actualizar') }}" method="POST">
                            @csrf
                           

                            <!-- Campos generales del usuario -->
                            <div class="mb-4">
                                <label for="telefono">Teléfono:</label>
                                <input type="text" name="telefono" id="telefono" class="border rounded w-full py-2 px-3"
                                    value="{{ old('telefono', Auth::user()->telefono) }}">
                            </div>
                            <div class="mb-4">
                                <label for="fechaNacimiento">Fecha de Nacimiento:</label>
                                <input type="date" name="fechaNacimiento" id="fechaNacimiento"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('fechaNacimiento', Auth::user()->fechaNacimiento) }}">
                            </div>
                            <div class="mb-4">
                                <label for="dni">DNI:</label>
                                <input type="text" name="dni" id="dni" class="border rounded w-full py-2 px-3"
                                    value="{{ old('dni', Auth::user()->dni) }}">
                            </div>
                            <div class="mb-4">
                                <label for="direccion">Dirección:</label>
                                <input type="text" name="direccion" id="direccion"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('direccion', Auth::user()->direccion) }}">
                            </div>

                            <!-- Campos específicos por rol -->
                            @if (Auth::user()->role === 'profesional')
                            <div class="mb-4">
                                <label for="especialidad">Especialidad:</label>
                                <input type="text" name="especialidad" id="especialidad"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('especialidad', Auth::user()->profesional->especialidad) }}">
                            </div>
                            <div class="mb-4">
                                <label for="matricula">Matrícula:</label>
                                <input type="text" name="matricula" id="matricula"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('matricula', Auth::user()->profesional->matricula) }}">
                            </div>
                            @elseif (Auth::user()->role === 'paciente')
                            <div class="mb-4">
                                <label for="obra_social">Obra Social:</label>
                                <input type="text" name="obra_social" id="obra_social"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('obra_social', Auth::user()->paciente->obra_social) }}">
                            </div>
                            <div class="mb-4">
                                <label for="numero_afiliado">Número de Afiliado:</label>
                                <input type="text" name="numero_afiliado" id="numero_afiliado"
                                    class="border rounded w-full py-2 px-3"
                                    value="{{ old('numero_afiliado', Auth::user()->paciente->numero_afiliado) }}">
                            </div>
                            @endif

                            <button type="submit"
                                class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded-full">
                                Guardar cambios
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
