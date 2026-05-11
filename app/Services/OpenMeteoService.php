<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OpenMeteoService
{
    public function obtenerClimaActual(): array
    {
        try {
            return Cache::remember('open_meteo_clima_actual', now()->addMinutes(20), function () {
                $latitude = config('services.open_meteo.latitude');
                $longitude = config('services.open_meteo.longitude');
                $timezone = config('services.open_meteo.timezone');
                $city = config('services.open_meteo.city');

                $response = Http::acceptJson()
                    ->timeout(15)
                    ->get('https://api.open-meteo.com/v1/forecast', [
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'current' => implode(',', [
                            'temperature_2m',
                            'relative_humidity_2m',
                            'apparent_temperature',
                            'precipitation',
                            'weather_code',
                            'wind_speed_10m',
                        ]),
                        'timezone' => $timezone,
                        'temperature_unit' => 'celsius',
                        'wind_speed_unit' => 'kmh',
                        'precipitation_unit' => 'mm',
                    ]);

                if (!$response->successful()) {
                    return $this->respuestaError('No se pudo consultar Open-Meteo. Código: ' . $response->status());
                }

                $data = $response->json();
                $current = $data['current'] ?? [];

                $weatherCode = (int) ($current['weather_code'] ?? -1);
                $temperatura = $current['temperature_2m'] ?? null;
                $sensacion = $current['apparent_temperature'] ?? null;
                $humedad = $current['relative_humidity_2m'] ?? null;
                $precipitacion = $current['precipitation'] ?? null;
                $viento = $current['wind_speed_10m'] ?? null;

                return [
                    'ok' => true,
                    'ciudad' => $city,
                    'temperatura' => $temperatura,
                    'sensacion' => $sensacion,
                    'humedad' => $humedad,
                    'precipitacion' => $precipitacion,
                    'viento' => $viento,
                    'codigo' => $weatherCode,
                    'condicion' => $this->traducirCodigoClima($weatherCode),
                    'icono' => $this->iconoClima($weatherCode),
                    'recomendacion' => $this->recomendacionGym($temperatura, $precipitacion, $weatherCode),
                    'hora' => $current['time'] ?? now()->format('Y-m-d H:i'),
                    'fuente' => 'Open-Meteo API',
                ];
            });
        } catch (\Exception $e) {
            return $this->respuestaError('No se pudo conectar con Open-Meteo: ' . $e->getMessage());
        }
    }

    private function respuestaError(string $mensaje): array
    {
        return [
            'ok' => false,
            'mensaje' => $mensaje,
            'ciudad' => config('services.open_meteo.city', 'Oaxaca de Juárez'),
            'fuente' => 'Open-Meteo API',
        ];
    }

    private function traducirCodigoClima(int $codigo): string
    {
        return match ($codigo) {
            0 => 'Cielo despejado',
            1 => 'Mayormente despejado',
            2 => 'Parcialmente nublado',
            3 => 'Nublado',
            45, 48 => 'Niebla',
            51, 53, 55 => 'Llovizna',
            56, 57 => 'Llovizna helada',
            61, 63, 65 => 'Lluvia',
            66, 67 => 'Lluvia helada',
            71, 73, 75 => 'Nevada',
            77 => 'Granizo ligero',
            80, 81, 82 => 'Chubascos',
            85, 86 => 'Chubascos de nieve',
            95 => 'Tormenta',
            96, 99 => 'Tormenta con granizo',
            default => 'Condición no disponible',
        };
    }

    private function iconoClima(int $codigo): string
    {
        return match (true) {
            $codigo === 0 => 'bi-sun-fill',
            in_array($codigo, [1, 2], true) => 'bi-cloud-sun-fill',
            $codigo === 3 => 'bi-cloud-fill',
            in_array($codigo, [45, 48], true) => 'bi-cloud-fog2-fill',
            in_array($codigo, [51, 53, 55, 56, 57], true) => 'bi-cloud-drizzle-fill',
            in_array($codigo, [61, 63, 65, 66, 67, 80, 81, 82], true) => 'bi-cloud-rain-heavy-fill',
            in_array($codigo, [95, 96, 99], true) => 'bi-cloud-lightning-rain-fill',
            default => 'bi-cloud-fill',
        };
    }

    private function recomendacionGym($temperatura, $precipitacion, int $codigo): string
    {
        if ($temperatura === null) {
            return 'Consulta el clima antes de planear actividades exteriores.';
        }

        if ($codigo >= 95 || (float) $precipitacion > 5) {
            return 'Se recomienda priorizar entrenamiento dentro del gimnasio.';
        }

        if ($temperatura >= 32) {
            return 'Día caluroso: recomienda hidratación constante a los clientes.';
        }

        if ($temperatura <= 12) {
            return 'Día fresco: ideal iniciar con calentamiento prolongado.';
        }

        return 'Condiciones adecuadas para entrenamiento y asistencia normal.';
    }
}