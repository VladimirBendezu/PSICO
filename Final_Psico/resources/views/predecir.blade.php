{{-- resources/views/predecir.blade.php --}}
@extends('adminlte::page')

@section('title', 'Predecir')

@section('content')
    <h2>Predecir</h2>
    <div class="mt-3">
        <button id="make-prediction" class="btn btn-success">Realizar Predicción</button>
    </div>

    <div id="prediction-result" class="mt-3"></div> {{-- Div para mostrar resultados de la predicción --}}
    <div class="text-center mt-4">
        <canvas id="prediction-chart" width="300" height="300"></canvas> {{-- Canvas para el gráfico circular --}}
    </div>
@stop

@section('css')
    <style>
        .table th, .table td {
            color: white; /* Cambiar color de letras de la tabla a blanco */
        }
        .table {
            background-color: #343a40; /* Fondo oscuro para la tabla */
        }
    </style>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> {{-- Agregar Chart.js --}}
    <script>
        $(document).ready(function() {
            // Lógica para realizar la predicción al hacer clic en el botón
            $('#make-prediction').on('click', function() {
                $.ajax({
                    url: '{{ route('predecir.index') }}', // Ruta para la misma vista
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}' // Token CSRF para la seguridad
                    },
                    success: function(response) {
                        // Crear tabla para mostrar resultados de la predicción
                        let tableHtml = '<table class="table table-bordered"><thead><tr><th>Especialidad</th><th>Predicción</th><th>Redondeado</th></tr></thead><tbody>';

                        let roundData = []; // Para los datos redondeados del gráfico

                        response.result.forEach(function(item) {
                            tableHtml += '<tr><td>' + item.especialidad + '</td><td>' + item.prediccion.toFixed(2) + '</td><td>' + item.redondeado + '</td></tr>';
                            roundData.push(item.redondeado); // Guardar datos redondeados
                        });

                        tableHtml += '</tbody></table>';

                        $('#prediction-result').html(tableHtml);

                        // Crear gráfico circular
                        const ctx = document.getElementById('prediction-chart').getContext('2d');
                        const chart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: response.result.map(item => item.especialidad),
                                datasets: [{
                                    label: 'Predicciones Redondeadas',
                                    data: roundData,
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(153, 102, 255, 0.2)',
                                        'rgba(255, 159, 64, 0.2)',
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)',
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false, // Permite ajustar el tamaño sin perder proporciones
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: {
                                            color: 'white' // Cambiar color de letras de la leyenda a blanco
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Demanda de Citas por Especialidad',
                                        color: 'white' // Cambiar color del título a blanco
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2); // Formato del tooltip
                                            }
                                        },
                                        titleColor: 'white' // Cambiar color del título del tooltip a blanco
                                    }
                                }
                            }
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        $('#prediction-result').html('<div class="alert alert-danger">Error al realizar la predicción.</div>');
                    }
                });
            });
        });
    </script>
@stop
