<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $programs = [
            'Mother Baby Friendly Health Facility Initiative (MBFHFI)',
            'Community-Based Drug Rehabilitation Program',
            'Adolescent Friendly Health Facility',
            'Animal Bite Treatment Center',
        ];

        foreach ($programs as $program) {
            Program::firstOrCreate([
                'name' => $program,
            ]);
        }
    }
}
