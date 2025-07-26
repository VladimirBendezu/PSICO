<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\CitaDisponible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Método para almacenar una nueva cita
    public function store(Request $request)
    {
        // Determina si la cita se está creando desde un test o desde la lista de citas disponibles
        if ($request->has('cita_id')) {
            // Lógica para agendar desde las citas disponibles
            $citaDisponible = CitaDisponible::findOrFail($request->cita_id);
            $appointmentDate = Carbon::parse($citaDisponible->fecha . ' ' . $citaDisponible->hora);

            // Crear una nueva cita basada en la cita disponible
            Appointment::create([
                'user_id' => Auth::id(),
                'appointment_date' => $appointmentDate,
                'description' => 'Cita para ' . $citaDisponible->especialidad,
            ]);

            // Eliminar la cita disponible
            $citaDisponible->delete();

            // Mensaje que incluye la fecha de la cita y prioridad
            $message = 'Cita para: ' . $appointmentDate->format('d/m/Y') . ' Cita agendada correctamente';

            return redirect()->route('citas.index')->with('success', $message);
        } else {
            // Lógica para agendar directamente desde el test o desde una solicitud genérica
            $request->validate([
                'appointment_date' => 'required|date',
            ]);

            // Crear una nueva cita
            $appointmentDate = Carbon::parse($request->appointment_date);
            Appointment::create([
                'user_id' => Auth::id(),
                'appointment_date' => $appointmentDate,
                'description' => 'Cita programada luego de resolver el test',
            ]);

            // Mensaje que incluye la fecha de la cita y prioridad
            $message = 'Cita para: ' . $appointmentDate->format('d/m/Y') . ' Cita agendada correctamente';

            return redirect()->route('home')->with('success', $message);
        }
    }

    // Método para mostrar las citas del usuario autenticado
    public function index()
    {
        // Obtiene la fecha de ayer
        $yesterday = now()->subDay();

        // Recupera las citas del usuario que son posteriores a ayer
        $appointments = Appointment::where('user_id', Auth::id())
            ->where('appointment_date', '>', $yesterday)
            ->get();

        return view('appointments', compact('appointments'));
    }
}
