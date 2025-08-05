<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="OrganizationPhone",
 *     title="OrganizationPhone",
 *     description="Модель телефона организации"
 * )
 */
class OrganizationPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'phone',
    ];

    /**
     * @OA\Property(
     *     property="id",
     *     type="integer",
     *     description="ID телефона"
     * )
     * @OA\Property(
     *     property="organization_id",
     *     type="integer",
     *     description="ID организации"
     * )
     * @OA\Property(
     *     property="phone",
     *     type="string",
     *     description="Номер телефона"
     * )
     * @OA\Property(
     *     property="organization",
     *     ref="#/components/schemas/Organization",
     *     description="Организация"
     * )
     */

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
} 