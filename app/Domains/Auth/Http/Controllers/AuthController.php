<?php

namespace App\Domains\Auth\Http\Controllers;

use App\Domains\Auth\Actions\LoginAction;
use App\Domains\Auth\Actions\RegisterAction;
use App\Domains\Auth\DTO\NewUserDTO;
use App\Domains\Auth\Exceptions\InvalidCredentialsException;
use App\Domains\Auth\Http\Requests\LoginRequest;
use App\Domains\Auth\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Throwable;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Commons\Messages\HttpMessages;

class AuthController
{
    use ApiResponseTrait;

    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        try{
            $dto = $action->execute(
                $request->email,
                $request->password
            );

            return $this->successResponse($dto->toArray());
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

    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logout realizado']);
    }
}