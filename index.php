<!DOCTYPE html>
<html>
<head>
    <title>Online Banking Login</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            background-image: url("bank2.png");
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
        }
        .login {
            width: 350px;
            margin: 150px auto;
            background: rgba(255,255,255,0.9);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        input, button {
            width: 90%;
            padding: 8px;
            margin: 8px 0;
        }
    </style>
</head>

<body>
<div class="login">
    <h2>Login</h2>

    <form method="POST" action="login.php">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
