<?php
session_start();
$title = 'Pending Orders';
require('./includes/mysql.inc.php');
$errors_array = array();
require('./includes/functions.inc.php');
if(isset($_SESSION['game_customers_id']) && isset($_SESSION['full_name'])){
	$game_customers_id = $_SESSION['game_customers_id'];
	if(isset($_GET['game_orders_id'])){
		$game_orders_id = $_GET['game_orders_id'];
		require('./includes/cancel_orders.inc.php');
	}else{
		include('./includes/header.inc.php');
		require('./includes/view_current_orders.inc.php');
	}
	include('./includes/footer.inc.php');
}else{
	redirect('Authentication failed', 'login.php', 1);
}
?>