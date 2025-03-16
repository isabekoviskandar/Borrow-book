<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\UserBook;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('user.index', compact('books'));
    }

    public function borrow(Request $request, $bookId)
    {
        $request->validate([
            'count' => 'required|integer|min:1',
            'borrow_days' => 'required|integer|min:1|max:30',
        ]);

        $book = Book::findOrFail($bookId);
        if ($book->count < $request->count) {
            return back()->with('error', 'Not enough books in stock.');
        }

        $user = auth()->user();
        $total = $book->price * $request->count;

        $borrow = UserBook::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'count' => $request->count,
            'total' => $total,
            'borrow_days' => $request->borrow_days,
            // 'emailed_at' => null (default, don’t set it here)
        ]);

        $book->decrement('count', $request->count);

        event(new \App\Events\BookBorrowed($borrow));

        return back()->with('success', 'Book borrowed successfully!');
    }

}
