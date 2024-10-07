<?php
session_start();
session_destroy();
header("Location: ../views/homepage.php");
//head to -> index.php, sends HTTP header to the browser
?>