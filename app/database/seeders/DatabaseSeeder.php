<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Schedule;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin + customer demo
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);
        $customer = User::factory()->create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@example.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);

        // Services
        $corte = Service::create(['name' => 'Corte', 'duration_minutes' => 30, 'price' => 4000, 'active' => true]);
        $color = Service::create(['name' => 'Color', 'duration_minutes' => 60, 'price' => 8000, 'active' => true]);

        // Staff + users
        $juanUser = User::factory()->create(['name' => 'Juan', 'email' => 'juan@example.com', 'role' => 'staff', 'password' => bcrypt('password')]);
        $meliUser = User::factory()->create(['name' => 'Meli', 'email' => 'meli@example.com', 'role' => 'staff', 'password' => bcrypt('password')]);

        $juan = Staff::create(['user_id' => $juanUser->id, 'display_name' => 'Juan', 'bio' => 'Barbero', 'active' => true]);
        $juan->services()->sync([$corte->id, $color->id]);
        $meli = Staff::create(['user_id' => $meliUser->id, 'display_name' => 'Meli', 'bio' => 'Colorista', 'active' => true]);
        $meli->services()->sync([$color->id]);

        // Schedules (Mon-Fri 10-18)
        foreach ([1,2,3,4,5] as $d) {
            Schedule::create(['staff_id' => $juan->id, 'weekday' => $d, 'start_time' => '10:00:00', 'end_time' => '18:00:00']);
            Schedule::create(['staff_id' => $meli->id, 'weekday' => $d, 'start_time' => '12:00:00', 'end_time' => '20:00:00']);
        }

        // Products (4)
        Product::factory()->create(['name' => 'Cheesecake frutos rojos', 'slug' => 'cheesecake', 'price' => 6000, 'is_made_to_order' => true, 'lead_time_hours' => 48]);
        Product::factory()->create(['name' => 'Lemon pie', 'slug' => 'lemon-pie', 'price' => 5200, 'stock' => 5]);
        Product::factory()->create(['name' => 'Brownie con nuez', 'slug' => 'brownie', 'price' => 3500, 'stock' => 10]);
        Product::factory()->create(['name' => 'Tiramisú', 'slug' => 'tiramisu', 'price' => 5800, 'is_made_to_order' => true, 'lead_time_hours' => 72]);

        // Default settings
        Setting::put('appointments.deposit_percent', 30);
        Setting::put('appointments.cancel_window_hours', 48);
        Setting::put('appointments.buffer_minutes', 0);
        Setting::put('orders.deposit_percent', 50);
        Setting::put('orders.cancel_window_hours', 48);
        Setting::put('business.phone', '+5491112345678');
        Setting::put('legal.text', 'La señal no es reembolsable dentro de 48h.');
    }
}
