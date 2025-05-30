<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['username', 'name', 'email', 'password']; 
    protected $hidden = ['password', 'remember_token']; 

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function financialGoals()
    {
        return $this->hasMany(FinancialGoal::class);
    }

    public function userPoints()
    {
        return $this->hasOne(UserPoint::class);
    }


    public function savingsGoals()
    {
        return $this->hasMany(SavingsGoal::class);
    }
}