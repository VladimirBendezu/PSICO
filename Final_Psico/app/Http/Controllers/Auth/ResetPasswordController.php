<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Asegúrate de que esta ruta sea correcta
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/home';

    public function showResetForm()
    {
        return view('auth.passwords.change'); // Asegúrate de que esta vista exista
    }

    public function reset(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user(); // Asegúrate de que sea un modelo de User

        if (!$user) {
            return redirect()->route('login')->withErrors(['current_password' => 'Debes estar autenticado para cambiar la contraseña.']);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('password.change')->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        // Cambiar la contraseña
        $user->password = Hash::make($request->password);
        $user->save(); // Este método debería funcionar correctamente ahora

        return redirect($this->redirectTo)->with('status', 'Contraseña cambiada con éxito.');
    }
}
