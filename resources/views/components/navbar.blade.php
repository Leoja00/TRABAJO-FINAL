<nav class="bg-gray-800 py-6 fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto flex px-8 xl:px-0">
        <div class="flex flex-grow items-center ml-9">
            <a href="{{ route('home') }}">
                <img src="logo.png" class="h-12 w-auto" alt="Logo">
            </a>
        </div>
        <div class="flex lg:hidden">
            <img src="menu.png" onclick="openMenu();" alt="Menú">
        </div>
        <div id="menu"
            class="lg:flex hidden flex-grow justify-between absolute lg:relative lg:top-0 top-20 left-0 bg-gray-800 w-full lg:w-auto items-center py-14 lg:py-0 px-8 sm:px-24 lg:px-0 transition-all duration-500 ease-in-out">
            <div class="flex flex-col lg:flex-row mb-8 lg:mb-0">
                <a href="{{ route('home') }}" 
                   class="{{ request()->routeIs('home') ? 'text-teal-500 text-m font-bold' : 'text-white text-base' }} lg:mr-7 mb-8 lg:mb-0 transition-all duration-300 ease-in-out hover:scale-105 transform">
                    Inicio
                </a>
                <a href={{ route('cobertura') }} 
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
            <div class="flex flex-col lg:flex-row text-center">
                <a href="{{ route('usuario') }}"
                class="{{ request()->routeIs('usuario') ? 'text-teal-500 text-m font-bold' : 'text-white text-base' }} text-white border border-white py-2.5 px-5 rounded-md hover:bg-teal-500 hover:text-white hover:border-teal-500 transition duration-500 ease-in-out lg:mr-4 mb-8 lg:mb-0 flex items-center space-x-2 hover:scale-105 transform">
            <img src="user.png" class="w-6 h-6" alt="Usuario">
            <span>Usuario</span>
                </a>
            </div>
        </div>
    </div>
</nav>
