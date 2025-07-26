<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CitaDisponible; 

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Nos aseguramos de que el usuario esté autenticado
    }

    public function index(Request $request)
    {
        // Obtener el mensaje de éxito de la sesión, en caso de que exista
        $testRegistered = $request->session()->get('test_registered');

        // Obtener todas las citas disponibles
        $citas = CitaDisponible::all(); 

        // Devolver la vista home con el mensaje de éxito y las citas
        return view('home', compact('testRegistered', 'citas'));
    }

    public function completeTest()
    {

        // Almacenar el mensaje de éxito en la sesión
        return redirect()->route('home')->with('test_registered', true);
    }
}
