<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserBook;
use App\Mail\BookBorrowedMail;
use Illuminate\Support\Facades\Mail;

class SendBorrowEmails extends Command
{
    protected $signature = 'emails:send-borrow';
    protected $description = 'Send emails for new book borrows';

    public function handle()
    {
        $borrows = UserBook::whereNull('emailed_at')->get();
        if ($borrows->isEmpty()) {
            $this->info('No new borrows to email.');
            return;
        }

        foreach ($borrows as $borrow) {
            if (!$borrow->user) {
                $this->error("Skipping borrow ID {$borrow->id}: No associated user (user_id: {$borrow->user_id})");
                continue;
            }

            try {
                Mail::to($borrow->user->email)->send(new BookBorrowedMail($borrow));
                $borrow->update(['emailed_at' => now()]);
                $this->info("Email sent to {$borrow->user->email} for {$borrow->book->name}");
            } catch (\Exception $e) {
                $this->error("Failed to send email for borrow ID {$borrow->id}: " . $e->getMessage());
            }
        }
        $this->info('Borrow emails processed successfully!');
    }
}