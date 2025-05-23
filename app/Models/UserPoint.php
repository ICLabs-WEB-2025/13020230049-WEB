<?php

// app/Models/UserPoint.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'savings_goal_id', 
        'points_earned',
        'description',
        'achievement_date',
        'achievement_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function savingsGoal()
    {
        return $this->belongsTo(SavingsGoal::class);
    }
}