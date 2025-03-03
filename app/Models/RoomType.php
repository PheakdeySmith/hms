<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'base_price',
        'capacity',
        'has_air_conditioning',
        'has_tv',
        'has_refrigerator',
        'has_safe',
        'has_wifi',
        'has_minibar',
        'has_bathtub',
        'amenities',
        'image',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'base_price' => 'decimal:2',
        'capacity' => 'integer',
        'has_air_conditioning' => 'boolean',
        'has_tv' => 'boolean',
        'has_refrigerator' => 'boolean',
        'has_safe' => 'boolean',
        'has_wifi' => 'boolean',
        'has_minibar' => 'boolean',
        'has_bathtub' => 'boolean',
        'amenities' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the rooms for the room type.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get the available rooms for this room type.
     */
    public function availableRooms()
    {
        return $this->rooms()->where('status', 'available');
    }

    /**
     * Get the count of available rooms for this room type.
     */
    public function getAvailableRoomsCountAttribute()
    {
        return $this->availableRooms()->count();
    }
}
