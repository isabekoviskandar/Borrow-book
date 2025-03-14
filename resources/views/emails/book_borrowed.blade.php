<!DOCTYPE html>
<html>
<head>
    <title>Book Borrowed</title>
</head>
<body>
    <h1>Hello, {{ $userName }}!</h1>
    <p>You have successfully borrowed the book: <strong>{{ $bookName }}</strong>.</p>
    <p>Duration: {{ $days }} days</p>
    <p>Total Cost: ${{ number_format($total, 2) }}</p>
    <p>Date of Borrowing: {{ $date }}</p>
    <p>Enjoy your reading!</p>
    <p>Regards,<br>Boorow Book Team</p>
</body>
</html>