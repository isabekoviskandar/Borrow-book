<?php

namespace App\Http\Controllers;

use App\Events\BookBorrowedEvent;
use App\Mail\BookBorrowed;
use App\Models\Book;
use App\Models\UserBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BorrowBookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('user.index', compact('books'));
    }

    public function borrow(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        if ($book->status !== 'available') {
            return redirect()->route('dashboard')->with('error', 'Book is not available.');
        }

        $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        $days = (int) $request->input('days'); // Cast to integer
        $user = auth()->user();

        $total = $book->price * $days * 100;

        if (isset($book->count) && $book->count > 0) {
            $book->count -= 1;
            if ($book->count == 0) {
                $book->status = 'borrowed';
            }
            $book->save();
        } else {
            $book->status = 'borrowed';
            $book->save();
        }

        UserBook::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'count' => $days,
            'total' => $total,
            'status' => 'borrowed',
            'date_of_borrowing' => now()->toDateString(),
            'due_date' => Carbon::now()->addDays($days)->toDateString(),
        ]);

        try {
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