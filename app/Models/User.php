<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'room_id',
        'contract_start',
        'contract_end',
    ];

    /**
     * Get the room that the user is currently occupying.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'contract_start' => 'datetime',
            'contract_end' => 'datetime',
        ];
    }

    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class)->latestOfMany();
    }

    public function latestSentReceipt()
    {
        return $this->hasOne(Message::class, 'receiver_id')->whereNotNull('attachment')->latestOfMany();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Determine if the user has verified their email address.
     * Admins are always considered verified.
     */
    public function hasVerifiedEmail(): bool
    {
        if ($this->role === 'admin') {
            return true;
        }
        
        return ! is_null($this->email_verified_at);
    }
}
