<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'type',
        'price',
        'quota',
        'status',
        'letak',
        'image',
        'description',
        'rental_type',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class)->orderByDesc('is_thumbnail')->orderBy('order');
    }

    public function thumbnail()
    {
        return $this->hasOne(RoomImage::class)->where('is_thumbnail', true)
            ->orWhereHas('room', fn($q) => $q->where('id', $this->id))
            ->orderByDesc('is_thumbnail')->orderBy('order')->limit(1);
    }

    /**
     * Get the cover image URL (thumbnail first, fallback to first image).
     */
    public function getCoverImageUrl(): ?string
    {
        $cover = $this->images->firstWhere('is_thumbnail', true) ?? $this->images->first();
        return $cover ? \Illuminate\Support\Facades\Storage::url($cover->image) : null;
    }
}
