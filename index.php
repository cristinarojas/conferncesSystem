<?php
    include "lib/config.php";

    if (isset($_GET["page"])) {
        $module = $_GET["page"];

        if (file_exists("modules/$module.php")) {
            include "modules/$module.php";
        } else {
            include "modules/reproductor.php";
        }
    } else {
        include "modules/reproductor.php";
    }
