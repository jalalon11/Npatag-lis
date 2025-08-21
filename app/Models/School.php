<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class School extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'address',
        'principal',
        'logo_path',
        'grade_levels',
        'division_name',
        'division_code',
        'division_address',
        'region',
        'is_active',
        'is_primary',
        'last_details_update_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'grade_levels' => 'array',
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
        'last_details_update_at' => 'datetime',
    ];

    /**
     * Get the full URL for the school logo
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo_path) {
            return null;
        }

        try {
            // Use our proxy route to serve the image
            return route('image.proxy', ['path' => $this->logo_path]);
        } catch (\Exception $e) {
            Log::error('Error getting logo URL: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the division name for this school
     */
    public function getDivisionNameAttribute()
    {
        return $this->attributes['division_name'] ?? 'Division of Davao del Sur';
    }

    /**
     * Get the region for this school
     */
    public function getRegionAttribute()
    {
        return $this->attributes['region'] ?? 'Region XI';
    }

    /**
     * Get the sections in this school
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    /**
     * Get the teachers in this school
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'teacher');
    }

    /**
     * Get all users in this school
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }



    /**
     * Get the support tickets for this school
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }



    /**
     * Check if school details can be updated (once every 60 days)
     */
    public function canUpdateDetails(): bool
    {
        // If last_details_update_at is null, it means the school has never been updated
        if ($this->last_details_update_at === null) {
            return true;
        }

        // Check if 60 days have passed since the last update
        return now()->diffInDays($this->last_details_update_at) >= 60;
    }

    /**
     * Get the number of days remaining until the school details can be updated again
     */
    public function getDaysUntilNextUpdateAttribute(): int
    {
        // If last_details_update_at is null, it means the school has never been updated
        if ($this->last_details_update_at === null) {
            return 0;
        }

        // Calculate days passed since last update
        $daysPassed = now()->diffInDays($this->last_details_update_at);

        // If 60 or more days have passed, return 0 (can update now)
        if ($daysPassed >= 60) {
            return 0;
        }

        // Otherwise, return the number of days remaining
        return 60 - $daysPassed;
    }

    /**
     * Get the single primary school instance
     */
    public static function primary()
    {
        return static::where('is_primary', true)->first();
    }

    /**
     * Get the single school instance (for single school system)
     */
    public static function single()
    {
        return static::first();
    }
}
