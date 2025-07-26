<?php

namespace App\Http\Controllers;

use App\Models\PersonalityTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class PersonalityTestController extends Controller
{
    // Mostrar el formulario del test
    public function show()
    {
        return view('personality-test');
    }

    // Manejar la sumisión del formulario del test
    public function submit(Request $request)
    {
        // Validar las respuestas del test
        $request->validate([
            'ap_1' => 'required|integer|between:1,5',
            'ap_2' => 'required|integer|between:1,5',
            'ap_3' => 'required|integer|between:1,5',
            'ap_4' => 'required|integer|between:1,5',
            'ap_5' => 'required|integer|between:1,5',
            're_1' => 'required|integer|between:1,5',
            're_2' => 'required|integer|between:1,5',
            're_3' => 'required|integer|between:1,5',
            're_4' => 'required|integer|between:1,5',
            're_5' => 'required|integer|between:1,5',
            'ex_1' => 'required|integer|between:1,5',
            'ex_2' => 'required|integer|between:1,5',
            'ex_3' => 'required|integer|between:1,5',
            'ex_4' => 'required|integer|between:1,5',
            'ex_5' => 'required|integer|between:1,5',
            'am_1' => 'required|integer|between:1,5',
            'am_2' => 'required|integer|between:1,5',
            'am_3' => 'required|integer|between:1,5',
            'am_4' => 'required|integer|between:1,5',
            'am_5' => 'required|integer|between:1,5',
            'ne_1' => 'required|integer|between:1,5',
            'ne_2' => 'required|integer|between:1,5',
            'ne_3' => 'required|integer|between:1,5',
            'ne_4' => 'required|integer|between:1,5',
            'ne_5' => 'required|integer|between:1,5',
        ]);

        // Guardar las respuestas del test en la base de datos
        $personalityTest = $this->saveTestResponse($request);

        if ($personalityTest) {
            // Enviar los datos al API de Flask para obtener la recomendación
            $recommendation = $this->getRecommendation($request->all());

            // Verificar si la petición es AJAX
            if ($request->ajax() || $request->wantsJson()) {
                if ($recommendation && !str_contains($recommendation, 'Error')) {
                    return response()->json([
                        'success' => true,
                        'message' => $recommendation
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'error' => $recommendation ?: 'Error al obtener recomendaciones'
                    ], 500);
                }
            }

            // Para peticiones normales (no AJAX)
            return redirect()->route('home')->with('success', $recommendation);
        }

        // Manejo de errores
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'error' => 'Hubo un error al registrar el test. Intenta nuevamente.'
            ], 500);
        }

        return redirect()->back()->with('error', 'Hubo un error al registrar el test. Intenta nuevamente.');
    }

    // Guardar las respuestas del test en la base de datos
    private function saveTestResponse(Request $request): ?PersonalityTest
    {
        try {
            return PersonalityTest::create([
                'user_id' => Auth::id(),
                'ap_1' => $request->input('ap_1'),
                'ap_2' => $request->input('ap_2'),
                'ap_3' => $request->input('ap_3'),
                'ap_4' => $request->input('ap_4'),
                'ap_5' => $request->input('ap_5'),
                're_1' => $request->input('re_1'),
                're_2' => $request->input('re_2'),
                're_3' => $request->input('re_3'),
                're_4' => $request->input('re_4'),
                're_5' => $request->input('re_5'),
                'ex_1' => $request->input('ex_1'),
                'ex_2' => $request->input('ex_2'),
                'ex_3' => $request->input('ex_3'),
                'ex_4' => $request->input('ex_4'),
                'ex_5' => $request->input('ex_5'),
                'am_1' => $request->input('am_1'),
                'am_2' => $request->input('am_2'),
                'am_3' => $request->input('am_3'),
                'am_4' => $request->input('am_4'),
                'am_5' => $request->input('am_5'),
                'ne_1' => $request->input('ne_1'),
                'ne_2' => $request->input('ne_2'),
                'ne_3' => $request->input('ne_3'),
                'ne_4' => $request->input('ne_4'),
                'ne_5' => $request->input('ne_5'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al guardar test de personalidad: ' . $e->getMessage());
            return null;
        }
    }

    private function getRecommendation(array $data): ?string
    {
        try {
            $client = new Client();
            $response = $client->post('http://127.0.0.1:5000/predict', [
                'json' => $data,
                'timeout' => 10,
            ]);

            $body = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('Error al decodificar JSON: ' . json_last_error_msg());
                return 'Error al decodificar la respuesta JSON: ' . json_last_error_msg();
            }

            // Adaptado para la nueva estructura de respuesta del API
            if (!isset($body['rasgo_dominante']) || !isset($body['perfil_completo'])) {
                \Log::error('Respuesta inesperada del servidor: ' . json_encode($body));
                return 'Respuesta inesperada del servidor.';
            }

            $rasgo_dominante = $body['rasgo_dominante'];
            $perfil_completo = $body['perfil_completo'];

            // Obtener las recomendaciones del rasgo dominante
            $trait_key = $this->getTraitKey($rasgo_dominante);
            
            if (isset($perfil_completo[$rasgo_dominante]['recomendaciones'])) {
                $recomendaciones = $perfil_completo[$rasgo_dominante]['recomendaciones'];
                $libro = $recomendaciones['libro'] ?? 'Sin recomendación de libro';
                $pelicula = $recomendaciones['pelicula'] ?? 'Sin recomendación de película';
                $cancion = $recomendaciones['cancion'] ?? 'Sin recomendación de canción';
                
                $puntuacion = $perfil_completo[$rasgo_dominante]['score'] ?? 'N/A';
                
                return "Tu rasgo dominante es: $rasgo_dominante, Lumina te recomienda los siguientes materiales de apoyo.<br><br>
                        📚 <strong>Libro recomendado:</strong> $libro<br>
                        🎬 <strong>Película recomendada:</strong> $pelicula<br>
                        🎵 <strong>Canción recomendada:</strong> $cancion";
            } else {
                return "Tu rasgo dominante es: $rasgo_dominante. No se pudieron obtener recomendaciones específicas.";
            }

        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            \Log::error('Error de conexión con API de Flask: ' . $e->getMessage());
            return 'Error: No se pudo conectar con el servidor de predicciones. Verifica que esté ejecutándose.';
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            \Log::error('Error en petición HTTP: ' . $e->getMessage());
            return 'Error en la comunicación con el servidor: ' . $e->getMessage();
        } catch (\Exception $e) {
            \Log::error('Error general al obtener recomendación: ' . $e->getMessage());
            return 'Error al conectar con el API: ' . $e->getMessage();
        }
    }

    private function getTraitKey($trait): string
    {
        $mapping = [
            'Apertura' => 'apertura',
            'Responsabilidad' => 'responsabilidad',
            'Extroversión' => 'extroversión',
            'Amabilidad' => 'amabilidad',
            'Neuroticismo' => 'neuroticismo'
        ];
        
        return $mapping[$trait] ?? strtolower($trait);
    }
}