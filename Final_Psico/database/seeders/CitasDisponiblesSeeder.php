<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CitasDisponiblesSeeder extends Seeder
{
 public function run()
{
    DB::table('citas_disponibles')->truncate(); // 👈 Esto borra todo

    $especialidades = [
        'Terapia Narrativa',
        'Psicoanálisis',
        'Terapia psicodinámica',
        'Terapia sistémica',
        'Consulta general',
        'Terapia Cognitivo-Conductual',
        'Terapia de Pareja',
        'Psicología Infantil'
    ];

        $consultorios = ['Consultorio A', 'Consultorio B', 'Consultorio C'];

        $horasManana = ['09:00:00', '10:00:00', '11:00:00', '12:00:00'];
        $horasTarde = ['14:00:00', '15:00:00', '16:00:00', '17:00:00'];

        $citas = [];

        // Generar fechas desde 2025-05-26 hasta 2025-06-07 (2 semanas incluyendo sábados)
        $startDate = Carbon::create(2025, 5, 26);
        $endDate = Carbon::create(2025, 6, 7);

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dayOfWeek = $date->dayOfWeek; // 0: Domingo, 6: Sábado

            // Citas de mañana (lunes a sábado)
            foreach ($horasManana as $hora) {
                $citas[] = [
                    'especialidad' => $especialidades[array_rand($especialidades)],
                    'hora' => $hora,
                    'lugar' => $consultorios[array_rand($consultorios)],
                    'fecha' => $date->toDateString()
                ];
            }

            // Citas de tarde (solo lunes a viernes)
            if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                foreach ($horasTarde as $hora) {
                    $citas[] = [
                        'especialidad' => $especialidades[array_rand($especialidades)],
                        'hora' => $hora,
                        'lugar' => $consultorios[array_rand($consultorios)],
                        'fecha' => $date->toDateString()
                    ];
                }
            }
        }

        DB::table('citas_disponibles')->insert($citas);
    }
}