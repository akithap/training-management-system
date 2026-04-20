<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TrainingProgram;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Amex Admin',
            'email' => 'admin@amex.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $trainer = User::create([
            'name' => 'Sarah Connor (Trainer)',
            'email' => 'trainer@amex.com',
            'password' => Hash::make('password'),
            'role' => 'trainer',
        ]);

        $trainee1 = User::create([
            'name' => 'John Doe (Trainee)',
            'email' => 'john@amex.com',
            'password' => Hash::make('password'),
            'role' => 'trainee',
        ]);

        $trainee2 = User::create([
            'name' => 'Jane Smith (Trainee)',
            'email' => 'jane@amex.com',
            'password' => Hash::make('password'),
            'role' => 'trainee',
        ]);

        $program = TrainingProgram::create([
            'title' => 'Amex Leadership & Resilience 2026',
            'training_area' => 'Management & Soft Skills',
            'venue' => 'Main Hall A',
            'schedule_datetime' => now()->addDays(5),
            'trainer_id' => $trainer->id,
            'file_path' => null
        ]);

        $program->trainees()->attach([$trainee1->id, $trainee2->id]);
    }
}
