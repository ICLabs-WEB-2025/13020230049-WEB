<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name'
    ];
    // Relasi ke Transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
