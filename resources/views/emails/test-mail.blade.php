<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>Send Email Success</h2>
    <h3>Email dikirim ke: {{ $email }}</h3>
    <p>Untuk mengatur ulang kata sandi Anda, silakan klik link di bawah ini:</p>
    <a href="{{ $resetLink }}">Reset Password</a>
</body>
</html>