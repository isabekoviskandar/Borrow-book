@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Available Books</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div id="notifications"></div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                    <tr data-book-id="{{ $book->id }}">
                        <td>{{ $book->name }}</td>
                        <td>
                            @if ($book->image)
                                <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->name }}" width="50">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>{{ $book->author }}</td>
                        <td>${{ $book->price }}</td>
                        <td>{{ $book->count }}</td>
                        <td>
                            @auth
                                @if ($book->count > 0)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#borrowModal{{ $book->id }}">
                                        Borrow
                                    </button>
                                    <div class="modal fade" id="borrowModal{{ $book->id }}" tabindex="-1"
                                        aria-labelledby="borrowModalLabel{{ $book->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="borrowModalLabel{{ $book->id }}">Borrow
                                                        {{ $book->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('books.borrow', $book->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="quantity{{ $book->id }}"
                                                                class="form-label">Quantity</label>
                                                            <input type="number" class="form-control"
                                                                id="quantity{{ $book->id }}" name="count" min="1"
                                                                max="{{ $book->count }}" required>
                                                            <small class="form-text text-muted">Available:
                                                                {{ $book->count }}</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="days{{ $book->id }}" class="form-label">Borrow
                                                                Days</label>
                                                            <input type="number" class="form-control"
                                                                id="days{{ $book->id }}" name="borrow_days" min="1"
                                                                max="30" required>
                                                            <small class="form-text text-muted">Max: 30 days</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Borrow Now</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Out of Stock</span>
                                @endif
                            @endauth
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login to Borrow</a>
                            @endguest
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        Echo.channel('books')
            .listen('BookBorrowed', (e) => {
                document.getElementById('notifications').innerHTML = `
                <div class="alert alert-info">
                    ${e.user} borrowed ${e.count} copy(ies) of "${e.name}" for ${e.borrow_days} days. Stock left: ${e.stock}
                </div>
            `;
                document.querySelector(`tr[data-book-id="${e.book_id}"] td:nth-child(5)`).textContent = e.stock;
            });
    </script>
@endpush
