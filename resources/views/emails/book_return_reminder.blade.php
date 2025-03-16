<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Dear {{ $borrow->user->name }},</p>

    <p>This is a reminder that you have {{ $daysLeft }} day(s) left to return the book:
        <strong>{{ $borrow->book->name }}</strong>.</p>

    <p>Return it before
        <strong>{{ $borrow->created_at->copy()->addDays($borrow->borrow_days)->format('Y-m-d') }}</strong> to avoid
        penalties.</p>

    <p>Thank you.</p>

</body>

</html>
