<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EjercicioController extends Controller
{
    public function index(Request $request)
    {
        $tipos = [
            '8' => 'Brazos',
            '9' => 'Piernas',
            '10' => 'Abdomen',
            '11' => 'Pecho',
            '12' => 'Espalda',
            '13' => 'Hombros',
            '14' => 'Pantorrillas',
            '15' => 'Cardio',
        ];

        $musculos = [
            '1' => 'Bíceps',
            '2' => 'Deltoide anterior',
            '3' => 'Serrato anterior',
            '4' => 'Pectoral mayor',
            '5' => 'Oblicuos',
            '6' => 'Gastrocnemio',
            '7' => 'Abdominales',
            '8' => 'Glúteos',
            '9' => 'Trapecio',
            '10' => 'Cuádriceps',
            '11' => 'Isquiotibiales',
            '12' => 'Dorsales',
            '13' => 'Braquial',
            '14' => 'Tríceps',
            '15' => 'Sóleo',
        ];

        $dificultades = [
            'beginner' => 'Principiante',
            'intermediate' => 'Intermedio',
            'expert' => 'Avanzado',
        ];

        $ejercicios = [];
        $error = null;
        $consultado = false;

        $hayBusqueda = $request->filled('name')
            || $request->filled('type')
            || $request->filled('muscle')
            || $request->filled('difficulty');

        if ($hayBusqueda) {
            $consultado = true;

            try {
                $languageId = $this->obtenerIdIdiomaEspanol();

                $parametros = array_filter([
                    'format' => 'json',
                    'limit' => 30,
                    'language' => $languageId,
                    'status' => 2,
                    'category' => $request->input('type'),
                    'muscles' => $request->input('muscle'),
                ], fn ($value) => $value !== null && $value !== '');

                $response = Http::acceptJson()
                    ->timeout(20)
                    ->get('https://wger.de/api/v2/exerciseinfo/', $parametros);

                if (!$response->successful()) {
                    $error = 'Error al consultar Wger API. Código: ' . $response->status();
                } else {
                    $data = $response->json();
                    $resultados = $data['results'] ?? [];

                    foreach ($resultados as $item) {
                        $ejercicio = $this->normalizarEjercicioWger(
                            $item,
                            $request,
                            $tipos,
                            $musculos,
                            $dificultades,
                            $languageId
                        );

                        if ($ejercicio) {
                            $ejercicios[] = $ejercicio;
                        }
                    }

                    if (empty($ejercicios) && $request->filled('name')) {
                        $responseAlterno = Http::acceptJson()
                            ->timeout(20)
                            ->get('https://wger.de/api/v2/exerciseinfo/', [
                                'format' => 'json',
                                'limit' => 50,
                                'language' => $languageId,
                                'status' => 2,
                            ]);

                        if ($responseAlterno->successful()) {
                            $dataAlterna = $responseAlterno->json();
                            $resultadosAlternos = $dataAlterna['results'] ?? [];

                            foreach ($resultadosAlternos as $item) {
                                $ejercicio = $this->normalizarEjercicioWger(
                                    $item,
                                    $request,
                                    $tipos,
                                    $musculos,
                                    $dificultades,
                                    $languageId
                                );

                                if ($ejercicio) {
                                    $ejercicios[] = $ejercicio;
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                $error = 'No se pudo conectar con Wger API: ' . $e->getMessage();
            }
        }

        return view('ejercicios.index', compact(
            'tipos',
            'musculos',
            'dificultades',
            'ejercicios',
            'error',
            'consultado'
        ));
    }

    private function obtenerIdIdiomaEspanol(): int
    {
        return Cache::remember('wger_idioma_espanol', now()->addDay(), function () {
            try {
                $response = Http::acceptJson()
                    ->timeout(15)
                    ->get('https://wger.de/api/v2/language/', [
                        'format' => 'json',
                        'limit' => 100,
                    ]);

                if (!$response->successful()) {
                    return 4;
                }

                $idiomas = $response->json('results') ?? [];

                $idiomaEspanol = collect($idiomas)->first(function ($idioma) {
                    $shortName = Str::lower($idioma['short_name'] ?? '');
                    $fullName = Str::lower($idioma['full_name'] ?? '');

                    return $shortName === 'es'
                        || Str::contains($shortName, 'es-')
                        || Str::contains($fullName, 'spanish')
                        || Str::contains($fullName, 'español');
                });

                return (int) ($idiomaEspanol['id'] ?? 4);
            } catch (\Exception $e) {
                return 4;
            }
        });
    }

    private function normalizarEjercicioWger(
        array $item,
        Request $request,
        array $tipos,
        array $musculos,
        array $dificultades,
        int $languageId
    ): ?array {
        $traducciones = $item['translations'] ?? [];

        $traduccion = collect($traducciones)->first(function ($translation) use ($languageId) {
            return (int) ($translation['language'] ?? 0) === $languageId;
        });

        $traduccion = $traduccion ?: collect($traducciones)->first();

        $nombre = $traduccion['name'] ?? $item['name'] ?? null;

        if (!$nombre) {
            return null;
        }

        if ($request->filled('name')) {
            $busqueda = Str::lower($request->input('name'));
            $nombreMinuscula = Str::lower($nombre);

            if (!Str::contains($nombreMinuscula, $busqueda)) {
                return null;
            }
        }

        $descripcion = $traduccion['description'] ?? $item['description'] ?? 'Sin instrucciones disponibles.';
        $descripcion = $this->limpiarTexto($descripcion);

        $categoriaId = null;

        if (isset($item['category'])) {
            if (is_array($item['category'])) {
                $categoriaId = $item['category']['id'] ?? null;
            } else {
                $categoriaId = $item['category'];
            }
        }

        $categoria = $categoriaId && isset($tipos[(string) $categoriaId])
            ? $tipos[(string) $categoriaId]
            : 'Ejercicio general';

        $musculosTexto = collect($item['muscles'] ?? [])
            ->map(function ($muscle) use ($musculos) {
                if (is_array($muscle)) {
                    $id = $muscle['id'] ?? null;

                    if ($id && isset($musculos[(string) $id])) {
                        return $musculos[(string) $id];
                    }

                    return $muscle['name'] ?? $muscle['name_en'] ?? null;
                }

                return $musculos[(string) $muscle] ?? null;
            })
            ->filter()
            ->implode(', ');

        if (!$musculosTexto && $request->filled('muscle')) {
            $musculosTexto = $musculos[$request->input('muscle')] ?? 'Músculo seleccionado';
        }

        $dificultad = $request->filled('difficulty')
            ? ($dificultades[$request->input('difficulty')] ?? 'General')
            : 'General';

        return [
            'name' => $nombre,
            'type' => $categoria,
            'muscle' => $musculosTexto ?: 'General',
            'difficulty' => $dificultad,
            'instructions' => $descripcion ?: 'Sin instrucciones disponibles.',
        ];
    }

    private function limpiarTexto(?string $texto): string
    {
        $texto = $texto ?? '';

        $texto = html_entity_decode($texto);
        $texto = strip_tags($texto);
        $texto = preg_replace('/\s+/', ' ', $texto);

        return trim($texto);
    }
}