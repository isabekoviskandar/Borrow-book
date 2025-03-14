<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BorrowBookController extends Controller
{
    public function index()
    {
        // Fetch all books from the database
        $books = Book::all();

        // Return view with books data
        return view('user.index', compact('books'));
    }
}