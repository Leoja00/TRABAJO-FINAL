<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('img/icono.jpeg') }}">
    <title>@yield('title', 'Document')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        .menu-enter {
            opacity: 0;
            transform: translateY(-10%);
        }

        .menu-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
        }

        .menu-leave-active {
            opacity: 0;
            transform: translateY(-10%);
            transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-950">
    @include('components.navbar')

    <main>
        @yield('contenidoHome')
    </main>

    @include('components.footer')

    <script>
        function openMenu() {
            let menu = document.getElementById('menu');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                menu.classList.remove('menu-leave-active');
                menu.classList.add('menu-enter');
                setTimeout(() => {
                    menu.classList.remove('menu-enter');
                    menu.classList.add('menu-enter-active');
                }, 10);
            } else {
                menu.classList.remove('menu-enter-active');
                menu.classList.add('menu-leave-active');
                setTimeout(() => {
                    menu.classList.add('hidden');
                    menu.classList.remove('menu-leave-active');
                }, 500);
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const year = new Date().getFullYear();
            const copyrightElement = document.getElementById('copyright');
            copyrightElement.innerHTML =
                `© ${year} <a href="https://github.com/Leoja00" target="_blank" class="hover:underline">Leonardo Gallardo</a>. Derechos reservados.`;
        });
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    </script>
</body>
</html>
