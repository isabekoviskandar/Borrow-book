<?php

namespace App\Console\Commands;

use App\Mail\BookDueInThreeDays;
use App\Mail\BookDueInOneDay;
use App\Models\UserBook;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckBookDueReminders extends Command
{
    protected $signature = 'books:check-due-reminders';
    protected $description = 'Check and send reminders for books due in 3 days or 1 day';

    public function handle()
    {
        $today = Carbon::today();

        $threeDaysLeft = UserBook::where('status', 'borrowed')
            ->whereDate('due_date', $today->copy()->addDays(3))
            ->get();

        foreach ($threeDaysLeft as $userBook) {
            Mail::to($userBook->user->email)->send(new BookDueInThreeDays(
                $userBook->book,
                $userBook->user,
                $userBook->due_date
            ));
            $this->info("Sent 3-day reminder for book ID {$userBook->book_id} to {$userBook->user->email}");
        }

        $oneDayLeft = UserBook::where('status', 'borrowed')
            ->whereDate('due_date', $today->copy()->addDay())
            ->get();

        foreach ($oneDayLeft as $userBook) {
            Mail::to($userBook->user->email)->send(new BookDueInOneDay(
                $userBook->book,
                $userBook->user,
                $userBook->due_date
            ));
            $this->info("Sent 1-day reminder for book ID {$userBook->book_id} to {$userBook->user->email}");
        }

        $this->info('Due date reminders checked.');
    }
}