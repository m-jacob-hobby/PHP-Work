<?php
session_start();
$title = 'Logout';
require('./includes/mysql.inc.php');
$errors_array = array();
require('./includes/functions.inc.php');

if(isset($_SESSION['game_customers_id']) && isset($_SESSION['full_name'])){
	unset($_SESSION['game_customers_id']);
	unset($_SESSION['full_name']);
	$_SESSION = array();
	session_destroy();
	setcookie('PHPSESSID', '', time()-5, '/', '', 0, 0);
	redirect('Logout successful. Goodbye.', 'login.php', 1);
}else{
	redirect('User is already logged out.', 'login.php', 1);
}
?>