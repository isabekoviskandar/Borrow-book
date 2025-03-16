<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\UserBook;

class BookBorrowedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $borrow;

    public function __construct(UserBook $borrow)
    {
        $this->borrow = $borrow;
    }

    public function build()
    {
        return $this->subject('Book Borrowed Successfully')
            ->view('emails.book_borrowed');
    }
}
