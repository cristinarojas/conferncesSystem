<?php
    include "lib/db.php";

    $query = "SELECT * FROM predicaciones ORDER BY id DESC";
    $result = $db->query($query);
    if (isset($_POST["search"])) {
        $search = $_POST["search"];
        $query = "SELECT * FROM predicaciones WHERE titulo_audio LIKE '%$search%' ORDER BY id DESC";
        $result = $db->query($query);
    }
?>

<?php include "templates/head.php"; ?>

    <div class="container">
        <div class="header-wrapper clearfix">
            <div class="logo">
                <a href="index.php"><img src="public/img/logo.png" /></a>
            </div>

            <div class="search">
                <form action="index.php" method="post">
                    <a href="javascript:;" onclick="parentNode.submit();"><i class="fa fa-search"></i></a>
                    <input type="text" name="search" class="" placeholder="Buscar predicaciones, por nombre ...">
                </form>
            </div>

            <div class="button-facebook">
                <div class="fb-share-button" data-href="http://grupoamor.org/predicaciones" data-layout="button"></div>
            </div>
        </div>

        <div class="sermons">
            <span id="currentAudio" style="display: none;"></span>

            <?php
                if ($result->num_rows === 0) {
                    echo ('<p>Búsqueda sin resultados, intente de nuevo.</p>');
                    echo ('<p><a href="index.php">Regresar</a></p>');
                }
                while ($data = $result->fetch_assoc()) {
                ?>
                    <div class="item">
                        <div class="image-item">
                            <div class="button-player">
                                <a href="#" class="play" data-audio="<?=$data["url_audio"];?>"><i class="fa fa-play"></i></a>
                            </div>
                            <img src="<?= $data["url_imagen"];?>" />
                        </div>
                        <div class="text-item">
                            <span href="#" class="autor"><?= utf8_encode($data["titulo_audio"]);?></span>
                            <span href="#"><?= utf8_encode($data["nombre_autor"]);?></span>
                        </div>
                    </div>
                <?php
                }
            ?>

        </div> <!-- sermons -->

        <?php include "templates/player.php"; ?>
    </div> <!-- container -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php include "templates/footer.php"; ?>
