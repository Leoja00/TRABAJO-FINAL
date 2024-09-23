@extends('layouts.app')

@section('title', 'Cobertura')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center" style="background-image: url('medico.jpeg');">
    <!-- Capa de fondo con opacidad -->
    <div class="absolute inset-0 bg-black opacity-50"></div>
    
    <!-- Contenedor centrado con margen superior uniforme -->
    <div class="relative z-10 w-full px-4 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 lg:mt-64 mt-32 justify-items-center">
            <!-- Dosep -->
            <div class="hover:-translate-y-2 group bg-neutral-50 duration-500 w-full sm:w-44 lg:w-56 lg:h-56 h-44 flex text-neutral-600 flex-col justify-center items-center relative rounded-xl overflow-hidden shadow-md">
                <img src="dosep.jpg" alt="dosep"
                    class="absolute blur z-10 duration-300 group-hover:blur-none group-hover:scale-105">
                <div class="z-20 flex flex-col justify-center items-center">
                </div>
            </div>

            <!-- Pami -->
            <div class="hover:-translate-y-2 group bg-neutral-50 duration-500 w-full sm:w-44 lg:w-56 lg:h-56 h-44 flex text-neutral-600 flex-col justify-center items-center relative rounded-xl overflow-hidden shadow-md">
                <img src="pami.png" alt="pami"
                    class="absolute blur z-10 duration-300 group-hover:blur-none group-hover:scale-105">
                <div class="z-20 flex flex-col justify-center items-center">
                </div>
            </div>
            
            <!-- Osde -->
            <div class="hover:-translate-y-2 group bg-neutral-50 duration-500 w-full sm:w-44 lg:w-56 lg:h-56 h-44 flex text-neutral-600 flex-col justify-center items-center relative rounded-xl overflow-hidden shadow-md">
                <img src="osde.png" alt="osde"
                    class="absolute blur z-10 duration-300 group-hover:blur-none group-hover:scale-105">
                <div class="z-20 flex flex-col justify-center items-center">
                </div>
            </div>

            <!-- Sin obra social -->
            <div class="hover:-translate-y-2 group bg-neutral-50 duration-500 w-full sm:w-44 lg:w-56 lg:h-56 h-44 flex text-neutral-600 flex-col justify-center items-center relative rounded-xl overflow-hidden shadow-md">
                <img src="sinCobertura.png" alt="sinCobertura"
                    class="absolute blur z-10 duration-300 group-hover:blur-none group-hover:scale-105">
                <div class="z-20 flex flex-col justify-center items-center">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
