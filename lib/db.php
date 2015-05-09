<?php
include "config.php";

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($db->connect_errno > 0) {
    die($db->connect_error);
}
