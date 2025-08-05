<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchAreaRequest;
use App\Http\Requests\SearchNameRequest;
use App\Http\Requests\SearchRadiusRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\OrganizationResource;
use App\Services\OrganizationService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Get(
 *     path="/organizations/building/{buildingId}",
 *     summary="Получить организации в здании",
 *     description="Получить список всех организаций в конкретном здании",
 *     tags={"Organizations"},
 *     security={{"api_key":{}}},
 *     @OA\Parameter(
 *         name="buildingId",
 *         in="path",
 *         description="ID здания",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Успешный ответ",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Organization")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Неверный API ключ"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Здание не найдено"
 *     )
 * )
 */
class OrganizationController extends Controller
{
    private OrganizationService $organizationService;

    public function __construct(OrganizationService $organizationService)
    {
        $this->organizationService = $organizationService;
    }

    /**
     * Получить организации в здании
     */
    public function getByBuilding(int $buildingId): JsonResponse
    {
        $organizations = $this->organizationService->getByBuilding($buildingId);

        return ApiResponse::collection(OrganizationResource::collection($organizations), 'Организации в здании получены');
    }

    /**
     * @OA\Get(
     *     path="/organizations/activity/{activityId}",
     *     summary="Получить организации по виду деятельности",
     *     description="Получить список организаций по виду деятельности",
     *     tags={"Organizations"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         description="ID вида деятельности",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Organization")
     *             )
     *         )
     *     )
     * )
     */
    public function getByActivity(int $activityId): JsonResponse
    {
        $organizations = $this->organizationService->getByActivity($activityId);

        return ApiResponse::collection(OrganizationResource::collection($organizations), 'Организации по виду деятельности получены');
    }

    /**
     * @OA\Get(
     *     path="/organizations/search/radius",
     *     summary="Поиск организаций в радиусе",
     *     description="Поиск организаций в радиусе",
     *     tags={"Organizations"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         description="Широта центральной точки",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         description="Долгота центральной точки",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         description="Радиус поиска в километрах",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Organization")
     *             )
     *         )
     *     )
     * )
     */
    public function searchByRadius(SearchRadiusRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $organizations = $this->organizationService->searchByRadius(
            $validated['latitude'],
            $validated['longitude'],
            $validated['radius']
        );

        return ApiResponse::collection(OrganizationResource::collection($organizations), 'Организации в радиусе найдены');
    }

    /**
     * @OA\Get(
     *     path="/organizations/search/area",
     *     summary="Поиск организаций в прямоугольной области",
     *     description="Поиск организаций в прямоугольной области",
     *     tags={"Organizations"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="min_lat",
     *         in="query",
     *         description="Минимальная широта",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="max_lat",
     *         in="query",
     *         description="Максимальная широта",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="min_lng",
     *         in="query",
     *         description="Минимальная долгота",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="max_lng",
     *         in="query",
     *         description="Максимальная долгота",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Organization")
     *             )
     *         )
     *     )
     * )
     */
    public function searchByArea(SearchAreaRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $organizations = $this->organizationService->searchByArea(
            $validated['min_lat'],
            $validated['max_lat'],
            $validated['min_lng'],
            $validated['max_lng']
        );

        return ApiResponse::collection(OrganizationResource::collection($organizations), 'Организации в области найдены');
    }

    /**
     * @OA\Get(
     *     path="/organizations/{id}",
     *     summary="Получить организацию по ID",
     *     description="Получить информацию об организации по ID",
     *     tags={"Organizations"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID организации",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Organization")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $organization = $this->organizationService->findById($id);

        if (!$organization) {
            return ApiResponse::notFound('Организация не найдена');
        }

        return ApiResponse::success(new OrganizationResource($organization), 'Организация найдена');
    }

    /**
     * @OA\Get(
     *     path="/organizations/search/activity-tree/{activityId}",
     *     summary="Поиск организаций по виду деятельности с дочерними",
     *     description="Поиск организаций по виду деятельности (включая дочерние)",
     *     tags={"Organizations"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         description="ID вида деятельности",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Organization")
     *             )
     *         )
     *     )
     * )
     */
    public function searchByActivityTree(int $activityId): JsonResponse
    {
        $organizations = $this->organizationService->searchByActivityTree($activityId);

        return ApiResponse::collection(OrganizationResource::collection($organizations), 'Организации по дереву деятельности найдены');
    }

    /**
     * @OA\Get(
     *     path="/organizations/search/name",
     *     summary="Поиск организаций по названию",
     *     description="Поиск организаций по названию",
     *     tags={"Organizations"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Название организации",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Organization")
     *             )
     *         )
     *     )
     * )
     */
    public function searchByName(SearchNameRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $organizations = $this->organizationService->searchByName($validated['name']);

        return ApiResponse::collection(OrganizationResource::collection($organizations), 'Организации по названию найдены');
    }
} 