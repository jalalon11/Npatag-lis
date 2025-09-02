<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Building extends Model
{
    protected $fillable = [
        'name',
        'description',
        'school_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the school that owns the building.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the rooms assigned to this building.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Section::class, 'building_id');
    }

    /**
     * Get active rooms in this building.
     */
    public function activeRooms(): HasMany
    {
        return $this->hasMany(Section::class, 'building_id')->where('is_active', true);
    }
}
