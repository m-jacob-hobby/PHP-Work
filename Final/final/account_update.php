<?php
$title = 'Account Update';
require('./includes/mysql.inc.php');
$errors_array = array();
require('./includes/functions.inc.php');

if(isset($_GET['game_customers_id'])){
	include('./includes/header.inc.php');
	$game_customers_id = $_GET['game_customers_id'];
	require('./includes/account_retrieve.inc.php');
	require('./includes/account_update.inc.php');
}elseif(isset($_POST['game_customers_id'])){
	$game_customers_id = $_POST['game_customers_id'];
	require('./includes/account_update_handle.inc.php');
}else{
	redirect('Unknown Access','account_info.php',2);
}
include('./includes/footer.inc.php');
?>