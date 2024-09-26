@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center" style="background-image: url('{{ asset('img/panel.jpg') }}');">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    
    <div class="relative z-10 w-full px-4 py-8">
        <h1 class="text-3xl text-white text-center mt-24">Administrar Usuarios</h1>

        <div class="overflow-x-auto bg-white rounded-lg shadow-lg mt-10">
            <table class="min-w-full bg-white table-auto">
                <thead>
                    <tr class="bg-teal-700 text-white">
                        <th class="py-2 px-4 text-left">ID</th>
                        <th class="py-2 px-4 text-left">Nombre</th>
                        <th class="py-2 px-4 text-left">Email</th>
                        <th class="py-2 px-4 text-left">Rol</th>
                        <th class="py-2 px-4 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="border-b block md:table-row">
                            <td class="py-2 px-4 block md:table-cell" data-label="ID">{{ $user->id }}</td>
                            <td class="py-2 px-4 block md:table-cell" data-label="Nombre">{{ $user->name }}</td>
                            <td class="py-2 px-4 block md:table-cell" data-label="Email">{{ $user->email }}</td>
                            <td class="py-2 px-4 block md:table-cell" data-label="Rol">{{ $user->role === 'admin' ? 'Administrador' : ucfirst($user->role) }}</td>
                            <td class="py-2 px-4 block md:table-cell" data-label="Acciones">
    @if($user->role !== 'admin')
        <form action="{{ route('admin.changeRole', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <select name="role" class="border border-gray-300 rounded px-2 py-1">
                @if($user->role !== 'paciente')
                    <option value="paciente">Paciente</option>
                @endif
                @if($user->role !== 'profesional')
                    <option value="profesional">Profesional</option>
                @endif
                @if($user->role !== 'secretario')
                    <option value="secretario">Secretario</option>
                @endif
            </select>
            <button type="submit" class="ml-2 px-4 py-1 bg-teal-700 text-white rounded">Cambiar</button>
        </form>
    @else
        <span>-----------------</span> 
    @endif
</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Responsive Styles */
@media (max-width: 768px) {
    table, thead, tbody, th, td, tr {
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
@endsection
