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
        $urlImage = $_POST["urlImage"];
        $urlAudio = $_POST["urlAudio"];

        if ($autor === "" || $sermonName === "") {
            die('Todos los campos son obligatorios.');
        }

        $uploaddir  = 'files/audios/';
        $uploadfile = $uploaddir . basename($sermonFile['name']);

        if ($urlAudio !== "") {
            $uploadfile = $urlAudio;
        }

        $result = $db->query("SELECT url_audio FROM predicaciones WHERE url_audio = '$uploadfile'");

        if ($result->num_rows > 0) {
            echo '<script>alert("Archivo existente, sube otro."); window.location.href = "panel.php"; </script>';
            exit;
        } else {
            if ($sermonFile['tmp_name'] === '' && $urlAudio === "") {
                echo '<script>alert("Debes subir un archivo o especificar URL"); window.location.href = "panel.php"; </script>';
                exit;
            }

            // Validar extensiones de audios
            $extensions = array('mp3', 'wma', 'wmv');
            $extension = end(explode('.', $sermonFile['name']));

            if ($urlAudio === "" && !in_array($extension, $extensions)) {
                echo '<script>alert("La extension de audio es incorrecta (Aceptadas: .mp3, .wma y .wmv)"); window.location.href = "panel.php"; </script>';
                exit;
            } else {
                if ($urlAudio === "" && !move_uploaded_file($sermonFile['tmp_name'], $uploadfile)) {
                    echo '<script>alert("Fallo al subir archivo."); window.location.href = "panel.php"; </script>';
                } else {
                    if ($urlAudio !== "") {
                        $uploadfile = $urlAudio;
                    }

                    if ($urlImage === "") {
                        $urlImage = "public/img/audio.jpg";
                    }

                    $query = "INSERT INTO predicaciones (
                        nombre_autor,
                        url_audio,
                        url_imagen,
                        titulo_audio)
                        VALUES (
                        '$autor',
                        '$uploadfile',
                        '$urlImage',
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
                    <input type="text" name="sermonName" placeholder="Nombre predicación" class="input2" required/>
                </label>

                <label>Pastor o autor&nbsp;
                    <input type="text" name="autor" placeholder="Nombre de autor" class="input1" required/>
                </label>

                <label>Subir audio o especificar Url del audio
                    <input type="file" name="sermonFile" class="file input3" />
                    <input type="text" name="urlAudio" placeholder="Url audio" class="input5"  />
                </label>

                <label>Url de la imagen&nbsp;
                    <input type="text" name="urlImage"  splaceholder="http://..." class="input4" />
                </label>
                <input type="submit" name="save" value="Guardar" class="btn btn-primary btn-block btn-large" />
            </form>
        </div>
    </div>
</body>
</html>
<?php
}


