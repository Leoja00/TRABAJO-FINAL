@extends('layouts.app')

@section('title', 'Perfil')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center"
    style="background-image: url('{{ asset('img/perfil.png') }}')">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative flex items-center justify-center min-h-screen">
        <div
            class="relative bg-clip-border rounded-xl bg-white bg-opacity-80 text-gray-700 shadow-md w-full max-w-[68rem] flex mt-40">

            <div id="perfil"
                class="w-full lg:w-{{ Auth::user()->role === 'profesional' ? '3/5' : 'full' }} rounded-lg lg:rounded-l-lg lg:rounded-r-none shadow-2xl bg-white opacity-75 mx-6 lg:mx-0">

                @php
                $missingFields = [];

                // Verificación de campos faltantes para profesionales
                if (Auth::user()->role === 'profesional') {
                if (is_null(Auth::user()->profesional->especialidad)) {
                $missingFields[] = 'especialidad';
                }
                if (is_null(Auth::user()->profesional->matricula)) {
                $missingFields[] = 'matrícula';
                }
                if (is_null(Auth::user()->profesional->imagen)) {
                $missingFields[] = 'imagen';
                }
                }

                // Verificación de campos faltantes para pacientes
                if (Auth::user()->role === 'paciente') {
                if (is_null(Auth::user()->paciente->obra_social)) {
                $missingFields[] = 'obra social';
                } else if (Auth::user()->paciente->obra_social !== 'SIN PREPAGA' &&
                is_null(Auth::user()->paciente->numero_afiliado)) {
                
                $missingFields[] = 'número de afiliado';
                }
                }

                // Verificación de campos generales de usuario
                if (is_null(Auth::user()->telefono)) {
                $missingFields[] = 'teléfono';
                }
                if (is_null(Auth::user()->fechaNacimiento)) {
                $missingFields[] = 'fecha de nacimiento';
                }
                if (is_null(Auth::user()->dni)) {
                $missingFields[] = 'DNI';
                }
                if (is_null(Auth::user()->direccion)) {
                $missingFields[] = 'dirección';
                }
                @endphp

                <div class="p-4 md:p-12 text-center lg:text-left">
                    <h1 class="text-3xl font-bold pt-8 lg:pt-0">{{ Auth::user()->name }}</h1>
                    <div class="mx-auto lg:mx-0 w-4/5 pt-3 border-b-2 border-green-500 opacity-25"></div>

                    <p class="pt-4 text-base font-bold flex items-center justify-center lg:justify-start">
                        <svg class="h-8 w-8 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        {{ Auth::user()->email }}
                    </p>

                    <p class="pt-4 text-base font-bold flex items-center justify-center lg:justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-8 w-8 text-green-500 mr-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41-.513m14.095-5.13 1.41-.513M5.106 17.785l1.15-.964m11.49-9.642 1.149-.964M7.501 19.795l.75-1.3m7.5-12.99.75-1.3m-6.063 16.658.26-1.477m2.605-14.772.26-1.477m0 17.726-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205 12 12m6.894 5.785-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
                        </svg>
                        {{ ucfirst(Auth::user()->role) }}
                    </p>

                    <!-- Mostrar información solo si existe -->
                    @if(Auth::user()->dni)
                    <p class="pt-4 text-base font-bold flex items-center justify-center lg:justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-8 w-8 text-green-500 mr-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                        </svg>

                        {{ ucfirst(Auth::user()->dni) }}
                    </p>
                    @endif

                    @if(Auth::user()->telefono)
                    <p class="pt-4 text-base font-bold flex items-center justify-center lg:justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-8 w-8 text-green-500 mr-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                        </svg>

                        {{ ucfirst(Auth::user()->telefono) }}
                    </p>
                    @endif

                    @if(Auth::user()->direccion)
                    <p class="pt-4 text-base font-bold flex items-center justify-center lg:justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-8 w-8 text-green-500 mr-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>

                        {{ ucfirst(Auth::user()->direccion) }}
                    </p>
                    @endif

                    @if(Auth::user()->role === 'paciente' && Auth::user()->paciente->obra_social)
                    <p class="pt-4 text-base font-bold flex items-center justify-center lg:justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mr-4" width="24"
                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <line x1="3" y1="21" x2="21" y2="21" />
                            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" />
                            <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
                            <line x1="10" y1="9" x2="14" y2="9" />
                            <line x1="12" y1="7" x2="12" y2="11" />
                        </svg>

                        {{ ucfirst(Auth::user()->paciente->obra_social) }} ---
                        {{ ucfirst(Auth::user()->paciente->numero_afiliado) }}
                    </p>
                    @endif


                    @if(Auth::user()->role === 'profesional' && Auth::user()->profesional->especialidad)
                    <p class="pt-4 text-base font-bold flex items-center justify-center lg:justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-8 w-8 text-green-500 mr-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>



                        {{ Auth::user()->profesional->especialidad }}
                    </p>
                    @endif

                    @if(Auth::user()->role === 'profesional' && Auth::user()->profesional->matricula)
                    <p class="pt-4 text-base font-bold flex items-center justify-center lg:justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-8 w-8 text-green-500 mr-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>

                        {{ Auth::user()->profesional->matricula }}
                    </p>
                    @endif

                    <!-- Mostrar botón de completar campos si hay campos faltantes -->
                    @if ($missingFields)
                    <p class="text-red-500 pt-4">Faltan completar los siguientes campos:</p>
                    <ul class="text-red-500">
                        @foreach ($missingFields as $field)
                        <li>* {{ ucfirst($field) }}</li>
                        @endforeach
                        <p class="text-red-500 font-bold pt-4">Para editar tu perfil, primero debes completar los campos
                            requeridos.</p>
                    </ul>
                    <div class="pt-4">
                        <a href="{{ route('completar.campos') }}"
                            class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded-full block text-center mt-2">
                            Completar campos faltantes
                        </a>
                    </div>

                    @else
                    <div class="pt-12 pb-8">
                        <a href="{{route('perfil.editar')}}"
                            class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded-full">
                            Editar Perfil
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            @if (Auth::user()->role === 'profesional')
            <div class="w-full lg:w-2/5">
                @php
                $image = Auth::user()->profesional->imagen;
                @endphp

                @if ($image)
                <img src="{{ asset('storage/' . $image) }}"
                    class="rounded-none lg:rounded-lg shadow-2xl hidden lg:block">
                @else
                <div class="text-center">
                    <p class="text-gray-600 text-lg">Imagen de {{ Auth::user()->name }}</p>
                </div>
                @endif
            </div>
            @endif



        </div>
    </div>
</div>
@endsection