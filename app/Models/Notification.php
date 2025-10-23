<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'message',
        'role',
        'is_read',   // keep if you use it elsewhere, but read_at is preferred
        'read_at',
    ];

    protected $dates = [
        'read_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function notifications()
{
    return $this->hasMany(\App\Models\Notification::class);
}
}
