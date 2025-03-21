<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{


    protected $fillable =
    [
        'name',
        'image',
        'author',
        'count',
        'price',
    ];


    public function user_book()
    {
        return $this->hasMany(UserBook::class , 'book_id');
    }
}
