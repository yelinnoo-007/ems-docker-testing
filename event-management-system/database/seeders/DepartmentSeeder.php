<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
                'IT' ,'Finance', 'HR', 'Admin', 'Staff'    
        ];
    
        foreach($departments as $department)
        {
            Department::create([
                'dept_name' => $department,
            ]);
        }
    }
}
