<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalEvent extends Model
{
    protected $fillable = ['user_id', 'title', 'start_time', 'end_time', 'location', 'description'];
    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
