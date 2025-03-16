<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;

class BookReturnReminder extends Notification
{
    public $borrow;
    public $daysLeft;

    public function __construct($borrow, $daysLeft)
    {
        $this->borrow = $borrow;
        $this->daysLeft = $daysLeft;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Reminder: {$this->daysLeft} days left to return '{$this->borrow->book->name}'")
            ->line("You have {$this->daysLeft} day(s) left to return '{$this->borrow->book->name}'.")
            ->line("Return it before {$this->borrow->created_at->copy()->addDays($this->borrow->borrow_days)->format('Y-m-d')} to avoid penalties.");
    }

    public function toDatabase($notifiable)
    {
        return [
            'book' => $this->borrow->book->name,
            'daysLeft' => $this->daysLeft,
            'returnDate' => $this->borrow->created_at->copy()->addDays($this->borrow->borrow_days)->format('Y-m-d'),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "You have {$this->daysLeft} day(s) left to return '{$this->borrow->book->name}'.",
            'returnDate' => $this->borrow->created_at->copy()->addDays($this->borrow->borrow_days)->format('Y-m-d'),
        ]);
    }
}
