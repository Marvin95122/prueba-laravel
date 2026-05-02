<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Membresia::updateOrCreate(
            ['nombre' => 'Básica'],
            [
                'descripcion' => 'Acceso a pesas y área de cardio.',
                'precio' => 399,
                'duracion_dias' => 30,
                'beneficios' => 'Pesas, cardio y acceso en horario general.',
                'estado' => 'activa',
            ]
        );

        \App\Models\Membresia::updateOrCreate(
            ['nombre' => 'Plus'],
            [
                'descripcion' => 'Acceso a pesas, cardio y clases grupales.',
                'precio' => 599,
                'duracion_dias' => 30,
                'beneficios' => 'Pesas, cardio, clases grupales y rutinas semanales.',
                'estado' => 'activa',
            ]
        );

        \App\Models\Membresia::updateOrCreate(
            ['nombre' => 'Premium'],
            [
                'descripcion' => 'Acceso completo con asesoría personalizada.',
                'precio' => 899,
                'duracion_dias' => 30,
                'beneficios' => 'Todo incluido, asesoría semanal y seguimiento personalizado.',
                'estado' => 'activa',
            ]
        );
        }
}
