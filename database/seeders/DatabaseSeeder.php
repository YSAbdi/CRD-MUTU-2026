<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email'=>'superadmin@mutu.co.id'], ['name'=>'Superadmin MUTU','password'=>Hash::make(env('SUPERADMIN_PASSWORD','ChangeMe_123456!')),'role'=>'superadmin']);
    }
}
