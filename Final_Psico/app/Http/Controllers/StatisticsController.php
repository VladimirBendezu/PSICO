<?php


namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cita; // Asegúrate de tener el modelo importado
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        // Define el rango de fechas
        $startDate = Carbon::createFromFormat('Y-m-d', '2024-01-01'); // 1 de enero de 2024
        $endDate = Carbon::now(); // Fecha actual

        // Realiza la consulta para contar las citas por especialidad
        $data = Cita::select('especialidad', \DB::raw('COUNT(*) as count'))
            ->whereBetween('fecha', [$startDate, $endDate]) // Filtra las fechas
            ->groupBy('especialidad')
            ->get();

        return view('statics', compact('data'));
    }
}
