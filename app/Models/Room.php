<?php

namespace App\Models;

use App\Enums\RoomType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'type' => RoomType::class,
    ];

    protected $hidden = ['deleted_at', 'updated_at'];

    /**
     * Get the bookings made for this room.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
