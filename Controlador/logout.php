<?php
session_start();
session_destroy();
header("Location: ../Vista/Index.php");
exit();
?>
