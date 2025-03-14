<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookDueInOneDay extends Mailable
{
    use Queueable, SerializesModels;

    public $book;
    public $user;
    public $dueDate;

    public function __construct($book, $user, $dueDate)
    {
        $this->book = $book;
        $this->user = $user;
        $this->dueDate = $dueDate;
    }

    public function build()
    {
        return $this->subject('Book Due Tomorrow')
                    ->view('emails.one_day')
                    ->with([
                        'bookName' => $this->book->name,
                        'userName' => $this->user->name,
                        'dueDate' => $this->dueDate,
                    ]);
    }
}