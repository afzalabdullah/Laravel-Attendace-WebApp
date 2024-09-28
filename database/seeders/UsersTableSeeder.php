<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $employees = DB::table('employees')->get();

        foreach ($employees as $employee) {
            DB::table('users')->insert([
                'name' => $employee->Employee_Name,
                'email' => $employee->Emp_Code . '@trakker.com',
                'password' => Hash::make('Karachi1'),
                'remember_token' => null,
                'department' => $employee->Department,
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'employee',
            ]);
        }
    }
}
