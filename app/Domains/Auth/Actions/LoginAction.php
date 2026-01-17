<?php 

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTO\AuthTokenDTO;
use App\Domains\Auth\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    public function execute(string $email, string $password): AuthTokenDTO
    {
        if (! $token = Auth::guard('api')->attempt([
            'email' => $email,
            'password' => $password,
        ])) {
            throw new InvalidCredentialsException('invalid.credentials');
        }

        return new AuthTokenDTO(
            token: $token,
            expiresIn: Auth::guard('api')->factory()->getTTL() * 60
        );
    }
}