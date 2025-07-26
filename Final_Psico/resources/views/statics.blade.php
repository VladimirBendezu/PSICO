{{-- resources/views/statics.blade.php --}}
@extends('adminlte::page')

@section('title', 'Gráficos Estadísticos')

@section('content')
    <h2>Gráficos Estadísticos de Citas por Especialidad</h2>
    <canvas id="myChart" width="400" height="200"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('myChart').getContext('2d');
            var specialtiesData = @json($data); // Cambia aquí a $data para que coincida con el controlador

            var labels = specialtiesData.map(function (item) {
                return item.especialidad; // Asegúrate de que este campo coincida con tu base de datos
            });
            var dataCounts = specialtiesData.map(function (item) {
                return item.count; // Asegúrate de que este campo coincida con el alias que utilizaste en el controlador
            });

            var myChart = new Chart(ctx, {
                type: 'bar', // Cambia el tipo de gráfico según sea necesario
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Cantidad de Citas',
                        data: dataCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad de Citas',
                                color: 'white' // Color del título del eje Y
                            },
                            ticks: {
                                color: 'white' // Color de las etiquetas del eje Y
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Especialidad',
                                color: 'white' // Color del título del eje X
                            },
                            ticks: {
                                color: 'white' // Color de las etiquetas del eje X
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white' // Color de las etiquetas de la leyenda
                            }
                        }
                    }
                }
            });
        });
    </script>
@stop
