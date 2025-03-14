<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBook extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'user_id',
        'book_id',
        'count',
        'date_of_borrowing',
        'total',
        'status',
        'due_date',
    ];

    protected $casts = [
        'date_of_borrowing' => 'date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
