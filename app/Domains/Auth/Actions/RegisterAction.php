<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTO\NewUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterAction
{
    public function execute(NewUserDTO $newUserDTO): User
    {
        return User::create([
            'name'     => $newUserDTO->name,
            'email'    => $newUserDTO->email,
            'password' => Hash::make($newUserDTO->password)
        ]);
    }
}