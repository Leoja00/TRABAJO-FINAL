@extends('layouts.app')

@section('title', 'Turnos Solicitados')

@section('contenidoHome')

<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center"
    style="background-image: url('{{ asset('img/turnos.jpg') }}');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

    <div class="relative z-10 w-full px-4 py-8">
        <h1 class="text-3xl text-white text-center mt-24">Turnos Solicitados</h1>

        @if(Auth::user()->paciente->obra_social === 'PAMI')
            <div class="flex justify-center">
                <div class="inline-block mx-auto my-4 bg-white bg-opacity-80 rounded-lg shadow-lg p-4">
                    <p class="text-lg text-sky-950	">Turnos reservados con la obra social de <strong>PAMI</strong> en el año
                        <strong>{{ $turnosEnElAno }}</strong>
                    </p>


                </div>
            </div>


        @endif

        <div class="overflow-x-auto bg-white rounded-lg shadow-lg mt-10">
            <table class="min-w-full bg-white table-auto">
                <thead>
                    <tr class="bg-teal-700 text-white">
                        <th class="py-2 px-4 text-left">ID</th>
                        <th class="py-2 px-4 text-left" id="fechaHoraHeader" style="cursor: pointer;">
                            Fecha y Hora
                            <span id="sortIconFecha">&#8597;</span>
                        </th>
                        <th class="py-2 px-4 text-left">Profesional</th>
                        <th class="py-2 px-4 text-left">Especialidad</th>
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
                                            data-estado="{{ $turno->estado }}">
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
                                            <td class="py-2 px-4 block md:table-cell" style="font-weight: 500;" data-label="Profesional">
                                                {{ $turno->profesional->user->name }}
                                            </td>
                                            <td class="py-2 px-4 block md:table-cell" style="font-weight: 400;" data-label="Especialidad">
                                                {{ $turno->profesional->especialidad }}
                                            </td>
                                            <td class="py-2 px-4 block md:table-cell" data-label="Estado">
                                                @if($turno->estado === 'completado')
                                                    <span class="text-green-600" style="font-weight: 700;">Completado</span>
                                                @elseif($turno->estado === 'reservado')
                                                    <span class="text-yellow-600" style="font-weight: 600;">Reservado</span>
                                                    <form id="cancelar-form-{{ $turno->id }}" action="{{ route('turno.cancelar', $turno->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="bg-rose-500 text-white py-1 px-3 rounded hover:bg-rose-700"
                                                            onclick="confirmarCancelacion({{ $turno->id }})">Cancelar</button>
                                                    </form>
                                                @endif

                                            </td>
                                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center">No tienes turnos solicitados.</td>
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

    function confirmarCancelacion(turnoId) {
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

                    document.getElementById('cancelar-form-' + turnoId).submit();
                });
            } else {
                Swal.close();
            }
        });
    }


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