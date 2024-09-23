@extends('layouts.app')

@section('title', 'Login')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center" style="background-image: url('medico.jpeg');">
    <!-- Capa de fondo con opacidad -->
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <!-- Contenido del formulario -->
    <div class="relative z-10 w-full px-4 py-8">
        
        <div class="flex flex-wrap justify-center gap-8 mt-24">
            <!-- Switch para alternar entre Login y Sign up -->
            <div class="flex items-center gap-4 py-8">
                <a href="#" id="loginLink" class="text-lg font-bold text-teal-500" onclick="showLogin()">Ingresar</a>
                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                    <input type="checkbox" name="toggle" id="toggle" class="absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer toggle-checkbox" onclick="toggleForms()" />
                    <label for="toggle" class="block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                </div>
                <a href="#" id="signupLink" class="text-lg font-bold text-teal-500" onclick="showSignUp()">Registrarse</a>
            </div>
        </div>

        <div class="flex flex-wrap justify-center gap-8 mt-0">
            <div class="w-full xl:w-3/4 lg:w-11/12 flex"></div>
           
            <div class="w-full xl:w-3/4 lg:w-11/12 flex">
               
                <!-- Columna de imagen -->
                <div class="w-full h-auto bg-gray-400 dark:bg-gray-800 hidden lg:block lg:w-5/12 bg-cover rounded-l-lg opacity-90"
                    style="background-image: url('login.jpg')"></div>
                <!-- Columna del formulario -->
                <div class="w-full lg:w-7/12 bg-white dark:bg-gray-700 p-5 rounded-lg lg:rounded-l-none">
                    <!-- Formulario de Login -->
                    <div id="loginForm">
                        <h3 class="py-4 text-2xl text-center text-gray-800 dark:text-white">Iniciar sesión</h3>
                        <form class="px-8 pt-6 pb-8 mb-4 bg-white dark:bg-gray-800 rounded">
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="email">
                                    Correo
                                </label>
                                <input
                                    class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="email"
                                    type="email"
                                    placeholder="Email"
                                />
                            </div>
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="password">
                                    Contraseña
                                </label>
                                <input
                                    class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border border-red-500 rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="password"
                                    type="password"
                                    placeholder="******************"
                                />
                                <p class="text-xs italic text-red-500">Por favor, ingrese una contraseña.</p>
                            </div>
                            <div class="mb-6 text-center">
                                <button
                                    class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-700 dark:bg-blue-700 dark:text-white dark:hover:bg-blue-900 focus:outline-none focus:shadow-outline"
                                    type="button"
                                >
                                    Ingresar
                                </button>
                            </div>
                            <hr class="mb-6 border-t" />
                            <div class="text-center">
                                <a class="inline-block text-sm text-blue-500 dark:text-blue-500 align-baseline hover:text-blue-800"
                                    href="#">
                                    Olvidaste tu contraseña?
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Formulario de Sign up (oculto inicialmente) -->
                    <div id="signupForm" class="hidden">
                        <h3 class="py-4 text-2xl text-center text-gray-800 dark:text-white">Crear una cuenta</h3>
                        <form class="px-8 pt-6 pb-8 mb-4 bg-white dark:bg-gray-800 rounded">
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="name">
                                    Nombre
                                </label>
                                <input
                                    class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="name"
                                    type="text"
                                    placeholder="Nombre completo"
                                />
                            </div>
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="emailSignUp">
                                    Correo
                                </label>
                                <input
                                    class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="emailSignUp"
                                    type="email"
                                    placeholder="Email"
                                />
                            </div>
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="passwordSignUp">
                                    Contraseña
                                </label>
                                <input
                                    class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                    id="passwordSignUp"
                                    type="password"
                                    placeholder="******************"
                                />
                            </div>
                            <div class="mb-6 text-center">
                                <button
                                    class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-700 dark:bg-blue-700 dark:text-white dark:hover:bg-blue-900 focus:outline-none focus:shadow-outline"
                                    type="button"
                                >
                                    Registrarse
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleForms() {
        var loginForm = document.getElementById('loginForm');
        var signupForm = document.getElementById('signupForm');
        var loginLink = document.getElementById('loginLink');
        var signupLink = document.getElementById('signupLink');
        var toggle = document.getElementById('toggle');

        if (toggle.checked) {
            loginForm.classList.add('hidden');
            signupForm.classList.remove('hidden');
            loginLink.classList.remove('text-blue-500');
            loginLink.classList.add('text-white');
            signupLink.classList.remove('text-white');
            signupLink.classList.add('text-blue-500');
        } else {
            signupForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
            signupLink.classList.remove('text-blue-500');
            signupLink.classList.add('text-white');
            loginLink.classList.remove('text-white');
            loginLink.classList.add('text-blue-500');
        }
    }
</script>
@endsection
