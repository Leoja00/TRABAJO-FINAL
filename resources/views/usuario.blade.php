@extends('layouts.app')

@section('title', 'Login')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center" style="background-image: url('medico.jpeg');">
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <div class="relative z-10 w-full px-4 py-8">
        <div class="flex flex-wrap justify-center gap-8 mt-24">
            <div class="flex items-center gap-4 py-8">
                <a href="#" id="loginLink"
                    class="text-lg font-bold transition-colors duration-300 text-teal-500 hover:text-teal-700"
                    onclick="showLogin()">Ingresar</a>

                <!-- Switch -->
                <div class="relative inline-block select-none transition duration-200 ease-in">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="toggle" class="sr-only peer" onclick="toggleForms()"
                            @if(session('signupError')) checked @endif>
                        <div
                            class="relative w-14 h-7 bg-teal-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600">
                        </div>
                    </label>
                </div>

                <a href="#" id="signupLink"
                    class="text-lg font-bold transition-colors duration-300 text-teal-500 hover:text-teal-700"
                    onclick="showSignUp()">Registrarse</a>
            </div>
        </div>

        <div class="flex flex-wrap justify-center gap-8 mt-0 perspective pb-12">
            <div class="w-full xl:w-2/3 lg:w-11/12 flex justify-center">
                <div class="relative w-full card-container @if(session('signupError')) flipped @endif">
                    <div class="card">
                        <!-- Cara del login -->
                        <div class="card-face card-front">
                            <div class="w-full flex">
                                <div class="w-1/2 hidden lg:block bg-cover rounded-l-lg opacity-90"
                                    style="background-image: url('{{ asset('img/login.jpg') }}');"></div>
                                <div class="w-full lg:w-1/2 bg-white dark:bg-gray-700 p-5 rounded-lg lg:rounded-l-none">
                                    <h3 class="py-4 text-2xl text-center text-gray-800 dark:text-white">Iniciar sesión
                                    </h3>
                                    <form class="px-8 pt-6 pb-8 mb-4 bg-white dark:bg-gray-800 rounded" method="POST"
                                        action="{{ route('login.submit') }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white"
                                                for="email">Correo</label>
                                            <input
                                                class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                                id="email" name="email" type="email" placeholder="Email" required />
                                            @if ($errors->login->has('email'))
                                            <p class="text-xs italic text-red-500">{{ $errors->login->first('email') }}
                                            </p>
                                            @endif
                                        </div>
                                        <div class="mb-4 relative">
    <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="password">Contraseña</label>
    <input class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
           id="passwordLogin" name="password" type="password" placeholder="******************" required />
    <!-- Icono de ojo -->
    <span class="password-eye-icon" onclick="togglePassword('passwordLogin', this)">
        <i class="far fa-eye" id="togglePasswordIconLogin"></i>
    </span>
    @if ($errors->login->has('password'))
        <p class="text-xs italic text-red-500">{{ $errors->login->first('password') }}</p>
    @endif
</div>


                                        <div class="mb-6 text-center">
                                            <button
                                                class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-700 dark:bg-blue-700 dark:text-white dark:hover:bg-blue-900 focus:outline-none focus:shadow-outline"
                                                type="submit">Ingresar</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                        <!-- Cara del registro -->
                        <div class="card-face card-back">
                            <div class="w-full flex">
                                <div class="w-1/2 hidden lg:block bg-cover rounded-l-lg opacity-90"
                                    style="background-image: url('{{ asset('img/login.jpg') }}');"></div>
                                <div class="w-full lg:w-1/2 bg-white dark:bg-gray-700 p-5 rounded-lg lg:rounded-l-none">
                                    <h3 class="py-4 text-2xl text-center text-gray-800 dark:text-white">Crear una cuenta
                                    </h3>
                                    <form class="px-8 pt-6 pb-8 mb-4 bg-white dark:bg-gray-800 rounded" method="POST"
                                        action="{{ route('register') }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white"
                                                for="name">Nombre</label>
                                            <input
                                                class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                                id="name" name="name" type="text" placeholder="Nombre completo"
                                                required />
                                            @if ($errors->register->has('name'))
                                            <p class="text-xs italic text-red-500">
                                                {{ $errors->register->first('name') }}</p>
                                            @endif
                                        </div>
                                        <div class="mb-4">
                                            <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white"
                                                for="emailSignUp">Correo</label>
                                            <input
                                                class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                                                id="emailSignUp" name="email" type="email" placeholder="Email"
                                                required />
                                            @if ($errors->register->has('email'))
                                            <p class="text-xs italic text-red-500">
                                                {{ $errors->register->first('email') }}</p>
                                            @endif
                                        </div>

                                        <div class="mb-4 relative">
    <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="passwordSignUp">Contraseña</label>
    <input class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
           id="passwordSignUp" name="password" type="password" placeholder="******************" required />
    <!-- Icono de ojo -->
    <span class="password-eye-icon" onclick="togglePassword('passwordSignUp', this)">
        <i class="far fa-eye" id="togglePasswordIconSignUp"></i>
    </span>
    @if ($errors->register->has('password'))
        <p class="text-xs italic text-red-500">{{ $errors->register->first('password') }}</p>
    @endif
</div>

<div class="mb-4 relative">
    <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-white" for="password_confirmation">Confirmar Contraseña</label>
    <input class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 dark:text-white border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
           id="password_confirmation" name="password_confirmation" type="password" placeholder="******************" required />
    <!-- Icono de ojo -->
    <span class="password-eye-icon" onclick="togglePassword('password_confirmation', this)">
        <i class="far fa-eye" id="togglePasswordIconConfirmation"></i>
    </span>
    @if ($errors->register->has('password_confirmation'))
        <p class="text-xs italic text-red-500">{{ $errors->register->first('password_confirmation') }}</p>
    @endif
</div>

                                        <div class="mb-6 text-center">
                                            <button
                                                class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded-full hover:bg-blue-700 dark:bg-blue-700 dark:text-white dark:hover:bg-blue-900 focus:outline-none focus:shadow-outline"
                                                type="submit">Registrarse</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.perspective {
    perspective: 1000px;
}

.card-container {
    position: relative;
    width: 100%;
}

.card {
    position: relative;
    width: 100%;
    transform-style: preserve-3d;
    transition: transform 0.8s;
    min-height: 550px;
}

.card-face {
    position: absolute;
    width: 100%;
    backface-visibility: hidden;
}

.card-front {
    transform: rotateY(0deg);
}

.card-back {
    transform: rotateY(180deg);
}

.flipped .card {
    transform: rotateY(180deg);
}


.password-eye-icon {
    position: absolute;
    top: 50%;  
    right: 1rem;  
    transform: translateY(-20%);  
    cursor: pointer;
    color: #6b7280;  
}

input[type="password"] {
    line-height: 1.6;  
    padding-right: 2.5rem; 
}



</style>

<script>
document.getElementById('toggle').checked = @if(session('signupError')) true @else false @endif;
toggleForms();

function toggleForms() {
    var cardContainer = document.querySelector('.card-container');
    var toggle = document.getElementById('toggle');

    if (toggle.checked) {
        cardContainer.classList.add('flipped');
    } else {
        cardContainer.classList.remove('flipped');
    }
}

function togglePassword(inputId, icon) {
    var input = document.getElementById(inputId);
    var iconElement = icon.querySelector('i');

    if (input.type === "password") {
        input.type = "text";
        iconElement.classList.remove('fa-eye');
        iconElement.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        iconElement.classList.remove('fa-eye-slash');
        iconElement.classList.add('fa-eye');
    }
}

</script>
@endsection