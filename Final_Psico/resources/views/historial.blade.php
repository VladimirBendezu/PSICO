@extends('adminlte::page')

@section('title', 'Historial de Citas')

@section('content_header')
    <h1>Historial de Citas</h1>
@stop

@section('content')
    <p>A continuación se muestra el historial de citas anteriores:</p>

    @if ($historialCitas->isEmpty())
        <div class="alert alert-warning" role="alert">
            No tienes citas en el historial.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($historialCitas as $cita)
                        <tr>
                            <td>{{ $cita->description }}</td>
                            <td>{{ $cita->appointment_date->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@stop

@section('css')
    <style>

        /* Estilo para el título en content_header */
        .content-header h1 {
            color: white !important;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa; /* Fondo claro para contraste */
        }

        h1 {
            margin-bottom: 20px;
            color: #343A40;
        }

        .alert-warning {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            border-radius: 6px;
        }

        .table-responsive {
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
        }

        .table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            background-color: #343A40;
            color: #f8f9fa; /* Texto claro */
        }

        .table th, .table td {
            padding: 14px 20px;
            border-bottom: 1px solid #495057;
            text-align: left;
            vertical-align: middle;
        }

        .table thead {
            background-color: #212529; /* Más oscuro que la tabla */
        }

        .table th {
            font-weight: 600;
            color: #f8f9fa; /* Texto claro */
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #2c3036; /* Tonos más oscuros para rayado */
        }

        .table-striped tbody tr:hover {
            background-color: #495057; /* Hover ligeramente más claro */
        }
    </style>
@stop