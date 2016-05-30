<?php
DEFINE('HOST', 'localhost');
DEFINE('USER', 'mjhobby');
DEFINE('PASS', 'zse45rdxXDR%$ESZ');
DEFINE('DB', 'game_database');

$link = @mysqli_connect(HOST, USER, PASS, DB) or die('An error occurred: '.mysqli_connect_error());
mysqli_set_charset($link, 'utf8');
?>