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
        'region',
        'grade_levels',
        'division_name',
        'division_code',
        'division_address',
        'principal',
        'logo_path',
    ];

    /**
     * Default school details (used as fallbacks)
     */
    const DEFAULT_SCHOOL_NAME = 'Patag Elementary School';
    const DEFAULT_SCHOOL_CODE = 'PATAGES-2024';
    const DEFAULT_SCHOOL_ADDRESS = 'Patag, Naawan Misamis Oriental, Philippines';
    const DEFAULT_DIVISION_NAME = 'Division of Misamis Oriental';
    const DEFAULT_DIVISION_CODE = 'DDS-2024';
    const DEFAULT_DIVISION_ADDRESS = 'Pelaez Sports Complex, Don Apolinar Velez St, Cagayan de Oro, 9000 Lalawigan ng Misamis Oriental';
    const DEFAULT_REGION = 'Region X';
    const DEFAULT_GRADE_LEVELS = 'K, 1, 2, 3, 4, 5, 6';
    const DEFAULT_LOGO_PATH = 'images/logo.jpg';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
        'last_details_update_at' => 'datetime',
        'grade_levels' => 'string',
    ];

    /**
     * Accessor methods with fallback to defaults
     */
    public function getNameAttribute($value)
    {
        return $value ?: self::DEFAULT_SCHOOL_NAME;
    }

    public function getCodeAttribute($value)
    {
        return $value ?: self::DEFAULT_SCHOOL_CODE;
    }

    public function getAddressAttribute($value)
    {
        return $value ?: self::DEFAULT_SCHOOL_ADDRESS;
    }

    public function getRegionAttribute($value)
    {
        return $value ?: self::DEFAULT_REGION;
    }

    public function getGradeLevelsAttribute($value)
    {
        return $value ?: self::DEFAULT_GRADE_LEVELS;
    }

    public function getDivisionNameAttribute($value)
    {
        return $value ?: self::DEFAULT_DIVISION_NAME;
    }

    public function getDivisionCodeAttribute($value)
    {
        return $value ?: self::DEFAULT_DIVISION_CODE;
    }

    public function getDivisionAddressAttribute($value)
    {
        return $value ?: self::DEFAULT_DIVISION_ADDRESS;
    }

    public function getLogoPathAttribute($value)
    {
        return $value ?: self::DEFAULT_LOGO_PATH;
    }

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
     * Get the buildings in this school
     */
    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
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
