<?php
session_start();
session_name('admin');

session_destroy();
header('Location: login.php');
?>