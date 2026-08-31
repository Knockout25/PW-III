<?php
session_start();
$_SESSION = [];
session_destroy();
header('Location: /CRUD_Mundo/login.php');
exit;
