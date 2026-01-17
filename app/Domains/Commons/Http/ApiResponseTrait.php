<?php

declare(strict_types=1);

namespace App\Domains\Commons\Http;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    protected function successResponse(
        mixed $data = null,
        string $message = 'Operação realizada com sucesso',
        int $status = Response::HTTP_OK
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    protected function errorResponse(
        string $message = 'Erro ao processar requisição',
        mixed $errors = null,
        int $status = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    protected function validationErrorResponse(
        mixed $errors,
        string $message = 'Erro de validação',
        int $status = Response::HTTP_UNPROCESSABLE_ENTITY
    ): JsonResponse {
        return $this->errorResponse(
            message: $message,
            errors: $errors,
            status: $status
        );
    }

    protected function unauthorizedResponse(
        string $message = 'Não autorizado'
    ): JsonResponse {
        return $this->errorResponse(
            message: $message,
            status: Response::HTTP_UNAUTHORIZED
        );
    }

    protected function forbiddenResponse(
        string $message = 'Acesso negado'
    ): JsonResponse {
        return $this->errorResponse(
            message: $message,
            status: Response::HTTP_FORBIDDEN
        );
    }

    protected function notFoundResponse(
        string $message = 'Recurso não encontrado'
    ): JsonResponse {
        return $this->errorResponse(
            message: $message,
            status: Response::HTTP_NOT_FOUND
        );
    }

    protected function serverErrorResponse(
        string $message = 'Erro interno do servidor'
    ): JsonResponse {
        return $this->errorResponse(
            message: $message,
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
