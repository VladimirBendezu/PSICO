<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrediccionController extends Controller
{
    public function index(Request $request)
{
    if ($request->isMethod('post')) {
        // Aquí iría la lógica para realizar la predicción
        // Simulación de resultado de predicción
        $predicciones = [
            ['especialidad' => 'Cita Agendada Test', 'prediccion' => 22.19],
            ['especialidad' => 'Consulta general', 'prediccion' => 22.63],
            ['especialidad' => 'Psicoanalisis', 'prediccion' => 22.81],
            ['especialidad' => 'Terapia Narrativa', 'prediccion' => 22.07],
            ['especialidad' => 'Terapia psicodinamica', 'prediccion' => 22.75],
            ['especialidad' => 'Terapia sistemica', 'prediccion' => 22.50],
        ];

        // Redondear las predicciones
        foreach ($predicciones as &$prediccion) {
            $prediccion['redondeado'] = round($prediccion['prediccion']);
        }

        return response()->json(['result' => $predicciones]);
    }

    return view('predecir'); // Muestra la vista si es un GET
}

}
