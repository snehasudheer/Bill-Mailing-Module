<?php 
session_start();
unset ($_SESSION['cno']);
unset ($_SESSION['cname']);
session_destroy();
header('location:index.php');

?>