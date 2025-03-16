<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create(['name' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'count' => 5, 'price' => 10.99]);
        Book::create(['name' => '1984', 'author' => 'George Orwell', 'count' => 3, 'price' => 8.99]);
        Book::create(['name' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'count' => 2, 'price' => 12.50]);
    }
}
