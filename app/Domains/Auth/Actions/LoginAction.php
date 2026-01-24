<?php 

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTO\AuthTokensDTO;
use App\Domains\Auth\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginAction
{
    public function execute(string $email, string $password): AuthTokensDTO
    {
        if (! $token = Auth::guard('api')->attempt([
            'email' => $email,
            'password' => $password,
        ])) {
            throw new InvalidCredentialsException('invalid.credentials');
        }

        $user = Auth::guard('api')->user();
        $refreshToken = $this->createRefreshToken($user);

        return new AuthTokensDTO(
            accessToken: $token,
            refreshToken: $refreshToken,
            expiresIn: Auth::guard('api')->factory()->getTTL() * 60
        );
    }

    private function createRefreshToken($user): string
    {
        $factory = JWTAuth::factory();
        $originalTtl = $factory->getTTL();
        $refreshTtl = config('jwt.refresh_ttl');

        if ($refreshTtl !== null) {
            $factory->setTTL((int) $refreshTtl);
        }

        $token = JWTAuth::claims(['token_type' => 'refresh'])->fromUser($user);

        if ($refreshTtl !== null) {
            $factory->setTTL($originalTtl);
        }

        return $token;
    }
}
