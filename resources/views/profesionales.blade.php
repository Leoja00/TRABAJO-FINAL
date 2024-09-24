@extends('layouts.app')

@section('title', 'Profesionales')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center" style="background-image: url('medico.jpeg');">
    <!-- Capa de fondo con opacidad -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <!-- Contenido de tarjetas -->
    <div class="relative z-10 w-full px-4 py-8">
        <div class="flex flex-wrap justify-center gap-8 mt-32">
        @forelse ($profesionales as $profesional)
    <div class="relative flex flex-col text-black bg-white bg-opacity-65 shadow-md bg-clip-border rounded-xl w-full sm:w-80 transform transition-transform duration-300 hover:scale-105 hover:shadow-xl">
        <div class="relative mx-4 mt-4 overflow-hidden text-gray-700 bg-white bg-opacity-90 shadow-lg bg-clip-border rounded-xl h-80">
            <img src="{{ asset('storage/' . $profesional->imagen) }}" alt="Imagen de perfil de {{ $profesional->user->name }}"
                class="w-full h-full object-cover" />
        </div>
        <div class="p-6 text-center">
            <h4 class="block mb-2 font-sans text-2xl antialiased font-bold leading-snug tracking-normal text-blue-gray-900">
                {{ $profesional->user->name }}
            </h4>
            <p class="block font-sans text-xl antialiased font-medium leading-relaxed text-gray-900">
                {{ $profesional->especialidad }}
            </p>
            <div class="flex justify-center p-0 pt-2 gap-7 items-center">
                <span class="block font-sans text-lg antialiased font-normal leading-relaxed text-gray-850">
                    N° Matrícula: {{ $profesional->matricula }}
                </span>
            </div>
        </div>
    </div>
@empty
    <p class="text-center text-white">No hay profesionales disponibles.</p>
@endforelse
        </div>
    </div>
</div>
@endsection
