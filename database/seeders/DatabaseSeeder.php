<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        DB::table('roles')->upsert([
            ['name' => 'Administrador', 'slug' => 'admin', 'description' => 'Acceso total al sistema', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Recepcionista', 'slug' => 'receptionist', 'description' => 'Registro y consulta limitada', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ], ['slug'], ['name', 'description', 'is_active', 'updated_at']);

        DB::table('document_types')->upsert([
            ['name' => 'Cédula de ciudadanía', 'code' => 'CC', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tarjeta de identidad', 'code' => 'TI', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cédula de extranjería', 'code' => 'CE', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pasaporte', 'code' => 'PA', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ], ['code'], ['name', 'is_active', 'updated_at']);

        DB::table('payment_methods')->upsert([
            ['name' => 'Efectivo', 'description' => 'Pago en efectivo', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transferencia', 'description' => 'Transferencia bancaria', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tarjeta débito', 'description' => 'Pago con tarjeta débito', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tarjeta crédito', 'description' => 'Pago con tarjeta crédito', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nequi', 'description' => 'Pago por Nequi', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Daviplata', 'description' => 'Pago por Daviplata', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ], ['name'], ['description', 'is_active', 'updated_at']);

        DB::table('membership_plans')->upsert([
            ['name' => 'Diario', 'duration_days' => 1, 'price' => 10000, 'description' => 'Acceso por un día', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Semanal', 'duration_days' => 7, 'price' => 30000, 'description' => 'Acceso por una semana', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Quincenal', 'duration_days' => 15, 'price' => 50000, 'description' => 'Acceso por quince días', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mensual', 'duration_days' => 30, 'price' => 80000, 'description' => 'Acceso por un mes', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Trimestral', 'duration_days' => 90, 'price' => 210000, 'description' => 'Acceso por tres meses', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Semestral', 'duration_days' => 180, 'price' => 390000, 'description' => 'Acceso por seis meses', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Anual', 'duration_days' => 365, 'price' => 720000, 'description' => 'Acceso por un año', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ], ['name'], ['duration_days', 'price', 'description', 'is_active', 'updated_at']);

        DB::table('gym_settings')->upsert([
            ['id' => 1, 'name' => 'Gimnasio', 'logo_path' => null, 'address' => null, 'phone' => null, 'email' => null, 'whatsapp' => null, 'facebook_url' => null, 'instagram_url' => null, 'website_url' => null, 'currency_code' => 'COP', 'currency_symbol' => '$', 'tax_rate' => 0, 'opening_hours' => null, 'timezone' => 'America/Bogota', 'created_at' => now(), 'updated_at' => now()],
        ], ['id'], ['name', 'updated_at']);

        $adminRoleId = Role::query()->where('slug', 'admin')->value('id');

        if ($adminRoleId && ! User::query()->where('email', 'admin@gym.com')->exists()) {
            User::query()->create([
                'role_id' => $adminRoleId,
                'name' => 'Administrador',
                'email' => 'admin@gym.com',
                'phone' => null,
                'is_active' => true,
                'password' => Hash::make('Admin12345'),
            ]);
        }
    }
}
