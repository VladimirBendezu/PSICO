<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistorialCitasController extends Controller
{
    public function index()
    {
        // Obtiene la fecha y hora actual
        $now = now();

        // Recupera las citas anteriores (pasadas) del usuario actual
        $historialCitas = Appointment::where('user_id', Auth::id())
            ->where('appointment_date', '<', $now)
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Verifica si es una solicitud AJAX
        if (request()->ajax()) {
            return response()->json($historialCitas);
        }

        // Retorna la vista con las citas anteriores
        return view('historial', compact('historialCitas'));
    }
}