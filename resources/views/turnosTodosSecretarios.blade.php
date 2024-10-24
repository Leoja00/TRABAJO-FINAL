@extends('layouts.app')

@section('title', 'Turnos Solicitados')

@section('contenidoHome')

<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center"
    style="background-image: url('{{ asset('img/turnos.jpg') }}');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

    <div class="relative z-10 w-full px-4 py-8">
        <h1 class="text-3xl text-white text-center mt-24">Turnos Solicitados</h1>
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

        <div class="overflow-x-auto bg-white rounded-lg shadow-lg mt-10">
            <table class="min-w-full bg-white table-auto">
                <thead>
                    <tr class="bg-teal-700 text-white">
                        <th class="py-2 px-4 text-left">ID</th>
                        <th class="py-2 px-4 text-left">Paciente</th>
                        <th class="py-2 px-4 text-left">Profesional</th>
                        <th class="py-2 px-4 text-left">Especialidad</th>
                        <th class="py-2 px-4 text-left" id="fechaHoraHeader" style="cursor: pointer;">
                            Fecha y Hora
                            <span id="sortIconFecha">&#8597;</span>
                        </th>
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
                                            data-dni="{{ $turno->paciente ? $turno->paciente->user->dni : $turno->dni_paciente_no_registrado }}">
                                            <td class="py-2 px-4 block md:table-cell" data-label="Orden">
                                                <strong>{{ $orden++ }}</strong> <!-- Mostrar y luego incrementar el contador -->
                                            </td>
                                            <td class="py-2 px-4 block md:table-cell" style="font-weight: 500;" data-label="Paciente">
                                                @if($turno->paciente)
                                                    {{ $turno->paciente->user->name ?? 'Paciente no disponible' }} <br>
                                                    <small>DNI: <span style="font-weight:700">{{ $turno->paciente->user->dni }}</span></small>
                                                    @if($turno->paciente->obra_social === 'PAMI')
                                                        <br><small>Turnos en el año <strong>PAMI</strong>: <span
                                                                style="font-weight:700">{{ $turno->turnosEnElAno }}</span></small>
                                                    @endif
                                                @else
                                                    {{ $turno->paciente_no_registrado_nombre }} <br>
                                                    <small>DNI: <span
                                                            style="font-weight:700">{{ $turno->dni_paciente_no_registrado }}</span></small>
                                                    @if($turno->paciente_no_registrado_nombre === 'PAMI')
                                                        <br><small>Turnos en el año: <span
                                                                style="font-weight:700">{{ $turno->turnosEnElAno }}</span></small>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 block md:table-cell" style="font-weight: 500;" data-label="Profesional">
                                                {{ $turno->profesional?->user?->name ?? 'Profesional no disponible' }}
                                            </td>
                                            <td class="py-2 px-4 block md:table-cell" style="font-weight: 400;" data-label="Especialidad">
                                                {{ $turno->profesional?->especialidad ?? 'Especialidad no disponible' }}
                                            </td>
                                            <td class="py-2 px-4 block md:table-cell" data-label="Fecha y Hora">
                                                @php
                                                    $fechaHora = \Carbon\Carbon::parse($turno->dia_hora);
                                                @endphp
                                                <strong>D:</strong> {{ $fechaHora->format('d/m/Y') }}<br>
                                                <strong>H:</strong> {{ $fechaHora->format('H:i') }}
                                            </td>
                                            <td class="py-2 px-4 block md:table-cell" data-label="Estado">
                                                @if($turno->estado === 'completado')
                                                    <span class="text-green-600" style="font-weight: 700;">Completado</span>
                                                @elseif($turno->estado === 'reservado')
                                                    <span class="text-yellow-600" style="font-weight: 600;">Reservado</span>
                                                    <form id="cancelar-secretario-form-{{ $turno->id }}"
                                                        action="{{ route('turno.cancelarSecretario', $turno->id) }}" method="POST"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="bg-rose-500 text-white py-1 px-3 rounded hover:bg-rose-700"
                                                            onclick="confirmarCancelacionSecretario({{ $turno->id }})">Cancelar turno</button>

                                                    </form>

                                                @else
                                                    <span class="text-gray-600" style="font-weight: 600;">{{ ucfirst($turno->estado) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center">No tienes turnos solicitados.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
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
    function confirmarCancelacionSecretario(turnoId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esta acción.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cancelar turno',
            cancelButtonText: 'No, volver'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Turno cancelado correctamente',
                    text: 'El turno ha sido cancelado con éxito.',
                }).then(() => {
                    document.getElementById('cancelar-secretario-form-' + turnoId).submit();
                });
            } else {
                Swal.close();
            }
        });
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
                const dniPaciente = row.getAttribute('data-dni')?.toLowerCase() || '';
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
                    return ascendingFecha ? fechaB - fechaA : fechaA - fechaB;
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