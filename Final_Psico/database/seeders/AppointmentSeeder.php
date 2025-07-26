<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run()
    {
        Appointment::create([
            'user_id' => 3, 
            'appointment_date' => now()->addDays(2), 
            'description' => 'Primera cita de prueba',
        ]);
    }
}