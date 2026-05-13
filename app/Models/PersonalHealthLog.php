<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalHealthLog extends Model
{
    protected $fillable = ['user_id', 'gender', 'age', 'height', 'weight', 'bmi', 'category'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
