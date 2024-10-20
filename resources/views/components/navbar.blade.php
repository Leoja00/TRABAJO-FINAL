<nav class="bg-gray-800 py-6 fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto flex px-8 xl:px-0">
        <div class="flex flex-grow items-center ml-9">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/logo.png') }}" class="h-12 w-auto" alt="Logo">
            </a>
        </div>
        <div class="flex lg:hidden">
            <img src="{{ asset('img/menu2.png') }}" onclick="openMenu();" alt="Menú" style="width: 40px; height: 40px;">
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
                                <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">Cerrar sesión</a>
                            @elseif (Auth::user()->role === 'paciente' || Auth::user()->role === 'secretario')
                                <a href="{{ route('perfil.show') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">
                                    Ver perfil
                                </a>

                                @php
                                    $missingFields = [];

                                    if (Auth::user()->role === 'paciente' && Auth::user()->paciente->obra_social !== 'SIN PREPAGA') {
                                        if (is_null(Auth::user()->paciente->obra_social))
                                            $missingFields['Obra social'] = '';
                                        if (is_null(Auth::user()->paciente->numero_afiliado))
                                            $missingFields['Número de afiliado'] = '';
                                    }

                                    if (is_null(Auth::user()->telefono))
                                        $missingFields['Teléfono'] = '';
                                    if (is_null(Auth::user()->fechaNacimiento))
                                        $missingFields['Fecha de Nacimiento'] = '';
                                    if (is_null(Auth::user()->dni))
                                        $missingFields['DNI'] = '';
                                    if (is_null(Auth::user()->direccion))
                                        $missingFields['Dirección'] = '';
                                @endphp

                                <a href="{{ route('turno.sacar') }}" 
                                   @if ($missingFields) 
                                       onclick="event.preventDefault(); Swal.fire({
                                           icon: 'error',
                                           title: 'ERROR',
                                           html: 'Debe completar los siguientes campos antes de {{ Auth::user()->role === 'secretario' ? 'agendar' : 'solicitar' }} un turno: <ul><li>{{ implode('</li><li>', array_map(fn($field) => '<strong>' . '*' . ucfirst($field) . '</strong>', array_keys($missingFields))) }}</li></ul>',
                                           footer: '<a href=\'{{ route('completar.campos') }}\' style=\'display: inline-block; padding: 10px 15px; background-color: rgb(250 204 21); color: white; text-align: center; border-radius: 5px; text-decoration: none; font-weight: bold;\'>Completar perfil</a>'
                                       });" 
                                   @else 
                                       class="block px-4 py-2 text-sm hover:bg-gray-600"
                                   @endif
                                   class="{{ $missingFields ? 'text-white text-sm' : 'text-teal-500 text-sm font-bold' }} transition-all duration-300 ease-in-out hover:scale-105 transform block px-3 py-2 hover:bg-gray-600">
                                    {{ Auth::user()->role === 'secretario' ? 'Agendar turno' : 'Solicitar turno' }}
                                </a>

                                @if(Auth::user()->role === 'paciente' && Auth::user()->paciente && Auth::user()->paciente->turnos()->exists())
                                    <a href="{{ route('turnos.ver') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">
                                        Ver turnos solicitados
                                    </a>
                                @endif

                                @if(Auth::user()->role === 'secretario' && \App\Models\Turno::exists())
                                    <a href="{{ route('turnos.secretario') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">
                                        Ver turnos solicitados
                                    </a>
                                @endif

                                <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">Cerrar sesión</a>
                            @elseif (Auth::user()->role === 'profesional' && Auth::user()->profesional)
                                <a href="{{ route('perfil.show') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">
                                    Ver perfil
                                </a>
                                @if (Auth::user()->profesional->turnos()->exists())
                                    <a href="{{ route('pacientes.profesional') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">
                                        Ver pacientes adheridos
                                    </a>
                                @endif
                                <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm hover:bg-gray-600">Cerrar sesión</a>
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
    window.onload = function () {
        document.getElementById('userMenuButton').addEventListener('click', function (e) {
            e.preventDefault();
            const dropdown = document.getElementById('dropdown');
            dropdown.classList.toggle('hidden');
        });

        document.querySelector('.lg:hidden img').addEventListener('click', openMenu);
    }

    function openMenu() {
        const menu = document.getElementById('menu');
        menu.classList.toggle('hidden');
    }
</script>