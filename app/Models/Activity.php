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
 *     schema="Activity",
 *     title="Activity",
 *     description="Модель вида деятельности"
 * )
 */
class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'level',
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    /**
     * @OA\Property(
     *     property="id",
     *     type="integer",
     *     description="ID вида деятельности"
     * )
     * @OA\Property(
     *     property="name",
     *     type="string",
     *     description="Название вида деятельности"
     * )
     * @OA\Property(
     *     property="parent_id",
     *     type="integer",
     *     nullable=true,
     *     description="ID родительской деятельности"
     * )
     * @OA\Property(
     *     property="level",
     *     type="integer",
     *     description="Уровень вложенности"
     * )
     * @OA\Property(
     *     property="parent",
     *     ref="#/components/schemas/Activity",
     *     description="Родительская деятельность"
     * )
     * @OA\Property(
     *     property="children",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Activity"),
     *     description="Дочерние деятельности"
     * )
     * @OA\Property(
     *     property="organizations",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Organization"),
     *     description="Организации с этим видом деятельности"
     * )
     */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_activity');
    }

    public function getAllChildren()
    {
        return $this->children()->with('children');
    }

    public function getAllDescendants()
    {
        return $this->children()->with('getAllDescendants');
    }
} 