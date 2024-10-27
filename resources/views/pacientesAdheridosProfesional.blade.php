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
                                                    {{ $turno->paciente->user->name }} <br>
                                                    <small>DNI: <span style="font-weight:700">{{ $turno->paciente->user->dni }}</span></small>

                                                    @if($turno->paciente->obra_social === 'PAMI')
                                                        <br><small>Turnos en el año <strong>PAMI</strong>: <span
                                                                style="font-weight:700">{{ $turno->turnosEnElAno }}</span></small>
                                                    @endif
                                                    <br>
                                                    <small>Teléfono: <span
                                                            style="font-weight:700">{{ $turno->paciente->user->telefono ?? 'No tiene' }}</span></small>
                                                @else
                                                    {{ $turno->paciente_no_registrado_nombre }} <br>
                                                    <small>DNI: <span
                                                            style="font-weight:700">{{ $turno->dni_paciente_no_registrado }}</span></small>

                                                    @if($turno->turnosEnElAno > 0)
                                                        <br><small>Turnos en el año: <span
                                                                style="font-weight:700">{{ $turno->turnosEnElAno }}</span></small>
                                                    @endif
                                                    <br><small>Teléfono: <span
                                                            style="font-weight:700">{{ $turno->paciente_no_registrado_telefono ?? 'No tiene' }}</span></small>
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