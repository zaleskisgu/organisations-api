<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @OA\Schema(
 *     schema="Building",
 *     title="Building",
 *     description="Модель здания"
 * )
 */
class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * @OA\Property(
     *     property="id",
     *     type="integer",
     *     description="ID здания"
     * )
     * @OA\Property(
     *     property="address",
     *     type="string",
     *     description="Адрес здания"
     * )
     * @OA\Property(
     *     property="latitude",
     *     type="number",
     *     format="float",
     *     description="Широта"
     * )
     * @OA\Property(
     *     property="longitude",
     *     type="number",
     *     format="float",
     *     description="Долгота"
     * )
     * @OA\Property(
     *     property="organizations",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Organization"),
     *     description="Организации в здании"
     * )
     */

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }
} 