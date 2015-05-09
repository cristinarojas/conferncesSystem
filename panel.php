<?php
include "lib/db.php";

// Validar login de usuario
$username = isset($_COOKIE["preUsername"]) ? $_COOKIE["preUsername"] : false;
$password = isset($_COOKIE["prePassword"]) ? $_COOKIE["prePassword"] : false;

$query = "SELECT * FROM users WHERE user = '$username' AND password = '$password'";
$result = $db->query($query);
$data = $result->fetch_assoc();

// Si el usuario no existe
if (!$data) {
    if (isset($_POST["login"])) {
        $username = isset($_POST["user"]) ? $_POST["user"] : false;
        $password = isset($_POST["password"]) ? md5($_POST["password"]) : false;
        $query = "SELECT * FROM users WHERE user = '$username' AND password = '$password'";
        $result = $db->query($query);
        $data = $result->fetch_assoc();

        if (!$data) {
            echo '<script>alert("Login incorrecto"); window.location.href = "panel.php"; </script>';
        } else {
            setcookie("preUsername", $data["user"], time() + 3600);
            setcookie("prePassword", $data["password"], time() + 3600);

            header("location: panel.php");
        }
    } else {
        include "templates/login.php";
    }
} else {
    if (isset($_GET["action"]) && $_GET["action"] === 'logout') {
        setcookie("preUsername");
        setcookie("prePassword");

        header("location: panel.php");
    }

    if (isset($_POST["save"])) {
        $autor = $_POST["autor"];
        $sermonName = $_POST["sermonName"];
        $sermonFile = $_FILES["sermonFile"];
        $ulrImage = $_POST["ulrImage"];

        if ($autor === "" || $sermonName === "" || $sermonFile === "" || $ulrImage === "") {
            die('Todos los campos son obligatorios.');
        }

        $uploaddir  = 'files/audios/';
        $uploadfile = $uploaddir . basename($sermonFile['name']);

        $result = $db->query("SELECT url_audio FROM predicaciones WHERE url_audio = '$uploadfile'");

        if ($result->num_rows > 0) {
            echo '<script>alert("Archivo existente, sube otro."); window.location.href = "panel.php"; </script>';
            exit;
        } else {
            // Validar extensiones de audios
            $extensions = array('mp3', 'wma', 'wmv');
            $extension = end(explode('.', $sermonFile['name']));


            if (!in_array($extension, $extensions)) {
                echo '<script>alert("La extensión de audio es incorrecta (Aceptadas: .mp3, .wma y .wmv)"); window.location.href = "panel.php"; </script>';
                exit;
            } else {
                if (!move_uploaded_file($sermonFile['tmp_name'], $uploadfile)) {
                    echo '<script>alert("Falló al subir archivo."); window.location.href = "panel.php"; </script>';
                } else {
                    $query = "INSERT INTO predicaciones (
                        nombre_autor,
                        url_audio,
                        url_imagen,
                        titulo_audio)
                        VALUES (
                        '$autor',
                        '$uploadfile',
                        '$ulrImage',
                        '$sermonName')";

                    $insert = $db->query($query);

                    echo '<script>alert("Predicacion insertada con exito"); window.location.href = "panel.php"; </script>';
                    exit;
                }
            }
        }
    }
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel - predicaciones</title>
    <meta charset="utf-8">
    <link rel="icon" type="image/ico" href="public/img/icon.ico">
    <link rel="stylesheet" type="text/css" href="public/css/panel.css">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200italic' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container clearfix">
        <img src="public/img/logoLogin.png">
        <h3>¡&nbsp;Bienvenido<span>&nbsp; <?= $_COOKIE["preUsername"]; ?></span>&nbsp;!</h3>
        <span class="logout"><a href="panel.php?action=logout">Salir</a></span>
        <div class="form">
            <form action="panel.php" method="post" enctype="multipart/form-data">

                <label>Nombre de la predicación&nbsp;
                    <input type="text" name="sermonName" placeholder="Nombre predicación" class="input2" />
                </label>

                <label>Pastor o autor&nbsp;
                    <input type="text" name="autor" placeholder="Nombre de autor" class="input1"  />
                </label>

                <label>Seleccionar predicación
                    <input type="file" name="sermonFile" class="file input3" />
                </label>

                <label>Url de la imagen&nbsp;
                    <input type="text" name="ulrImage"  splaceholder="http://..." class="input4" />
                </label>
                <input type="submit" name="save" value="Guardar" class="btn btn-primary btn-block btn-large" />
            </form>
        </div>
    </div>
</body>
</html>
<?php
}


