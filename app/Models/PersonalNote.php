<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalNote extends Model
{
    protected $fillable = ['user_id', 'title', 'content', 'is_pinned'];
    protected $casts = ['is_pinned' => 'boolean'];
}

class PersonalTask extends Model
{
    protected $fillable = ['user_id', 'title', 'is_completed'];
    protected $casts = ['is_completed' => 'boolean'];
}

class PersonalEvent extends Model
{
    protected $fillable = ['user_id', 'title', 'start_time', 'end_time', 'location', 'description'];
    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime'];
}

class PersonalReminder extends Model
{
    protected $fillable = ['user_id', 'title', 'remind_at', 'is_notified'];
    protected $casts = ['remind_at' => 'datetime', 'is_notified' => 'boolean'];
}

class PersonalFinance extends Model
{
    protected $fillable = ['user_id', 'type', 'amount', 'category', 'description', 'date'];
    protected $casts = ['date' => 'date', 'amount' => 'decimal:2'];
}

class PersonalHealthLog extends Model
{
    protected $fillable = ['user_id', 'gender', 'age', 'height', 'weight', 'bmi', 'category'];
}
