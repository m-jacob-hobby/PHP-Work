<?php
// Handle first name field
if(!empty($_POST['first_name'])&&is_string($_POST['first_name'])){
	$first_name = htmlspecialchars(add_slashes($_POST['first_name']));
}else{
	$errors_array['first_name'] = "First name entry invalid.";
}
// Handle last name field
if(!empty($_POST['last_name'])&&is_string($_POST['last_name'])){
	$last_name = htmlspecialchars(add_slashes($_POST['last_name']));
}else{
	$errors_array['last_name'] = "Last name entry invalid.";
}
// Handle email field
if(!empty($_POST['email'])&&filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	$email = htmlspecialchars(add_slashes($_POST['email']));
}else{
	$errors_array['email'] = "Email entry invalid.";
}
// Handle phone number field
if(!empty($_POST['phone'])){
	$phone = htmlspecialchars(add_slashes($_POST['phone']));
}else{
	$errors_array['phone'] = "Phone number entry invalid.";
}
// Handle address line 1
if(!empty($_POST['address_1'])){
	$address_1 = htmlspecialchars(add_slashes($_POST['address_1']));
}else{
	$errors_array['address_1'] = "Address entry invalid.";
}
// Handle address line 2
if(!empty($_POST['address_2'])){
	$address_2 = htmlspecialchars(add_slashes($_POST['address_2']));
}else{
	$address_2 = null;
}
// Handle city field
if(!empty($_POST['city'])){
	$city = htmlspecialchars(add_slashes($_POST['city']));
}else{
	$errors_array['city'] = "City entry invalid.";
}
// Handle state field
if(isset($_POST['game_states_id'])){
	$game_states_id = $_POST['game_states_id'];
}else{
	$errors_array['game_states_id'] = "State entry invalid.";
}
// Handle zip code field
if(!empty($_POST['zip'])){
	$zip = htmlspecialchars(add_slashes($_POST['zip']));
}else{
	$errors_array['zip'] = "Zip code entry invalid.";
}

// Update game_database game_customers table
if(count($errors_array)==0){
	mysqli_query($link, 'AUTOCOMMIT = 0');
	$update_game_customers = "UPDATE game_customers 
		SET 
		first_name = '$first_name',
		last_name = '$last_name',
		email = '$email',
		phone = '$phone',
		address_1 = '$address_1',
		address_2 = '$address_2',
		city = '$city',
		game_states_id = '$game_states_id',
		zip = '$zip',
		date_created = '$date_created'
		WHERE game_customers_id = $game_customers_id";
	$exec_update_game_customers = @mysqli_query($link, $update_game_customers);
	if(mysqli_affected_rows($link)==0){
		mysqli_query('COMMIT');
		redirect('Account update failed...', 'account_info.php', 2);
	}elseif(mysqli_affected_rows($link)==1){
		mysqli_query($link, 'COMMIT');
		redirect('Account has been updated...', 'account_info.php', 2);
	}else{
		rollback('An error occured: '.mysqli_error($link));
	}
}else{
	include('./includes/header.inc.php');
	require('./includes/account_update.inc.php');
}

?>