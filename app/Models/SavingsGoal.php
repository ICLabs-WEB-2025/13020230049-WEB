<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'goal_name', 'target_amount', 'current_amount', 'target_date'
    ];
    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
