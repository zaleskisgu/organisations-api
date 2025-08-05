<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     title="Organizations API",
 *     version="1.0.0",
 *     description="API для работы с организациями, зданиями и видами деятельности",
 *     @OA\Contact(
 *         email="admin@example.com",
 *         name="API Support"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Local API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="api_key",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-Key"
 * )
 */
class OpenApiSpec
{
    // Этот класс используется только для аннотаций OpenAPI
} 