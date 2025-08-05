<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchAreaRequest;
use App\Http\Requests\SearchNameRequest;
use App\Http\Requests\SearchRadiusRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\OrganizationResource;
use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
    /**
     * Получить организации в здании
     */
    public function getByBuilding(int $buildingId): JsonResponse
    {
        $organizations = Organization::with(['phones', 'activities', 'building'])
            ->where('building_id', $buildingId)
            ->get();

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
        $organizations = Organization::with(['phones', 'activities', 'building'])
            ->whereHas('activities', function ($query) use ($activityId) {
                $query->where('activities.id', $activityId);
            })
            ->get();

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

        $lat = $validated['latitude'];
        $lng = $validated['longitude'];
        $radius = $validated['radius'];

        $organizations = Organization::with(['phones', 'activities', 'building'])
            ->whereHas('building', function ($query) use ($lat, $lng, $radius) {
                // Check if we're using SQLite (for testing)
                if (config('database.default') === 'sqlite' || config('database.default') === 'testing') {
                    // Simple bounding box calculation for SQLite
                    $latDelta = $radius / 111.0; // Approximate km per degree latitude
                    $lngDelta = $radius / (111.0 * cos(deg2rad($lat))); // Approximate km per degree longitude
                    
                    $query->whereBetween('latitude', [$lat - $latDelta, $lat + $latDelta])
                          ->whereBetween('longitude', [$lng - $lngDelta, $lng + $lngDelta]);
                } else {
                    // Use Haversine formula for MySQL/PostgreSQL
                    $query->whereRaw(
                        '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?',
                        [$lat, $lng, $lat, $radius]
                    );
                }
            })
            ->get();

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

        $minLat = $validated['min_lat'];
        $maxLat = $validated['max_lat'];
        $minLng = $validated['min_lng'];
        $maxLng = $validated['max_lng'];

        $organizations = Organization::with(['phones', 'activities', 'building'])
            ->whereHas('building', function ($query) use ($minLat, $maxLat, $minLng, $maxLng) {
                $query->whereBetween('latitude', [$minLat, $maxLat])
                    ->whereBetween('longitude', [$minLng, $maxLng]);
            })
            ->get();

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
        $organization = Organization::with(['phones', 'activities', 'building'])->find($id);

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
        $activity = Activity::find($activityId);
        
        if (!$activity) {
            return ApiResponse::collection(OrganizationResource::collection(collect()), 'Организации по дереву деятельности найдены');
        }

        // Получаем все дочерние ID
        $childIds = $this->getChildActivityIds($activityId);
        $allIds = array_merge([$activityId], $childIds);

        $organizations = Organization::with(['phones', 'activities', 'building'])
            ->whereHas('activities', function ($query) use ($allIds) {
                $query->whereIn('activities.id', $allIds);
            })
            ->get();

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

        $organizations = Organization::with(['phones', 'activities', 'building'])
            ->where('name', 'like', '%' . $validated['name'] . '%')
            ->get();

        return ApiResponse::collection(OrganizationResource::collection($organizations), 'Организации по названию найдены');
    }

    /**
     * Получить все дочерние ID видов деятельности
     */
    private function getChildActivityIds(int $parentId): array
    {
        $childIds = [];
        $children = Activity::where('parent_id', $parentId)->get();

        foreach ($children as $child) {
            $childIds[] = $child->id;
            $childIds = array_merge($childIds, $this->getChildActivityIds($child->id));
        }

        return $childIds;
    }
} 