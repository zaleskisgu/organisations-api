<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class OrganizationController extends Controller
{
    /**
     * Получить список всех организаций в конкретном здании
     * 
     * @OA\Get(
     *     path="/api/organizations/building/{buildingId}",
     *     summary="Получить организации в здании",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="buildingId",
     *         in="path",
     *         required=true,
     *         description="ID здания",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Organization"))
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
    public function getByBuilding(int $buildingId): JsonResponse
    {
        $building = Building::find($buildingId);
        
        if (!$building) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }
        
        $organizations = $building->organizations()
            ->with(['phones', 'activities', 'building'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $organizations
        ]);
    }

    /**
     * Получить список организаций по виду деятельности
     * 
     * @OA\Get(
     *     path="/api/organizations/activity/{activityId}",
     *     summary="Получить организации по виду деятельности",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         required=true,
     *         description="ID вида деятельности",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *         )
     *     )
     * )
     */
    public function getByActivity(int $activityId): JsonResponse
    {
        $activity = Activity::find($activityId);
        
        if (!$activity) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }
        
        $organizations = $activity->organizations()
            ->with(['phones', 'activities', 'building'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $organizations
        ]);
    }

    /**
     * Поиск организаций в радиусе
     * 
     * @OA\Get(
     *     path="/api/organizations/search/radius",
     *     summary="Поиск организаций в радиусе",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         required=true,
     *         description="Широта центральной точки",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         required=true,
     *         description="Долгота центральной точки",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         required=true,
     *         description="Радиус поиска в километрах",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *         )
     *     )
     * )
     */
    public function searchByRadius(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|numeric|min:0',
        ]);

        $lat = $validated['latitude'];
        $lng = $validated['longitude'];
        $radius = $validated['radius'];

        $organizations = Organization::with(['phones', 'activities', 'building'])
            ->whereHas('building', function ($query) use ($lat, $lng, $radius) {
                $query->whereRaw("
                    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                    sin(radians(latitude)))) <= ?
                ", [$lat, $lng, $lat, $radius]);
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $organizations
        ]);
    }

    /**
     * Поиск организаций в прямоугольной области
     * 
     * @OA\Get(
     *     path="/api/organizations/search/area",
     *     summary="Поиск организаций в прямоугольной области",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="min_lat",
     *         in="query",
     *         required=true,
     *         description="Минимальная широта",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="max_lat",
     *         in="query",
     *         required=true,
     *         description="Максимальная широта",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="min_lng",
     *         in="query",
     *         required=true,
     *         description="Минимальная долгота",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="max_lng",
     *         in="query",
     *         required=true,
     *         description="Максимальная долгота",
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *         )
     *     )
     * )
     */
    public function searchByArea(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'min_lat' => 'required|numeric|between:-90,90',
            'max_lat' => 'required|numeric|between:-90,90',
            'min_lng' => 'required|numeric|between:-180,180',
            'max_lng' => 'required|numeric|between:-180,180',
        ]);

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

        return response()->json([
            'success' => true,
            'data' => $organizations
        ]);
    }

    /**
     * Получить информацию об организации по ID
     * 
     * @OA\Get(
     *     path="/api/organizations/{id}",
     *     summary="Получить организацию по ID",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID организации",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Organization")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $organization = Organization::with(['phones', 'activities', 'building'])
            ->find($id);

        if (!$organization) {
            return response()->json([
                'success' => false,
                'message' => 'Организация не найдена'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $organization
        ]);
    }

    /**
     * Поиск организаций по виду деятельности (включая дочерние)
     * 
     * @OA\Get(
     *     path="/api/organizations/search/activity-tree/{activityId}",
     *     summary="Поиск организаций по виду деятельности с дочерними",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="activityId",
     *         in="path",
     *         required=true,
     *         description="ID вида деятельности",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *         )
     *     )
     * )
     */
    public function searchByActivityTree(int $activityId): JsonResponse
    {
        $activity = Activity::find($activityId);
        
        if (!$activity) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }
        
        $descendantIds = $this->getAllDescendantIds($activity);
        $descendantIds[] = $activityId;

        $organizations = Organization::whereHas('activities', function ($query) use ($descendantIds) {
            $query->whereIn('activities.id', $descendantIds);
        })
        ->with(['phones', 'activities', 'building'])
        ->get();

        return response()->json([
            'success' => true,
            'data' => $organizations
        ]);
    }

    /**
     * Поиск организаций по названию
     * 
     * @OA\Get(
     *     path="/api/organizations/search/name",
     *     summary="Поиск организаций по названию",
     *     tags={"Organizations"},
     *     security={{"apiKey":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         description="Название организации",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Organization"))
     *         )
     *     )
     * )
     */
    public function searchByName(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2',
        ]);

        $organizations = Organization::with(['phones', 'activities', 'building'])
            ->where('name', 'like', '%' . $validated['name'] . '%')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $organizations
        ]);
    }

    /**
     * Получить все здания
     * 
     * @OA\Get(
     *     path="/api/buildings",
     *     summary="Получить все здания",
     *     tags={"Buildings"},
     *     security={{"apiKey":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Building"))
     *         )
     *     )
     * )
     */
    public function getBuildings(): JsonResponse
    {
        $buildings = Building::with('organizations')->get();

        return response()->json([
            'success' => true,
            'data' => $buildings
        ]);
    }

    /**
     * Рекурсивно получаем ID всех дочерних деятельностей
     */
    private function getAllDescendantIds(Activity $activity): array
    {
        $ids = [];
        
        foreach ($activity->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getAllDescendantIds($child));
        }
        
        return $ids;
    }
} 