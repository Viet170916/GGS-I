<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome Email</title>
</head>
<body>
    <h1>Welcome to our website!</h1>
    <p>Thank you for joining us.</p>
    <a href="{{url('http://localhost:3000/auth?token=' . $token)}}">Verify your account</a>
</body>
</html>
