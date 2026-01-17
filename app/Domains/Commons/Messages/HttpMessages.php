<?php

declare(strict_types=1);

namespace App\Domains\Commons\Messages;

final class HttpMessages
{
    private function __construct()
    {
        // Impede instanciação
    }

    /* =========================
     * AUTH
     * ========================= */
    public const INVALID_CREDENTIALS = 'Credenciais inválidas';
    public const UNAUTHORIZED        = 'Não autorizado';
    public const FORBIDDEN           = 'Acesso negado';
    public const TOKEN_EXPIRED       = 'Token expirado';
    public const TOKEN_INVALID       = 'Token inválido';
    public const TOKEN_NOT_PROVIDED  = 'Token não informado';

    /* =========================
     * REQUEST / VALIDATION
     * ========================= */
    public const VALIDATION_ERROR    = 'Erro de validação';
    public const BAD_REQUEST         = 'Requisição inválida';
    public const UNPROCESSABLE       = 'Não foi possível processar a requisição';

    /* =========================
     * RESOURCE
     * ========================= */
    public const RESOURCE_NOT_FOUND  = 'Recurso não encontrado';
    public const RESOURCE_CREATED    = 'Recurso criado com sucesso';
    public const RESOURCE_UPDATED    = 'Recurso atualizado com sucesso';
    public const RESOURCE_DELETED    = 'Recurso removido com sucesso';

    /* =========================
     * SERVER
     * ========================= */
    public const INTERNAL_ERROR      = 'Erro interno do servidor';
    public const SERVICE_UNAVAILABLE = 'Serviço temporariamente indisponível';
}
