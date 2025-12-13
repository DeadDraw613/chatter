<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_a_id',
        'user_b_id',
        'status',
        'requester_id',
        'removed_by_a',
        'removed_by_b'
    ];

    // Relationships
    public function userA()
    {
        return $this->belongsTo(User::class, 'user_a_id');
    }

    public function userB()
    {
        return $this->belongsTo(User::class, 'user_b_id');
    }

    // Scope for active connections
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Helper: check if connection involves given user
    public function involves($userId)
    {
        return $this->user_a_id === $userId || $this->user_b_id === $userId;
    }
}