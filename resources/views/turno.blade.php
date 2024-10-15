@extends('layouts.app')

@section('title', 'Turno')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center"
    style="background-image: url('{{ asset('img/turnos.jpg') }}');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="container mx-auto p-4 text-center relative z-10">
        <h1 class="text-white text-2xl mb-6 mt-40">Bienvenido al sistema de turnos, {{ Auth::user()->name }}</h1>

        <!-- Mensajes de éxito o error -->
        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-200 text-red-800 p-2 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Selección de profesional -->
            <div>
                <label for="profesional_id" class="block text-lg font-medium mb-2 text-white">Selecciona un
                    profesional:</label>
                <select id="profesional_id" name="profesional_id" required class="border p-2 rounded w-full"
                    onchange="updateProfesionalId()">
                    <option value="">-- Selecciona un profesional --</option>
                    @foreach($profesionales as $profesional)
                        <option value="{{ $profesional->id }}">{{ $profesional->user->name }} -
                            {{ $profesional->especialidad }}
                        </option>
                    @endforeach
                </select>
                @error('profesional_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Calendario para seleccionar fecha -->
            <div class="flex flex-col">
                <label for="fecha" class="block text-lg font-medium mb-2 text-white">Seleccionar fecha:</label>
                <input type="text" id="fecha" name="fecha" class="border p-2 rounded w-full"
                    placeholder="Seleccionar fecha" readonly>
            </div>
        </div>

        <!-- Spinner de carga -->
        <div id="spinner" class="text-center mb-6" style="display: none;">
            <div role="status">
                <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin fill-blue-600 mx-auto"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.59c0-27.61-22.39-50-50-50S0 22.98 0 50.59a50.22 50.22 0 0015.22 35.36A49.78 49.78 0 0050 101c27.61 0 50-22.39 50-50.41zM9.08 50.59c0-22.61 18.32-40.92 40.92-40.92s40.92 18.32 40.92 40.92S72.61 91.51 50 91.51 9.08 73.2 9.08 50.59z"
                        fill="currentColor" />
                    <path
                        d="M93.97 39.04c1.78-.46 2.89-2.27 2.4-4.05a3.635 3.635 0 00-4.05-2.4c-1.78.46-2.89 2.27-2.4 4.05.46 1.78 2.27 2.89 4.05 2.4z"
                        fill="currentFill" />
                </svg>
                <span class="text-white mt-2 block">Cargando horarios...</span>
            </div>
        </div>

        <!-- Formulario para reservar turno -->
        <form method="POST" action="{{ route('turno.guardar') }}" class="mt-6" onsubmit="return validarReserva(event)">
            @csrf
            <input type="hidden" name="profesional_id" id="profesional_id-hidden">
            <input type="hidden" name="fecha" id="fecha-input">
            <input type="hidden" name="hora" id="hora-input">

            @if(Auth::user()->role === 'secretario')
                <div class="mt-6">
                    <label for="dni" class="block text-lg font-medium mb-2 text-white">DNI del Paciente:</label>
                    <input type="text" id="dni" name="dni" class="border p-2 rounded w-full" required pattern="^\d{7,9}$" title="Debe contener entre 7 y 9 números" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                    @error('dni')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <div id="dni-info" class="mt-2 text-white"></div>

                    <!-- Spinner de carga para DNI -->
                    <div id="dni-spinner" class="mb-12" style="display: none;">
                        <svg aria-hidden="true" class="w-6 h-6 text-gray-200 animate-spin fill-blue-600 inline"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.59c0-27.61-22.39-50-50-50S0 22.98 0 50.59a50.22 50.22 0 0015.22 35.36A49.78 49.78 0 0050 101c27.61 0 50-22.39 50-50.41zM9.08 50.59c0-22.61 18.32-40.92 40.92-40.92s40.92 18.32 40.92 40.92S72.61 91.51 50 91.51 9.08 73.2 9.08 50.59z"
                                fill="currentColor" />
                            <path
                                d="M93.97 39.04c1.78-.46 2.89-2.27 2.4-4.05a3.635 3.635 0 00-4.05-2.4c-1.78.46-2.89 2.27-2.4 4.05.46 1.78 2.27 2.89 4.05 2.4z"
                                fill="currentFill" />
                        </svg>
                        <span class="text-white ml-2">Verificando DNI...</span>
                    </div>
                </div>
            @endif

            <!-- Horarios disponibles -->
            <div class="mb-6" id="horarios-disponibles">

                <!-- Contenedor para horarios -->
                <div class="grid grid-cols-4 gap-4">
                    <div id="manana-horarios" class="col-span-2"></div>
                    <div id="tarde-horarios" class="col-span-2"></div>
                </div>
            </div>

            <!-- Botón de enviar -->
            <button type="submit" class="bg-sky-500 text-white px-6 py-2 rounded-md font-bold hover:bg-cyan-700"
                id="reservar-btn">
                Reservar Turno
            </button>
        </form>
    </div>
</div>

<script>
    flatpickr("#fecha", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function (selectedDates, dateStr, instance) {
            document.getElementById('fecha-input').value = dateStr;
            fetchHorariosDisponibles(dateStr);
        }
    });

    function fetchHorariosDisponibles(fecha) {
        const profesionalId = document.getElementById('profesional_id').value;
        document.getElementById('profesional_id-hidden').value = profesionalId;

        if (!profesionalId) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor selecciona un profesional primero.',
            });
            return;
        }

        // Mostrar el spinner
        document.getElementById('spinner').style.display = 'block';

        fetch('{{ route('getHorariosDisponibles') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                fecha: fecha,
                profesional_id: profesionalId
            })
        })
            .then(response => response.json())
            .then(data => {
                // Ocultar el spinner
                document.getElementById('spinner').style.display = 'none';

                document.getElementById('manana-horarios').innerHTML = '';
                document.getElementById('tarde-horarios').innerHTML = '';

                document.getElementById('reservar-btn').disabled = !data.horariosDisponibles.manana.length && !data.horariosDisponibles.tarde.length;

                // TURNO MAÑANA
                const contenedorTurno = document.createElement('div');
                contenedorTurno.className = 'border-2 border-white p-4 rounded-md mb-12';

                const mananaHeader = document.createElement('h3');
                mananaHeader.textContent = 'Turno Mañana';
                mananaHeader.className = 'text-lg font-bold mt-4 text-white';
                contenedorTurno.appendChild(mananaHeader);

                data.horariosDisponibles.manana.forEach(function (hora) {
                    const button = document.createElement('button');
                    button.textContent = hora;
                    button.className = 'border border-gray-300 p-4 rounded-md w-24 text-center m-2 cursor-pointer hover:bg-teal-500 text-white';
                    button.onclick = function (event) {
                        event.preventDefault();
                        seleccionarHora(hora);
                    };
                    contenedorTurno.appendChild(button);
                });

                document.getElementById('manana-horarios').appendChild(contenedorTurno);

                // TURNO TARDE
                const contenedorTurnoTarde = document.createElement('div');
                contenedorTurnoTarde.className = 'border-2 border-white p-4 rounded-md mb-4';

                const tardeHeader = document.createElement('h3');
                tardeHeader.textContent = 'Turno Tarde';
                tardeHeader.className = 'text-lg font-bold mt-4 text-white';
                contenedorTurnoTarde.appendChild(tardeHeader);

                data.horariosDisponibles.tarde.forEach(function (hora) {
                    const button = document.createElement('button');
                    button.textContent = hora;
                    button.className = 'border border-gray-300 p-4 rounded-md w-24 text-center m-2 cursor-pointer hover:bg-teal-500 text-white';
                    button.onclick = function (event) {
                        event.preventDefault();
                        seleccionarHora(hora);
                    };
                    contenedorTurnoTarde.appendChild(button);
                });

                document.getElementById('tarde-horarios').appendChild(contenedorTurnoTarde);

            })
            .catch(error => {
                console.error('Error al obtener los horarios disponibles:', error);

                document.getElementById('spinner').style.display = 'none';
            });
    }

    let horarioSeleccionado = null;

    function seleccionarHora(hora) {
        if (horarioSeleccionado) {
            horarioSeleccionado.classList.remove('bg-teal-500');
        }

        const buttons = document.querySelectorAll('button');
        buttons.forEach(button => {
            if (button.textContent === hora) {
                button.classList.add('bg-teal-500');
                horarioSeleccionado = button;
            }
        });

        document.getElementById('hora-input').value = hora;
    }

    function updateProfesionalId() {
        const profesionalId = document.getElementById('profesional_id').value;
        document.getElementById('profesional_id-hidden').value = profesionalId;
    }

    @if(Auth::user()->role === 'secretario')
            document.getElementById('dni').addEventListener('blur', function () {
                const dni = this.value.trim();

                // Resto del código para verificar el DNI
                const dniInfo = document.getElementById('dni-info');
                const dniSpinner = document.getElementById('dni-spinner');

                if (dni === '') {
                    dniInfo.textContent = '';
                    return;
                }

                // Mostrar el spinner de DNI
                dniSpinner.style.display = 'block';
                dniInfo.innerHTML = ''; // Limpiar información previa

                fetch('{{ route('verificarDni') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ dni: dni })
                })
                    .then(response => response.json())
                    .then(data => {
                        // Ocultar el spinner de DNI
                        dniSpinner.style.display = 'none';

                        if (data.existe) {
                            dniInfo.innerHTML = `
                                <div style="margin-bottom: 10px;" class="text-green-400">DNI perteneciente a <strong>${data.nombre}</strong></div>
                                `;

                        } else {
                            dniInfo.innerHTML = `
          <div class="text-yellow-400 mb-6">DNI no registrado. Se reservará para DNI: <strong>${dni}</strong></div>
        `;

                        }
                    })
                    .catch(error => {
                        console.error('Error al verificar DNI:', error);
                        dniSpinner.style.display = 'none';
                        dniInfo.innerHTML = `<span class="text-red-500">Error al verificar el DNI.</span>`;
                    });
            });
    @endif


    function validarReserva(event) {
        event.preventDefault();

        const profesionalId = document.getElementById('profesional_id-hidden').value;
        const fecha = document.getElementById('fecha-input').value;
        const hora = document.getElementById('hora-input').value;

        @if(Auth::user()->role === 'secretario')
            const dni = document.getElementById('dni').value.trim();
        @endif

        let mensaje = '';

        if (!profesionalId || !fecha || !hora) {
            mensaje = 'Por favor, asegúrate de seleccionar un profesional, una fecha y una hora antes de reservar.';
        }

        @if(Auth::user()->role === 'secretario')
            if (!dni) {
                mensaje = 'Por favor, ingresa el DNI del paciente.';
            }
        @endif

        if (mensaje) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: mensaje,
            });
            return false;
        } else {
            // Preparar información para la confirmación
            let confirmHtml = `<strong>Profesional:</strong> ${document.querySelector("#profesional_id option:checked").textContent}<br>
                              <strong>Fecha:</strong> ${fecha}<br>
                              <strong>Hora:</strong> ${hora}<br>`;

            @if(Auth::user()->role === 'secretario')
                const dni = document.getElementById('dni').value.trim();
                const dniInfoText = document.getElementById('dni-info').textContent;
                confirmHtml += `<strong>DNI:</strong> ${dni}<br>${dniInfoText}`;
            @endif

            Swal.fire({
                title: '¿Estás seguro de que deseas reservar este turno?',
                html: confirmHtml,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, reservar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar mensaje de éxito antes de enviar
                    Swal.fire({
                        icon: 'success',
                        title: 'Turno reservado correctamente',
                        text: 'Tu turno ha sido reservado con éxito.',
                    }).then(() => {
                        // Enviar el formulario
                        event.target.submit();
                    });
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const fechaInput = document.getElementById('fecha');
        
       
        fechaInput.addEventListener('input', function () {
            const date = new Date(this.value);
            const day = date.getDay();
            
            if (day === 5 || day === 6) { 
                alert('Por favor, selecciona una fecha de lunes a viernes.');
                this.value = '';
            }
        });
    });
</script>

@endsection