<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;


class CitaController extends Controller
{
    public function create()
    {
        return view('citascompleta');
    }

    public function store(Request $request)
    {
        $request->validate([
            'especialidad' => 'required|string|max:255',
            'edad' => 'required|integer',
            'genero' => 'required|string',
            'ubicacion' => 'required|string',
            'motivo' => 'required|string',
            'exp_prev' => 'required|integer',
            'dur_cita' => 'required|integer',
            'fecha' => 'required|date',
            'eventos_locales' => 'required|boolean',
            'condiciones_climaticas' => 'required|string',
            'promociones' => 'required|integer',
            'num_medicos' => 'required|integer',
            'tiempo_espera' => 'required|integer',
            'demografia' => 'required|string',
        ]);

        Cita::create($request->all());

        return redirect()->route('home')->with('test_result', 'Datos registrados correctamente');
    }
}