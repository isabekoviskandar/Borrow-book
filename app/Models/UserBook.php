<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBook extends Model
{
    protected $fillable = ['user_id', 'book_id', 'count', 'total', 'borrow_days', 'emailed_at'];

    /**
     * Get the user that borrowed the book.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the book being borrowed.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}