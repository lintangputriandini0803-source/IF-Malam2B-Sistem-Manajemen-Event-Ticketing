<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form method="post" action="/login">
        @csrf
        
        <label>Usename</label>
        <input type="text" name="username"><br><br>
        <label>Password</label>
        <input type="password" name="password">
        <button type="submit">login</button>
    </form>
</body>
</html>