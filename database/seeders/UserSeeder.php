<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            
            $user = User::create([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]);

            
            Profile::create([
                'user_id' => $user->id,
                'id_proof' => 'proofs/id_proof_' . $i . '.jpg',
                'address_proof' => 'proofs/address_proof_' . $i . '.jpg',
                'status' => ['Not Submitted', 'Waiting for Approval', 'Approved', 'Rejected'][rand(0, 3)],
            ]);
        }
    }
}
