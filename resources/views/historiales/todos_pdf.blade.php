<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Clínica del Paciente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            position: relative; /* Añadir posición relativa para el footer */
        }
        h1{
            text-align: left;
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }
        h2{
            text-align: left;
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .historial-header {
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
        }
        .historial-section {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .logo {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .logo img {
            width: 100px;
        }
        .footer {
            text-align: left; /* Alinear a la izquierda */
            margin-top: 20px;
            position: absolute; /* Para colocar en la parte inferior */
            bottom: 10px; /* Espacio desde el fondo */
            left: 10px; /* Espacio desde el lado izquierdo */
        }
        .footer img {
            width: 100px;
        }
    </style>
</head>

    <div class="logo">
        <img src="{{ public_path('img/pdf.png') }}" alt="CDRM Logo">
    </div>

    <h1>Historia Clínica de {{ $paciente->user->name ?? $pacienteNoLogueado->name }}</h1>

    <h2>Datos del paciente:</h2>
    <table>
        <tr>
            <th>Nombre</th>
            <td>{{ $paciente->user->name ?? $pacienteNoLogueado->name }}</td>
        </tr>
        <tr>
            <th>DNI</th>
            <td>{{ $paciente->user->dni ?? $pacienteNoLogueado->dni }}</td>
        </tr>
        <tr>
            <th>Teléfono</th>
            <td>{{ $paciente->user->telefono ?? $pacienteNoLogueado->telefono }}</td>
        </tr>
        <tr>
            <th>Dirección</th>
            <td>{{ $paciente->user->direccion ?? $pacienteNoLogueado->direccion }}</td>
        </tr>
        <tr>
            <th>Obra Social</th>
            <td>{{ $paciente->obra_social ?? $pacienteNoLogueado->obra_social }}</td>
        </tr>
    </table>

    @foreach($historiales as $historial)
        <h2 class="historial-header"> ID: {{ $historial->id }} (Fecha: {{ $historial->created_at->format('d/m/Y') }} - Hora: {{ $historial->created_at->format('h:i A') }})</h2>

        <div class="historial-section">
            <strong>Profesional:</strong> {{ $historial->profesional->user->name }}
        </div>
        <div class="historial-section">
            <strong>Tensión Arterial:</strong> {{ $historial->tension_arterial }}
        </div>
        <div class="historial-section">
            <strong>Peso:</strong> {{ $historial->peso }}
        </div>
        <div class="historial-section">
            <strong>Motivo de Consulta:</strong> {{ $historial->motivo_consulta }}
        </div>
        <div class="historial-section">
            <strong>Diagnóstico:</strong> {{ $historial->diagnostico }}
        </div>
        <div class="historial-section">
            <strong>Tratamiento e Indicaciones:</strong> {{ $historial->tratamiento_indicaciones }}
        </div>
        <hr>
        
    @endforeach

    <div class="footer">
        <img src="{{ public_path('img/pdf.png') }}" alt="CDRM Logo">
    </div>

</body>
</html>
