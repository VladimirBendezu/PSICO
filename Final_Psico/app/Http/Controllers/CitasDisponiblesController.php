<?php

namespace App\Http\Controllers;

use App\Models\CitaDisponible;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CitasDisponiblesController extends Controller
{
    public function index()
    {
        // Obtener la hora actual en la zona horaria de Lima
        $now = Carbon::now('America/Lima')->format('Y-m-d H:i:00');

        // Filtrar citas que están después del momento actual
        $citas = CitaDisponible::whereRaw("STR_TO_DATE(CONCAT(fecha, ' ', hora), '%Y-%m-%d %H:%i:%s') > ?", [$now])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get()
            ->groupBy(function ($cita) {
                return Carbon::parse($cita->fecha)->format('d/m/Y');
            });

        return view('citas_disponibles.index', compact('citas'));
    }
}
