<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookBorrowed extends Mailable
{
    use Queueable, SerializesModels;

    public $book;
    public $user;

    public function __construct($book, $user)
    {
        $this->book = $book;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Book Borrowed Successfully')
                    ->view('emails.book_borrowed')
                    ->with([
                        'bookName' => $this->book->name,
                        'userName' => $this->user->name,
                    ]);
    }
}