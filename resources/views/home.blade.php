@extends('layouts.app')

@section('title', 'Inicio')

@section('contenidoHome')
    <div class="relative w-full h-screen bg-fixed bg-cover bg-center" style="background-image: url('medico.jpeg');">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative flex items-center justify-center h-full text-white text-center p-8">
            <div class="bg-gray-800 bg-opacity-75 p-6 rounded-lg">
                <h1 class="text-3xl font-bold mb-4">Consultorio Dra. Adriana Mencarelli</h1>
                <p class="text-lg">Tu bienestar es nuestra prioridad. En nuestro consultorio, estamos comprometidos a
                    brindarte atención médica de calidad y personalizada.</p>
                <a href="#conocenos"
                    class="text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 dark:focus:ring-teal-800 shadow-lg shadow-teal-500/50 dark:shadow-lg dark:shadow-teal-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center mt-4 transition duration-300 inline-block">Más
                    de nosotros</a>
            </div>
        </div>
    </div>

    <div class="relative z-10">
        <section class="w-full bg-teal-500 py-8 text-white -mt-32">
            <div class="container mx-auto text-center px-8">
                <h2 class="text-4xl font-bold mb-4">Solicita tu turno</h2>
                <p class="text-lg mb-8">Elige la forma más conveniente para ti:</p>

                <div class="flex flex-col md:flex-row justify-center gap-8">
                    <div class="bg-teal-700 p-4 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold mb-4">Solicitar en línea</h3>
                        <p class="mb-4">Agenda tu cita médica fácilmente con nuestro sistema en línea. Nuestro equipo
                            estará encantado de atenderte.</p>
                        <a href="#formulario"
                            class="bg-cyan-600 hover:bg-cyan-400 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Solicitar
                            turno</a>
                    </div>
                    <div class="bg-teal-700 p-4 rounded-lg shadow-lg">
                        <h3 class="text-2xl font-bold mb-4">Solicitar por teléfono</h3>
                        <p class="mb-4">Si prefieres hablar con alguien directamente, llama a nuestro número de atención
                            al cliente  o visítanos en nuestro consultorio.</p>
                        <p class="text-lg font-semibold mb-4">Teléfono: <a href="tel:+123456789"
                                class="text-yellow-300">+123 456 789</a></p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Nuestro consultorio -->
    <div class="relative w-full bg-fixed bg-cover bg-center"
        style="background-image:url('pastilla.jpeg'); height: 115px;">
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

    <!-- Resto de la imagen -->
    <div class="relative w-full h-[5vh] md:h-[10vh] lg:h-[20vh] bg-fixed bg-cover bg-center pb-2"
     style="background-image:url('pastilla.jpeg');">
    </div>
@endsection
