<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;


    protected $fillable =
    [
        'name',
        'author',
        'price',
        'status',
    ];

    public function user_book()
    {
        return $this->hasMany(UserBook::class , 'book_id');
    }
}
