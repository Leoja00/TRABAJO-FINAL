@extends('layouts.app')

@section('title', 'Turno')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center" style="background-image: url('{{ asset('img/turnos.jpg') }}');">
    <div class="absolute inset-0" style="background-color: rgba(0, 0, 0, 0.5);"></div> 
    <div class="container mx-auto p-4 text-center relative z-10"> 
        <h1 class="text-white text-3xl font-bold mb-6 mt-40">Reservar Turno</h1>

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

        <!-- Formulario para seleccionar fecha -->
        <form method="GET" action="{{ route('turno.sacar') }}" id="fecha-form" class="mb-6">
            <label for="fecha" class="block text-lg font-medium mb-2">Seleccionar fecha:</label>
            <input type="date" id="fecha" name="fecha" value="{{ $fecha }}"
                min="{{ \Carbon\Carbon::today()->toDateString() }}" class="border p-2 rounded mx-auto block w-1/3"
                onchange="this.form.submit()">
        </form>

        <!-- Formulario para reservar turno -->
        <form method="POST" action="{{ route('turno.guardar') }}">
            @csrf
            <input type="hidden" name="fecha" value="{{ $fecha }}">

            <!-- Selección de profesional -->
            <div class="mb-6">
                <label for="profesional_id" class="block text-lg font-medium mb-2">Selecciona un profesional:</label>
                <select id="profesional_id" name="profesional_id" required
                    class="border p-2 rounded w-1/3 mx-auto block">
                    <option value="">-- Selecciona un profesional --</option>
                    @foreach($profesionales as $profesional)
                    <option value="{{ $profesional->id }}">{{ $profesional->user->name }} - {{ $profesional->especialidad }}</option>
                    @endforeach
                </select>
                @error('profesional_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Horarios disponibles -->
            <div class="mb-6">
                <label class="block text-lg font-medium mb-4">Horarios disponibles:</label>
                @if(count($horariosDisponibles) > 0)
                <div class="grid grid-cols-2 gap-4 max-w-md mx-auto">
                    @foreach($horariosDisponibles as $hora)
                    <label class="flex justify-center items-center border border-gray-300 rounded-md p-2 hover:bg-gray-100 cursor-pointer">
                        <input type="radio" name="hora" value="{{ $hora }}" required class="mr-2">
                        {{ $hora }}
                    </label>
                    @endforeach
                </div>
                @else
                <p class="text-red-500">No hay horarios disponibles para esta fecha.</p>
                @endif
                @error('hora')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botón de enviar -->
            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-md"
                {{ count($horariosDisponibles) === 0 ? 'disabled' : '' }}>
                Reservar Turno
            </button>
        </form>
    </div>
</div>
@endsection
