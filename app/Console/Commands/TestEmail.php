<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'email:test {email}';
    protected $description = 'Send a test email to the specified address';

    public function handle()
    {
        $email = $this->argument('email');

        try {
            Mail::raw('This is a test email from Laravel.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email from Borrow Book');
            });
            $this->info("Test email sent successfully to $email!");
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
        }
    }
}