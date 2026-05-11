<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->crearUsuario('Administrador', 'admin@gymcontrol.com', 'admin');
        $this->crearUsuario('Gerente', 'gerente@gymcontrol.com', 'gerente');
        $this->crearUsuario('Recepción', 'recepcion@gymcontrol.com', 'recepcion');

        DB::table('membresias')->updateOrInsert(
            ['nombre' => 'Básica'],
            [
                'descripcion' => 'Acceso a pesas y área de cardio.',
                'precio' => 399,
                'duracion_dias' => 30,
                'estado' => 'activa',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('membresias')->updateOrInsert(
            ['nombre' => 'Plus'],
            [
                'descripcion' => 'Acceso a pesas, cardio y clases grupales.',
                'precio' => 599,
                'duracion_dias' => 30,
                'estado' => 'activa',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('membresias')->updateOrInsert(
            ['nombre' => 'Premium'],
            [
                'descripcion' => 'Acceso completo con asesoría personalizada.',
                'precio' => 899,
                'duracion_dias' => 30,
                'estado' => 'activa',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function crearUsuario(string $nombre, string $email, string $rol): void
    {
        $usuario = User::where('email', $email)->first();

        if (!$usuario) {
            $usuario = new User();
            $usuario->email = $email;
        }

        $usuario->name = $nombre;
        $usuario->password = Hash::make('prueba123');
        $usuario->role = $rol;
        $usuario->email_verified_at = now();
        $usuario->save();
    }
}