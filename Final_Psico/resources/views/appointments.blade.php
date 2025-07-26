@extends('adminlte::page')

@section('title', 'Tus Citas')

@section('content')
    <h2>Tus Citas</h2>

    @if ($appointments->isEmpty())
        <p>No tienes citas registradas.</p>
    @else
        <div class="row">
            @foreach ($appointments as $appointment)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            Cita para: {{ $appointment->appointment_date->format('d/m/Y') }}
                        </div>
                        <div class="card-body">
                            <p>{{ $appointment->description }}</p> <!-- Muestra la descripción de la cita -->
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@stop

@section('js')
    <!-- Incluir Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script>
        @if (session('success'))
            toastr.success('{{ session('success') }}');
        @elseif (session('error'))
            toastr.error('{{ session('error') }}');
        @endif
    </script>
@stop
