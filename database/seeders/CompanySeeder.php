<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::insert([
            ['name' => 'pharma', 'description' => 'this is the first company'],
            ['name' => 'unipharma', 'description' => 'this is the second company'],
            ['name' => 'ibnrshd', 'description' => 'this is the third company'],
        ]);
        
    }
}
