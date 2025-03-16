<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\UserBook;

class BookBorrowed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $borrow;

    public function __construct(UserBook $borrow)
    {
        $this->borrow = $borrow;
    }

    public function broadcastOn()
    {
        return new Channel('books');
    }

    public function broadcastWith()
    {
        $bookName = $this->borrow->book ? $this->borrow->book->name : 'Unknown Book';
        $bookStock = $this->borrow->book ? $this->borrow->book->count : 0;
        $userName = $this->borrow->user ? $this->borrow->user->name : 'Unknown User';

        return [
            'book_id' => $this->borrow->book_id,
            'name' => $bookName,
            'count' => $this->borrow->count,
            'borrow_days' => $this->borrow->borrow_days,
            'stock' => $bookStock,
            'user' => $userName,
        ];
    }
}