<?php
session_start();
session_destroy();
header('Location: ../Front/index.php');
exit;
?>