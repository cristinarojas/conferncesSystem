<!DOCTYPE html>
<html>
<head>
    <title>Login - Predicaciones</title>
    <link rel="icon"type="image/ico" href="public/img/icon.ico">
    <link rel="stylesheet" type="text/css" href="public/css/login.css">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="login">
        <img src="public/img/logoLogin.png">
        <h1>Login</h1>
        <form method="post" action="panel.php">
            <input type="text" name="user" placeholder="Usuario" required="required" />
            <input type="password" name="password" placeholder="Password" required="required" />
            <button type="submit" name="login" class="btn btn-primary btn-block btn-large">Entrar</button>
        </form>
    </div>
</body>
</html>
