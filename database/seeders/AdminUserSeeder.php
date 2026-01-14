<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         User::updateOrCreate(
            ['email' => 'aldwincarl.acl@gmail.com'],   // unique identifier
            [
                'name' => 'Aldwin Carl Llenado',
                'password' => Hash::make('admin123'), // change this!
            ]
        );
    }
}
