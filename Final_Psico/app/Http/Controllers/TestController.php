<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TestController extends Controller
{
    public function show()
    {
        return view('test');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:12|max:99',
            'p1' => 'required|integer|between:0,3',
            'p2' => 'required|integer|between:0,3',
            'p3' => 'required|integer|between:0,3',
            'p4' => 'required|integer|between:0,3',
            'p5' => 'required|integer|between:0,3',
            'p6' => 'required|integer|between:0,3',
            'p7' => 'required|integer|between:0,3',
            'p8' => 'required|integer|between:0,3',
            'p9' => 'required|integer|between:0,3',
        ]);

        // Guardar test en BD
        $test = $this->saveTestResponse($request);
        if (!$test) {
            return response()->json([
                'error' => 'Error al registrar el test.'
            ], 500);
        }

        $inputData = [
            'p1' => (int) $request->p1,
            'p2' => (int) $request->p2,
            'p3' => (int) $request->p3,
            'p4' => (int) $request->p4,
            'p5' => (int) $request->p5,
            'p6' => (int) $request->p6,
            'p7' => (int) $request->p7,
            'p8' => (int) $request->p8,
            'p9' => (int) $request->p9,
        ];

        try {
            $response = Http::timeout(15)->post('http://127.0.0.1:8001/predict', $inputData);
            $score = $response->json()['score'] ?? 0;

            $mensaje = 'Lamina determinó que ';
            if ($score >= 15 && $score <= 19) {
                $this->createAppointment(2);
                $mensaje .= 'usted necesita una cita. Se ha programado una cita en 2 días.';
            } elseif ($score >= 20 && $score <= 27) {
                $this->createAppointment(0);
                $mensaje .= 'usted necesita una cita urgente. Se ha programado una cita para hoy.';
            } else {
                $mensaje .= 'puede ayudarte, dirígete a la pestaña "Lumina te apoya".';
            }

            if ($request->ajax()) {
                return response()->json([
                    'message' => $mensaje,
                    'score' => $score,
                ]);
            }

            return redirect()->route('tests.show')->with('test_result', $mensaje);

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Error al comunicarse con la API: ' . $e->getMessage()], 500);
            }
            return redirect()->route('tests.show')->with('error', 'Error al comunicarse con la API: ' . $e->getMessage());
        }
    }

    private function saveTestResponse(Request $request)
    {
        return Test::create([
            'user_id' => Auth::id(),
            'full_name' => $request->full_name,
            'age' => $request->age,
            'p1' => $request->p1,
            'p2' => $request->p2,
            'p3' => $request->p3,
            'p4' => $request->p4,
            'p5' => $request->p5,
            'p6' => $request->p6,
            'p7' => $request->p7,
            'p8' => $request->p8,
            'p9' => $request->p9,
        ]);
    }

    private function createAppointment($daysOffset = 0)
    {
        $user = Auth::user();
        if (!$user) return;

        $appointmentDate = Carbon::now()->addDays($daysOffset)->startOfDay();

        Appointment::create([
            'user_id' => $user->id,
            'appointment_date' => $appointmentDate,
            'description' => 'Cita generada automáticamente según resultado del PHQ-9.',
        ]);
    }
}
