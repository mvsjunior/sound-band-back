<?php

namespace App\Domains\Auth\Http\Controllers;

use App\Domains\Auth\Actions\LoginAction;
use App\Domains\Auth\Actions\RefreshTokenAction;
use App\Domains\Auth\Actions\RegisterAction;
use App\Domains\Auth\DTO\NewUserDTO;
use App\Domains\Auth\Exceptions\InvalidCredentialsException;
use App\Domains\Auth\Http\Requests\LoginRequest;
use App\Domains\Auth\Http\Requests\RegisterRequest;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Commons\Messages\HttpMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController
{
    use ApiResponseTrait;

    private const REFRESH_TOKEN_COOKIE = 'refresh_token';

    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        try{
            $tokens = $action->execute(
                $request->email,
                $request->password
            );

            return $this
                ->successResponse($tokens->accessTokenDTO()->toArray())
                ->cookie($this->refreshTokenCookie($tokens->refreshToken));
        }catch(InvalidCredentialsException $e){
            return $this->unauthorizedResponse(HttpMessages::INVALID_CREDENTIALS);
        }catch(Throwable $th){
            return $this->serverErrorResponse(HttpMessages::INTERNAL_ERROR . $th->getMessage());
        }
    }

    public function register(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        $userDTO = new NewUserDTO($request->name, $request->email, $request->password);
        $userData = $action->execute($userDTO);

        return response()->json($userData, 201);
    }

    public function refresh(Request $request, RefreshTokenAction $action): JsonResponse
    {
        $refreshToken = $request->cookie(self::REFRESH_TOKEN_COOKIE);

        if (! $refreshToken) {
            return $this->unauthorizedResponse(HttpMessages::TOKEN_NOT_PROVIDED);
        }

        try {
            $tokens = $action->execute($refreshToken);

            return $this
                ->successResponse($tokens->accessTokenDTO()->toArray())
                ->cookie($this->refreshTokenCookie($tokens->refreshToken));
        } catch (TokenExpiredException $exception) {
            return $this->unauthorizedResponse(HttpMessages::TOKEN_EXPIRED);
        } catch (TokenInvalidException $exception) {
            return $this->unauthorizedResponse(HttpMessages::TOKEN_INVALID);
        } catch (JWTException $exception) {
            return $this->unauthorizedResponse(HttpMessages::UNAUTHORIZED);
        } catch (Throwable $th) {
            return $this->serverErrorResponse(HttpMessages::INTERNAL_ERROR . $th->getMessage());
        }
    }

    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }

    public function logout(Request $request): JsonResponse
    {
        auth('api')->logout();

        $refreshToken = $request->cookie(self::REFRESH_TOKEN_COOKIE);
        if ($refreshToken) {
            JWTAuth::setToken($refreshToken)->invalidate();
        }

        return $this
            ->successResponse(['message' => 'Logout realizado'])
            ->cookie($this->forgetRefreshTokenCookie());
    }

    private function refreshTokenCookie(string $token): Cookie
    {
        $minutes = (int) (config('jwt.refresh_ttl') ?? 0);

        return cookie(
            self::REFRESH_TOKEN_COOKIE,
            $token,
            $minutes,
            config('session.path', '/'),
            config('session.domain'),
            (bool) config('session.secure', false),
            true,
            false,
            config('session.same_site', 'lax')
        );
    }

    private function forgetRefreshTokenCookie(): Cookie
    {
        return cookie()->forget(
            self::REFRESH_TOKEN_COOKIE,
            config('session.path', '/'),
            config('session.domain')
        );
    }
}
