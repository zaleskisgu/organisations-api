<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @OA\Get(
 *     path="/buildings",
 *     summary="Получить все здания",
 *     tags={"Buildings"},
 *     security={{"api_key":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Список всех зданий",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Building")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 */
class BuildingController extends Controller
{
    /**
     * Получить все здания с организациями
     */
    public function index(): JsonResponse
    {
        $buildings = Building::with(['organizations.phones', 'organizations.activities'])->get();
        
        return ApiResponse::collection(BuildingResource::collection($buildings), 'Здания успешно получены');
    }
} 