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
        'report_id',  // ✅ ADD THIS: Allows mass assignment for report_id
        'is_read',    // Keep if you use it elsewhere
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
}
