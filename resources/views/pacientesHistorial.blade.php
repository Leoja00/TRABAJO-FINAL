@extends('layouts.app')

@section('title', 'Historial clínico')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center"
    style="background-image: url('{{ asset('img/medico.jpeg') }}')">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative flex flex-col items-center justify-start min-h-screen space-y-8 pt-24">

        <div class="relative z-10 w-full px-4 py-8">
            <h1 class="text-3xl text-white text-center ">Pacientes Adheridos</h1>

            <!-- Campo de búsqueda -->
            <div class="flex justify-center my-4">
                <div class="relative w-full max-w-md">
                    <input type="text" id="dniSearch"
                        class="w-full py-2 px-4 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Buscar por DNI">
                    <button id="clearSearch"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500"
                        style="display: none;">
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
                            <th class="py-2 px-4 text-left">Paciente</th>
                            <th class="py-2 px-4 text-left">DNI</th>
                            <th class="py-2 px-4 text-left">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="pacientesTableBody">
                        @forelse($pacientesPaginated as $index => $paciente)
                            <tr class="border-b" data-dni="{{ $paciente->dni }}">
                                <td class="py-2 px-4"><strong>{{ $pacientesPaginated->firstItem() + $index }}</strong></td>
                                <!-- Número de ordenamiento -->
                                <td class="py-2 px-4" style="font-weight: 500;">
                                    {{ $paciente->name }}
                                </td>
                                <td class="py-2 px-4">
                                    <span style="font-weight: 700">{{ $paciente->dni }}</span>
                                </td>
                                <td class="py-2 px-4">

                                    <!-- Enlace para cargar historia clínica -->
                                    <a href="{{ route('historial.crear', ['paciente_id' => $paciente->id, 'profesional_id' => Auth::user()->id]) }}"
                                        class="inline-block px-4 py-2 text-white bg-green-500 rounded-md shadow hover:bg-green-700 transition duration-200">
                                        Crear/Modificar Historia Clínica
                                    </a>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center">No tienes pacientes adheridos.</td>
                            </tr>
                        @endforelse
                    </tbody>


                </table>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $pacientesPaginated->links() }}
                </div>
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
        const pacientesTableBody = document.getElementById('pacientesTableBody');
        const originalRows = Array.from(pacientesTableBody.querySelectorAll('tr'));

        // Filtrar por DNI mientras el usuario escribe
        dniSearch.addEventListener('input', function () {
            const filter = dniSearch.value.toLowerCase();
            const rows = Array.from(pacientesTableBody.querySelectorAll('tr'));

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

            clearSearch.style.display = dniSearch.value ? 'block' : 'none';
        });

        clearSearch.addEventListener('click', function () {
            dniSearch.value = '';
            dniSearch.dispatchEvent(new Event('input'));
        });

        function resetTable() {
            pacientesTableBody.innerHTML = '';
            originalRows.forEach(row => {
                pacientesTableBody.appendChild(row);
                row.style.display = '';
            });
        }
    });
</script>

@endsection