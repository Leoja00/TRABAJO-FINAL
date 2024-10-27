@extends('layouts.app')

@section('title', 'Pacientes adheridos')

@section('contenidoHome')

<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center"
    style="background-image: url('{{ asset('img/turnos.jpg') }}');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

    <div class="relative z-10 w-full px-4 py-8">
        <h1 class="text-3xl text-white text-center mt-24">Pacientes adheridos</h1>

        <!-- Campo de búsqueda -->
        <div class="flex justify-center my-4">
            <div class="relative w-full max-w-md">
                <input type="text" id="dniSearch"
                    class="w-full py-2 px-4 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="Buscar por DNI">
                <button id="clearSearch"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 focus:outline-none">
                    &times;
                </button>
            </div>
        </div>
        <div class="flex justify-center my-4">
    <div class="relative w-full max-w-md">
        <input id="fechaPicker" class="w-full py-2 px-4 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" 
               type="text" placeholder="Seleccionar fecha(s)">
        <button onclick="limpiarFecha()" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 focus:outline-none">
            &times;
        </button>
    </div>
</div>
<div class="flex justify-center space-x-4 my-4">
<button onclick="filtrarTurnosPorFecha()" class="bg-teal-700 text-white py-2 px-4 rounded-lg hover:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-teal-500">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block w-5 h-5 mr-2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
    </svg>
    Filtrar Turnos
</button>

    <button onclick="imprimirTurnos()" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="inline-block w-5 h-5 mr-2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
    </svg>
    Imprimir Turnos
</button>

</div>

        <!-- Tabla de pacientes adheridos -->
        <div class="overflow-x-auto bg-white rounded-lg shadow-lg mt-10">
            <table class="min-w-full bg-white table-auto">
                <thead>
                    <tr class="bg-teal-700 text-white">
                        <th class="py-2 px-4 text-left">ID</th>
                        <th class="py-2 px-4 text-left" id="fechaHoraHeader" style="cursor: pointer;">
                            Fecha y Hora
                            <span id="sortIconFecha">&#8597;</span>
                        </th>
                        <th class="py-2 px-4 text-left">Paciente</th>
                        <th class="py-2 px-4 text-left" id="estadoHeader" style="cursor: pointer;">
                            Estado
                            <span id="sortIconEstado">&#8597;</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="turnosTableBody">
                    @php
                        $orden = 1; // Inicializar contador de orden
                    @endphp
                    @forelse($turnos as $turno)
                                        <tr class="border-b block md:table-row" data-fecha="{{ $turno->dia_hora }}"
                                            data-estado="{{ $turno->estado }}"
                                            data-dni="{{ $turno->paciente->dni ?? $turno->dni_paciente_no_registrado }}">
                                            <td class="py-2 px-4 block md:table-cell" data-label="Orden">
                                                <strong>{{ $orden++ }}</strong> <!-- Mostrar y luego incrementar el contador -->
                                            </td>
                                            <td class="py-2 px-4 block md:table-cell" data-label="Fecha y Hora">
                                                @php
                                                    $fechaHora = \Carbon\Carbon::parse($turno->dia_hora);
                                                @endphp
                                                <strong>D:</strong> {{ $fechaHora->format('d/m/Y') }}<br>
                                                <strong>H:</strong> {{ $fechaHora->format('H:i') }}
                                            </td>
                                            <td class="py-2 px-4 block md:table-cell" style="font-weight: 500;" data-label="Paciente"
                                                data-dni="{{ $turno->paciente->user->dni ?? $turno->dni_paciente_no_registrado }}">
                                                @if($turno->paciente)
                                                {{ $turno->paciente->user->name ?? 'Paciente no disponible' }} <br>
                                                <small>DNI: <span style="font-weight:700">{{ $turno->paciente->user->dni }}</span></small>
                                                @if($turno->turnosEnElAno)
                                                    <br><small>Turnos en el año <strong>PAMI</strong>: <span style="font-weight:700">{{ $turno->turnosEnElAno }}</span></small>
                                                @endif
                                                <br>
                                                <small>Teléfono: <span style="font-weight:700">{{ $turno->paciente->user->telefono ?? 'No tiene' }}</span></small>
                                            @else
                                                {{ $turno->paciente_no_registrado_nombre }} <br>
                                                <small>DNI: <span style="font-weight:700">{{ $turno->dni_paciente_no_registrado }}</span></small>
                                                @if($turno->turnosEnElAno)
                                                    <br><small>Turnos en el año <strong>PAMI</strong>: <span style="font-weight:700">{{ $turno->turnosEnElAno }}</span></small>
                                                @endif
                                                <br><small>Teléfono: <span style="font-weight:700">{{ $turno->paciente_no_registrado_telefono ?? 'No tiene' }}</span></small>
                                            @endif

                                            </td>


                                            <td class="py-2 px-4 block md:table-cell" data-label="Estado">
                                                @if($turno->estado === 'completado')
                                                    <span class="text-green-600" style="font-weight: 600;">Completado</span>
                                                    <!-- @if($turno->paciente)
                                                                                 Enlace para paciente logueado
                                                                                <a href="{{ route('historial.crear', ['paciente_id' => $turno->paciente->id, 'profesional_id' => Auth::user()->id]) }}"
                                                                                    class="text-blue-500 hover:text-blue-700">
                                                                                    Cargar/Completar Historia Clínica
                                                                                </a>
                                                                            @elseif($turno->dni_paciente_no_registrado)
                                                                                Enlace para paciente no logueado 
                                                                                <a href="{{ route('historial.crear', ['paciente_id' => $turno->dni_paciente_no_registrado, 'profesional_id' => Auth::user()->id]) }}"
                                                                                    class="text-blue-500 hover:text-blue-700">
                                                                                    Cargar/Completar Historia Clínica (No logueado)
                                                                                </a>
                                                                            @endif-->
                                                @elseif($turno->estado === 'reservado')
                                                    <span class="text-yellow-600" style="font-weight: 600;">Reservado</span>
                                                @endif
                                            </td>




                                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center">No tienes pacientes adheridos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="mt-4">
                {{ $turnos->links() }}
            </div>
        </div>
    </div>
</div>

<style>
     @media print {
        /* Ocultar todo excepto el área de impresión */
        body * {
            visibility: hidden;
        }

        /* Mostrar solo el contenedor imprimible */
        .printable, .printable * {
            visibility: visible;
        }

        /* Establece el área de impresión como la tabla de turnos */
        .printable {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }
    }
    @media (max-width: 768px) {

        table,
        thead,
        tbody,
        th,
        td,
        tr {
            display: block;
        }

        thead tr {
            display: none;
        }

        tr {
            margin-bottom: 15px;
        }

        td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border: 1px solid #ccc;
        }

        td::before {
            content: attr(data-label);
            font-weight: bold;
            text-transform: uppercase;
        }
    }
</style>

<script>
    flatpickr("#fechaPicker", {
    mode: "range",
    dateFormat: "Y-m-d",
    onClose: function(selectedDates, dateStr, instance) {
        // Opcional: verificar si el usuario seleccionó algo o no
        if (!dateStr) {
            mostrarTodosLosTurnos();
        }
    }
});

function filtrarTurnosPorFecha() {
    let fechasSeleccionadas = document.getElementById('fechaPicker').value.split(' to ');

    let fechaInicio = new Date(fechasSeleccionadas[0]);
    let fechaFin = fechasSeleccionadas[1] ? new Date(fechasSeleccionadas[1]) : fechaInicio;

    let filas = document.querySelectorAll('#turnosTableBody tr');

    filas.forEach(fila => {
        let fechaTurno = new Date(fila.getAttribute('data-fecha'));

        let fechaTurnoStr = fechaTurno.toISOString().split('T')[0];
        let fechaInicioStr = fechaInicio.toISOString().split('T')[0];
        let fechaFinStr = fechaFin.toISOString().split('T')[0];

        if (fechaTurnoStr >= fechaInicioStr && fechaTurnoStr <= fechaFinStr) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

function limpiarFecha() {
    document.getElementById('fechaPicker').value = ''; // Limpiar el campo de fecha
    mostrarTodosLosTurnos(); // Mostrar todos los turnos
}

function mostrarTodosLosTurnos() {
    let filas = document.querySelectorAll('#turnosTableBody tr');
    filas.forEach(fila => {
        fila.style.display = ''; // Mostrar todas las filas
    });
}


function imprimirTurnos() {
    // Obtener el rango de fechas seleccionado
    let fechasSeleccionadas = document.getElementById('fechaPicker').value.split(' to ');
    let fechaInicio = fechasSeleccionadas[0] || '';
    let fechaFin = fechasSeleccionadas[1] || '';

    // Determinar el título según las fechas seleccionadas
    let rangoFechas = fechaInicio 
    ? `Turnos dados en la fecha: ${fechaFin ? `${fechaInicio} al ${fechaFin}` : fechaInicio}` 
    : 'Turnos totales';

let turnosVisibles = '';
let filas = document.querySelectorAll('#turnosTableBody tr');

filas.forEach(fila => {
    if (fila.style.display !== 'none') {
        let filaClone = fila.cloneNode(true);

        // Remover las celdas de "estado" y "turnos en el año"
        let estadoCell = filaClone.querySelector('[data-label="Estado"]');
        if (estadoCell) estadoCell.remove();

        turnosVisibles += filaClone.outerHTML;
    }
});

if (turnosVisibles) {
    // Crear un iframe para imprimir
    const iframe = document.createElement('iframe');
    iframe.style.position = 'absolute';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);

    const doc = iframe.contentWindow.document;
    doc.open();
    doc.write(`<html><head><title>${rangoFechas}</title><link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"></head><body>`);

        doc.write(`<h3><strong>TURNOS:</strong></h3>`);
        doc.write('<hr>');
        doc.write(`<table class="min-w-full bg-white table-auto">${turnosVisibles}</table>`);
        doc.write('</body></html>');
        doc.close();

        iframe.contentWindow.focus();
        iframe.contentWindow.print();

        setTimeout(() => document.body.removeChild(iframe), 1000);
    } else {
        alert('No hay turnos para imprimir.');
    }
}
    document.addEventListener('DOMContentLoaded', function () {
        const dniSearch = document.getElementById('dniSearch');
        const clearSearch = document.getElementById('clearSearch');
        const turnosTableBody = document.getElementById('turnosTableBody');
        const originalRows = Array.from(turnosTableBody.querySelectorAll('tr'));

        // Filtrar por DNI mientras el usuario escribe
        dniSearch.addEventListener('input', function () {
            const filter = dniSearch.value.toLowerCase();
            const rows = Array.from(turnosTableBody.querySelectorAll('tr'));

            if (filter === '') {
                resetTable();
                return;
            }

            rows.forEach(row => {
                const dniPaciente = row.querySelector('[data-dni]')?.getAttribute('data-dni').toLowerCase() || '';
                if (dniPaciente.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        clearSearch.addEventListener('click', function () {
            dniSearch.value = '';
            dniSearch.dispatchEvent(new Event('input'));
        });


        dniSearch.addEventListener('input', function () {
            clearSearch.style.display = dniSearch.value ? 'block' : 'none';
        });

        clearSearch.style.display = dniSearch.value ? 'block' : 'none';

        function resetTable() {
            turnosTableBody.innerHTML = '';
            originalRows.forEach(row => {
                turnosTableBody.appendChild(row);
                row.style.display = '';
            });
        }
    });





    document.addEventListener('DOMContentLoaded', function () {
        const turnosTableBody = document.getElementById('turnosTableBody');
        const fechaHoraHeader = document.getElementById('fechaHoraHeader');
        const estadoHeader = document.getElementById('estadoHeader');
        const sortIconFecha = document.getElementById('sortIconFecha');
        const sortIconEstado = document.getElementById('sortIconEstado');

        let ascendingFecha = true;
        let ascendingEstado = true;

        // Función para ordenar por estado (primero "reservado", luego "completado") y luego por fecha
        function sortByEstadoYFecha(rows) {
            return rows.sort((a, b) => {
                const estadoA = a.getAttribute('data-estado');
                const estadoB = b.getAttribute('data-estado');
                const fechaA = new Date(a.getAttribute('data-fecha'));
                const fechaB = new Date(b.getAttribute('data-fecha'));

                if (estadoA === estadoB) {
                    return ascendingFecha ? fechaB - fechaA : fechaA - fechaB; // Ordenar por fecha si el estado es el mismo
                }

                // Ordenar "reservado" antes que "completado"
                if (ascendingEstado) {
                    return estadoA === 'reservado' ? -1 : 1;
                } else {
                    return estadoB === 'reservado' ? -1 : 1;
                }
            });
        }

        // EVENTO DE ORDENACIÓN POR FECHA Y ESTADO
        fechaHoraHeader.addEventListener('click', () => {
            const rows = Array.from(turnosTableBody.querySelectorAll('tr'));
            ascendingFecha = !ascendingFecha;
            const sortedRows = sortByEstadoYFecha(rows);
            turnosTableBody.innerHTML = '';
            sortedRows.forEach(row => turnosTableBody.appendChild(row));
            sortIconFecha.innerHTML = ascendingFecha ? '&#8593;' : '&#8595;';
        });

        estadoHeader.addEventListener('click', () => {
            const rows = Array.from(turnosTableBody.querySelectorAll('tr'));
            ascendingEstado = !ascendingEstado;
            const sortedRows = sortByEstadoYFecha(rows);
            turnosTableBody.innerHTML = '';
            sortedRows.forEach(row => turnosTableBody.appendChild(row));
            sortIconEstado.innerHTML = ascendingEstado ? '&#8593;' : '&#8595;';
        });

        // Orden inicial al cargar la página
        const rows = Array.from(turnosTableBody.querySelectorAll('tr'));
        const sortedRows = sortByEstadoYFecha(rows);
        turnosTableBody.innerHTML = '';
        sortedRows.forEach(row => turnosTableBody.appendChild(row));
    });
</script>

@endsection