@extends('layouts.app')

@section('title', 'Contacto')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center" style="background-image: url('medico.jpeg');">
    <!-- Capa de fondo con opacidad -->
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative flex items-center justify-center min-h-screen">
        <div class="relative bg-clip-border rounded-xl bg-white bg-opacity-80 text-gray-700 shadow-md w-full max-w-[68rem] flex mt-40">
            <!-- Imagen -->
            <div class="flex-shrink-0 w-1/3 overflow-hidden rounded-l-xl">
                
            <img src="{{ asset('img/contacto.jpg') }}" alt="card-image" class="object-cover w-full h-full" />
            </div>
            <!-- Texto-->
            <div class="p-6 w-2/3">
                <h6 class="block mb-4 font-sans text-base antialiased font-semibold leading-relaxed tracking-normal text-gray-700 uppercase">
                    Contacto
                </h6>
                <h4 class="block mb-2 font-sans text-2xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
                    Horario de atención
                </h4>
                <p class="block mb-1 font-sans text-base antialiased font-normal leading-relaxed text-gray-700">
                    Lunes a viernes 08:30-13:00 / 16:00-20:00
                </p>
                <p class="block mb-8 font-sans text-base antialiased font-normal leading-relaxed text-gray-700">
                    Sábado 09:00-13:00
                </p>
                <h6 class="block mb-2 font-sans text-base antialiased font-semibold leading-relaxed tracking-normal text-gray-700 uppercase">
                    Dirección
                </h6>
                <p class="block mb-8 font-sans text-base antialiased font-normal leading-relaxed text-gray-700">
                Ruta 23 S/N Barrio San Martin<br>
                Santa Rosa del Conlara, San Luis
                </p>
                <h6 class="block mb-2 font-sans text-base antialiased font-semibold leading-relaxed tracking-normal text-gray-700 uppercase">
                    Teléfono
                </h6>
                <p class="block mb-0 font-sans text-base antialiased font-normal leading-relaxed text-gray-700">
                +123 456 789
                </p>
                
            </div>
        </div>
    </div>
    
</div>

<div class="relative w-full bg-fixed bg-cover bg-center"
        style="background-image:url('medico.jpeg'); height: 115px;">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative flex items-center justify-center h-full">
            <div
                class="bg-gray-800 bg-opacity-75 p-6 rounded-lg flex items-center justify-center space-x-2 w-full max-w-4xl absolute top-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <p class="text-lg text-white">Nuestro consultorio</p>
            </div>
        </div>
    </div>
    <!-- Mapa -->
    <div class="relative w-full h-0" style="padding-bottom: 25%;">
        <iframe class="absolute top-0 left-0 w-full h-full border-0"
            src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d809.8127134257095!2d-65.21421773040485!3d-32.34064999836556!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMzLCsDIwJzI2LjMiUyA2NcKwMTInNDguOSJX!5e1!3m2!1ses-419!2sar!4v1725375247717!5m2!1ses-419!2sar"
            allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="relative w-full h-[5vh] md:h-[10vh] lg:h-[20vh] bg-fixed bg-cover bg-center pb-2"
     style="background-image:url('pastilla.jpeg');">
    </div>

@endsection
