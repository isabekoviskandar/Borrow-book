<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class BookReturnReminderMail extends Mailable
{
    public $borrow;
    public $daysLeft;

    public function __construct($borrow, $daysLeft)
    {
        $this->borrow = $borrow;
        $this->daysLeft = $daysLeft;
    }

    public function build()
    {
        return $this->subject("Reminder: {$this->daysLeft} days left to return '{$this->borrow->book->name}'")
                    ->view('emails.book_return_reminder');
    }
}
