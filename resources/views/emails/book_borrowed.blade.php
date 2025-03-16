<!DOCTYPE html>
<html>

<head>
    <title>Book Borrowed</title>
</head>

<body>
    <h1>Book Borrowed</h1>
    <p>Hello, {{ $borrow->user->name }},</p>
    <p>You have borrowed {{ $borrow->count }} copy(ies) of "{{ $borrow->book->name }}" by {{ $borrow->book->author }}
        for {{ $borrow->borrow_days }} days.</p>
    <p>Total Cost: ${{ $borrow->total }}</p>
    <p>Borrowed on: {{ $borrow->created_at }}</p>
    <p>Please return by: {{ $borrow->created_at->addDays($borrow->borrow_days) }}</p>
    <p>Thank you!</p>
</body>

</html>
