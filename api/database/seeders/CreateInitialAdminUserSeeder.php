<?php

namespace Database\Seeders;

use App\Auth\Repositories\UserRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateInitialAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRepository = new UserRepository();

        $user = $userRepository->findByEmail('admin@email.com');
        if (empty($user)) {
            $user = $userRepository->create([
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => Hash::make('cmt$passWord'),
                'is_admin' => true
            ]);

            if (empty($user)) {
                return;
            }

            $user->markEmailAsVerified();
        }
    }
}
