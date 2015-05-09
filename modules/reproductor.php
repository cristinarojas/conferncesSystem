<?php
    include "lib/db.php";

    $query = "SELECT * FROM predicaciones ORDER BY id DESC";
    $result = $db->query($query);
?>

<?php include "templates/head.php"; ?>

    <div class="container">
        <div class="header-wrapper clearfix">
            <div class="logo">
                <img src="public/img/logo.png" />
            </div>

            <div class="search">
                <i class="fa fa-search"></i>
                <input type="text" class="" placeholder="Buscar predicaciones, por nombre ...">
            </div>
        </div>

        <div class="sermons">
            <span id="currentAudio" style="display: none;"></span>

            <?php
                while ($data = $result->fetch_assoc()) {
                ?>
                    <div class="item">
                        <div class="image-item">
                            <div class="button-player">
                                <a href="#" class="play" data-audio="<?=$data["url_audio"];?>"><i class="fa fa-play"></i></a>
                            </div>
                            <div class="button-facebook">
                                <a href="#" class="facebook"><i class="fa fa-facebook-square"></i></a>
                            </div>
                            <img src="<?= $data["url_imagen"];?>" />
                        </div>
                        <div class="text-item">
                            <a href="#" class="autor"><?= utf8_encode($data["titulo_audio"]);?></a>
                            <a href="#"><?= utf8_encode($data["nombre_autor"]);?></a>
                        </div>
                    </div>
                <?php
                }
            ?>

        </div> <!-- sermons -->

        <?php include "templates/player.php"; ?>
    </div> <!-- container -->

<?php include "templates/footer.php"; ?>
