<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $roles = ['admin', 'pimpinan'];
        $role = $this->faker->randomElement($roles);

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => $role,
            'jabatan' => $role === 'admin' 
                ? $this->faker->randomElement(['Administrator Sistem', 'Admin Operasional', 'Admin Data'])
                : $this->faker->randomElement(['Kepala Dinas', 'Sekretaris Dinas', 'Kepala Bidang']),
            'no_telepon' => $this->faker->phoneNumber(),
            'foto' => null,
            'is_active' => true,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}