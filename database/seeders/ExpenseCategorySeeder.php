<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Untuk menggunakan DB facade
use App\Models\ExpenseCategory; // Jika kamu ingin menggunakan model Eloquent

// Run This Commant for data Dumy
// php artisan make:seeder ExpenseCategorySeeder
class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expense_categories')->delete(); 

        $categories = [
            // Pemasukan
            ['id' => 1, 'category_name' => 'Gaji', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'category_name' => 'Bisnis', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'category_name' => 'Investasi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'category_name' => 'Hadiah', 'created_at' => now(), 'updated_at' => now()],
            
            // Pengeluaran
            ['id' => 6, 'category_name' => 'Makanan & Minuman', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'category_name' => 'Transportasi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'category_name' => 'Belanja', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'category_name' => 'Tagihan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'category_name' => 'Hiburan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'category_name' => 'Kesehatan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'category_name' => 'Pendidikan', 'created_at' => now(), 'updated_at' => now()],

            ['id' => 13, 'category_name' => 'Lainnya', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('expense_categories')->insert($categories);
    }
}

// 