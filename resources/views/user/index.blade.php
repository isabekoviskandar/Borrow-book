<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Dashboard - Boorow Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-12">
                <nav class="navbar bg-body-tertiary">
                    <div class="container-fluid">
                        <a href="/" class="navbar-brand">Boorow Book</a>
                        <div class="d-flex align-items-center">
                            @auth
                                <span class="me-3">Welcome, {{ Auth::user()->name }}!</span>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                                </form>
                            @else
                                <a href="{{ route('register') }}" class="btn btn-primary me-2">Register</a>
                                <a href="{{ route('login') }}" class="btn btn-success">Login</a>
                            @endauth
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h2 class="mb-4">Available Books</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse ($books as $book)
                <div class="col">
                    <div class="card h-100">
                        @if ($book->image)
                            <img src="{{ asset('storage/' . $book->image) }}" class="card-img-top" alt="{{ $book->name }} cover">
                        @else
                            <img src="{{ asset('default-book.jpg') }}" class="card-img-top" alt="Default book cover">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $book->name }}</h5>
                            <p class="card-text"><strong>Author:</strong> {{ $book->author }}</p>
                            <p class="card-text"><strong>Price:</strong> ${{ number_format($book->price, 2) }}</p>
                            <p class="card-text"><strong>Available:</strong> {{ $book->count ?? 'N/A' }}</p>
                            <p class="card-text">
                                @if ($book->status == 'available')
                                    <span class="badge bg-success">Available</span>
                                @elseif ($book->status == 'borrowed')
                                    <span class="badge bg-danger">Borrowed</span>
                                @elseif ($book->status == 'reserved')
                                    <span class="badge bg-warning text-dark">Reserved</span>
                                @else
                                    <span class="badge bg-secondary">{{ $book->status }}</span>
                                @endif
                            </p>
                            @if ($book->status == 'available')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#borrowModal{{ $book->id }}">
                                    Borrow Book
                                </button>

                                <div class="modal fade" id="borrowModal{{ $book->id }}" tabindex="-1" aria-labelledby="borrowModalLabel{{ $book->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="borrowModalLabel{{ $book->id }}">Borrow {{ $book->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('books.borrow', $book->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="days{{ $book->id }}" class="form-label">How many days would you like to borrow this book?</label>
                                                        <input type="number" class="form-control" id="days{{ $book->id }}" name="days" min="1" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Confirm Borrow</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="#" class="btn btn-secondary disabled">Currently Unavailable</a>
                                @if ($book->status == 'borrowed')
                                    <p class="mt-2"><strong>Due Date:</strong> {{ $book->userBooks()->where('status', 'borrowed')->latest()->first()->due_date ?? 'N/A' }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        No books are currently available in the library.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Echo.private('user.{{ Auth::id() }}')
                .listen('.book.borrowed', (e) => {
                    alert(`You have borrowed "${e.book.name}" successfully! Check your email.`);
                });
        });
    </script>
</body>
</html>