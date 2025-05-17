<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'amount', 'transaction_type', 'description', 'date'
    ];

    protected $dates = ['created_at', 'updated_at', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function logs()
    {
        return $this->hasMany(TransactionLog::class);
    }
}
