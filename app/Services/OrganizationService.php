<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class OrganizationService
{
    /**
     * Получить организации в здании
     */
    public function getByBuilding(int $buildingId): Collection
    {
        return Organization::with(['phones', 'activities', 'building'])
            ->where('building_id', $buildingId)
            ->get();
    }

    /**
     * Получить организации по виду деятельности
     */
    public function getByActivity(int $activityId): Collection
    {
        return Organization::with(['phones', 'activities', 'building'])
            ->whereHas('activities', function ($query) use ($activityId) {
                $query->where('activities.id', $activityId);
            })
            ->get();
    }

    /**
     * Поиск организаций в радиусе
     */
    public function searchByRadius(float $latitude, float $longitude, float $radius): Collection
    {
        return Organization::with(['phones', 'activities', 'building'])
            ->whereHas('building', function ($query) use ($latitude, $longitude, $radius) {
                // Check if we're using SQLite (for testing)
                if (config('database.default') === 'sqlite' || config('database.default') === 'testing') {
                    // Simple bounding box calculation for SQLite
                    $latDelta = $radius / 111.0; // Approximate km per degree latitude
                    $lngDelta = $radius / (111.0 * cos(deg2rad($latitude))); // Approximate km per degree longitude
                    
                    $query->whereBetween('latitude', [$latitude - $latDelta, $latitude + $latDelta])
                          ->whereBetween('longitude', [$longitude - $lngDelta, $longitude + $lngDelta]);
                } else {
                    // Use Haversine formula for MySQL/PostgreSQL
                    $query->whereRaw(
                        '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?',
                        [$latitude, $longitude, $latitude, $radius]
                    );
                }
            })
            ->get();
    }

    /**
     * Поиск организаций в прямоугольной области
     */
    public function searchByArea(float $minLat, float $maxLat, float $minLng, float $maxLng): Collection
    {
        return Organization::with(['phones', 'activities', 'building'])
            ->whereHas('building', function ($query) use ($minLat, $maxLat, $minLng, $maxLng) {
                $query->whereBetween('latitude', [$minLat, $maxLat])
                    ->whereBetween('longitude', [$minLng, $maxLng]);
            })
            ->get();
    }

    /**
     * Получить организацию по ID
     */
    public function findById(int $id): ?Organization
    {
        return Organization::with(['phones', 'activities', 'building'])->find($id);
    }

    /**
     * Поиск организаций по дереву деятельности
     */
    public function searchByActivityTree(int $activityId): Collection
    {
        $activity = Activity::find($activityId);
        
        if (!$activity) {
            return new Collection();
        }

        // Получаем все дочерние ID
        $childIds = $this->getChildActivityIds($activityId);
        $allIds = array_merge([$activityId], $childIds);

        return Organization::with(['phones', 'activities', 'building'])
            ->whereHas('activities', function ($query) use ($allIds) {
                $query->whereIn('activities.id', $allIds);
            })
            ->get();
    }

    /**
     * Поиск организаций по названию
     */
    public function searchByName(string $name): Collection
    {
        return Organization::with(['phones', 'activities', 'building'])
            ->where('name', 'like', '%' . $name . '%')
            ->get();
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