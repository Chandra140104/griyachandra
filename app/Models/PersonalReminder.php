<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalReminder extends Model
{
    protected $fillable = ['user_id', 'title', 'remind_at', 'is_notified'];
    protected $casts = ['remind_at' => 'datetime', 'is_notified' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
