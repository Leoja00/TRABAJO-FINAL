<nav class="bg-gray-800 py-6 fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto flex px-8 xl:px-0">
        <div class="flex flex-grow items-center ml-9">
            <a href="{{ route('home') }}">
                
                <img src="{{ asset('img/logo.png') }}" class="h-12 w-auto" alt="Logo">
            </a>
        </div>
        <div class="flex lg:hidden">

            <img src="{{ asset('img/menu.png') }}" onclick="openMenu();" alt="Menú">
        </div>
        <div id="menu"
            class="lg:flex hidden flex-grow justify-between absolute lg:relative lg:top-0 top-20 left-0 bg-gray-800 w-full lg:w-auto items-center py-14 lg:py-0 px-8 sm:px-24 lg:px-0 transition-all duration-500 ease-in-out">
            <div class="flex flex-col lg:flex-row mb-8 lg:mb-0">
                <a href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? 'text-teal-500 text-m font-bold' : 'text-white text-base' }} lg:mr-7 mb-8 lg:mb-0 transition-all duration-300 ease-in-out hover:scale-105 transform">
                    Inicio
                </a>
                <a href="{{ route('cobertura') }}"
                    class="{{ request()->is('cobertura') ? 'text-teal-500 text-m font-bold' : 'text-white text-base' }} lg:mr-7 mb-8 lg:mb-0 transition-all duration-300 ease-in-out hover:scale-105 transform">
                    Cobertura
                </a>
                <a href="{{ route('profesionales') }}"
                    class="{{ request()->routeIs('profesionales') ? 'text-teal-500 text-m font-bold' : 'text-white text-base' }} lg:mr-7 mb-8 lg:mb-0 transition-all duration-300 ease-in-out hover:scale-105 transform">
                    Profesionales
                </a>
                <a href="{{ route('contacto') }}"
                    class="{{ request()->is('contacto') ? 'text-teal-500 text-m font-bold' : 'text-white text-base' }} transition-all duration-300 ease-in-out hover:scale-105 transform">
                    Contacto
                </a>
            </div>
            <div class="flex flex-col lg:flex-row text-center relative">
                @if (Auth::check())
                <div class="relative inline-block text-left">
                    <a href="#" id="userMenuButton"
                        class="{{ request()->routeIs(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'usuario') ? 'text-teal-500 text-m font-bold' : 'text-white text-base' }} text-white border border-white py-2.5 px-5 rounded-md hover:bg-teal-500 hover:text-white hover:border-teal-500 transition duration-500 ease-in-out lg:mr-4 mb-8 lg:mb-0 flex items-center space-x-2 hover:scale-105 transform">
                        <!-- Cambié la ruta de la imagen -->
                        <img src="{{ asset('img/user.png') }}" class="w-6 h-6" alt="Usuario">
                        <span>{{ Auth::user()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 ml-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </a>

                    <div id="dropdown"
                        class="hidden absolute right-0 z-20 bg-gray-700 text-white rounded-md shadow-lg mt-2">
                        @if (Auth::user()->role === 'admin')
                        <a href="{{ route('admin.panel') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">
                            Administrar usuarios
                        </a>
                        <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">Cerrar
                            sesión</a>
                        @elseif (Auth::user()->role === 'paciente')
                        <a href="{{ route('perfil.show') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">
                            Ver perfil
                        </a>
                        <a href="{{ route('turno.sacar') }}" 
                        @php
                            $missingFieldsPaciente = [];
                            if (Auth::user()->paciente->obra_social !== 'SIN PREPAGA') {
                                if (is_null(Auth::user()->paciente->obra_social)) $missingFieldsPaciente['Obra social'] = '';
                                if (is_null(Auth::user()->paciente->numero_afiliado)) $missingFieldsPaciente['Numero de afiliado'] = '';
                                if (is_null(Auth::user()->telefono)) $missingFieldsPaciente['telefono'] = '';
                                if (is_null(Auth::user()->fechaNacimiento)) $missingFieldsPaciente['Fecha de Nacimiento'] = '';
                                if (is_null(Auth::user()->dni)) $missingFieldsPaciente['dni'] = '';
                                if (is_null(Auth::user()->direccion)) $missingFieldsPaciente['direccion'] = '';
                            }
                        @endphp
                                                
                                                @if ($missingFieldsPaciente)
                        onclick="event.preventDefault(); Swal.fire({
                        icon: 'error',
                        title: 'ERROR',
                        html: 'Debe completar los siguientes campos antes de solicitar un turno: <ul><li>{{ implode('</li><li>', array_map(fn($field) => '<strong>' .'*' . ucfirst($field). '</strong>', array_keys($missingFieldsPaciente))) }}</li></ul>',
                        footer: '<a href=\'{{ route('completar.campos') }}\' style=\'display: inline-block; padding: 10px 15px; background-color: rgb(250 204 21); color: white; text-align: center; border-radius: 5px; text-decoration: none; font-weight: bold;\'>Completar perfil</a>'
                        });"
                            class="block px-4 py-2 text-sm hover:bg-gray-600"
                        @else
                            class="block px-4 py-2 text-sm hover:bg-gray-600"
                        @endif
                        >
                        Solicitar turno
                        </a>


                        <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">Cerrar
                            sesión</a>
                        @else
                        <a href="{{ route('perfil.show') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">
                            Ver perfil
                        </a>
                        <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">Cerrar
                            sesión</a>
                        @endif
                    </div>
                </div>

                @else
                <a href="{{ route('usuario') }}"
                    class="{{ request()->routeIs('usuario') ? 'text-teal-500 text-m font-bold' : 'text-white text-base' }} text-white border border-white py-2.5 px-5 rounded-md hover:bg-teal-500 hover:text-white hover:border-teal-500 transition duration-500 ease-in-out lg:mr-4 mb-8 lg:mb-0 flex items-center space-x-2 hover:scale-105 transform">

                    <img src="{{ asset('img/user.png') }}" class="w-6 h-6" alt="Usuario">
                    <span>Usuario</span>
                </a>
                @endif
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
window.onload = function() {
    document.getElementById('userMenuButton').addEventListener('click', function(e) {
        e.preventDefault();
        const dropdown = document.getElementById('dropdown');
        dropdown.classList.toggle('hidden');
    });
}
</script>