<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @OA\Schema(
 *     schema="Organization",
 *     title="Organization",
 *     description="Модель организации"
 * )
 */
class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'building_id',
    ];

    /**
     * @OA\Property(
     *     property="id",
     *     type="integer",
     *     description="ID организации"
     * )
     * @OA\Property(
     *     property="name",
     *     type="string",
     *     description="Название организации"
     * )
     * @OA\Property(
     *     property="building_id",
     *     type="integer",
     *     description="ID здания"
     * )
     * @OA\Property(
     *     property="building",
     *     ref="#/components/schemas/Building",
     *     description="Здание организации"
     * )
     * @OA\Property(
     *     property="phones",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/OrganizationPhone"),
     *     description="Телефоны организации"
     * )
     * @OA\Property(
     *     property="activities",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Activity"),
     *     description="Виды деятельности"
     * )
     */

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'organization_activity');
    }

    public function phones(): HasMany
    {
        return $this->hasMany(OrganizationPhone::class);
    }
} 