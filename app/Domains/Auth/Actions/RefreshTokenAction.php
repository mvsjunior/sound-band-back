<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\DTO\AuthTokensDTO;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTokenAction
{
    public function execute(string $refreshToken): AuthTokensDTO
    {
        $payload = JWTAuth::setToken($refreshToken)->getPayload();

        if ($payload->get('token_type') !== 'refresh') {
            throw new TokenInvalidException('token.invalid');
        }

        $user = JWTAuth::setToken($refreshToken)->authenticate();

        if (! $user) {
            throw new TokenInvalidException('token.invalid');
        }

        $accessToken = Auth::guard('api')->login($user);
        $refreshToken = $this->createRefreshToken($user);

        return new AuthTokensDTO(
            accessToken: $accessToken,
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
