<footer class="bg-gray-1000 rounded-lg shadow dark:bg-gray-800 mt-2">
    <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
        <div class="sm:block sm:items-center sm:justify-center text-center">
            <a href="{{ route('home') }}" class="flex flex-col items-center mb-4 space-y-3 rtl:space-y-reverse">
                <!-- Cambié la ruta de la imagen -->
                <img src="{{ asset('img/logo.png') }}" class="h-8 w-auto" alt="Logo">
                <span class="text-lg md:text-xl lg:text-2xl font-semibold whitespace-nowrap text-white">
                    Consultorio Dra. Adriana Mencarelli
                </span>
            </a>
            <ul class="flex flex-wrap justify-center items-center mb-6 text-sm font-medium text-gray-400 dark:text-gray-400">
                <li>
                    <div class="flex items-center me-4 md:me-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        Jose Hernandez S/N Barrio San Martin<br>
                        Santa Rosa del Conlara
                    </div>
                </li>
                <li>
                    <div class="flex items-center me-4 md:me-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0 6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z" />
                        </svg>
                        02656-492619
                    </div>
                </li>
            </ul>
        </div>
        <hr class="my-6 border-gray-700 sm:mx-auto dark:border-gray-700 lg:my-8" />
        <span id="copyright" class="block text-sm text-gray-400 sm:text-center dark:text-gray-400"></span>
    </div>
</footer>
