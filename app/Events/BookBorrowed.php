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
        return [
            'book_id' => $this->borrow->book_id,
            'name' => $this->borrow->book->name,
            'count' => $this->borrow->count,
            'borrow_days' => $this->borrow->borrow_days,
            'stock' => $this->borrow->book->count,
            'user' => $this->borrow->user->name,
        ];
    }
}
