<?php

namespace App\Http\Controllers;

use App\Events\BookBorrowedEvent;
use App\Mail\BookBorrowed;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BorrowBookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('user.index', compact('books')); 
    }

    public function borrow($id)
    {
        $book = Book::findOrFail($id);

        if ($book->status !== 'available') {
            return redirect()->route('dashboard')->with('error', 'Book is not available.');
        }

        $user = auth()->user();
        $book->status = 'borrowed';
        $book->save();

        try {
            Log::info('Attempting to send email with config: ', config('mail.mailers.smtp'));
            Mail::to($user->email)->send(new BookBorrowed($book, $user));
            Log::info('Email sent successfully to: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Book borrowed, but email failed to send.');
        }

        broadcast(new BookBorrowedEvent($book, $user))->toOthers();

        return redirect()->route('dashboard')->with('success', 'Book borrowed successfully!');
    }
}