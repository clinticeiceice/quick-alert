<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'reporter_id',
        'level',
        'description',
        'status',
        'designated_to',
        'reported_at',
    ];

    /**
     * Relationship: Report belongs to a Reporter (User).
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Optional: Relationship for the user who approved the report
     * (Designated Personnel).
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
