<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserBook;
use App\Mail\BookReturnReminderMail;
use App\Notifications\BookReturnReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendBookReturnReminders extends Command
{
    protected $signature = 'emails:send-book-reminders';
    protected $description = 'Send reminders for book return dates';

    public function handle()
    {
        $today = Carbon::today();

        $borrows = UserBook::whereNotNull('borrow_days')
            ->whereNull('emailed_at')
            ->get();

        foreach ($borrows as $borrow) {
            if (!$borrow->user || !$borrow->book) continue;

            // Calculate return date
            $returnDate = $borrow->created_at->copy()->addDays($borrow->borrow_days);
            $daysLeft = $returnDate->diffInDays($today);

            if ($daysLeft === 3 || $daysLeft === 1) {
                // Send Email
                Mail::to($borrow->user->email)->send(new BookReturnReminderMail($borrow, $daysLeft));

                // Send WebSocket Notification
                $borrow->user->notify(new BookReturnReminder($borrow, $daysLeft));

                // Mark as emailed to prevent duplicate notifications
                $borrow->emailed_at = now();
                $borrow->save();

                $this->info("Reminder sent to {$borrow->user->email} for book: {$borrow->book->name}, {$daysLeft} days left.");
            }
        }
    }
}
