<?php
/* Handlers for registration input */

if(!empty($_POST['first_name'])&&is_string($_POST['first_name'])){
	$first_name = htmlspecialchars(add_slashes($_POST['first_name']));
}else{
	$errors_array['first_name'] = "First name entry invalid.";
}

if(!empty($_POST['last_name'])&&is_string($_POST['last_name'])){
	$last_name = htmlspecialchars(add_slashes($_POST['last_name']));
}else{
	$errors_array['last_name'] = "Last name entry invalid.";
}

if(!empty($_POST['email'])&&filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	$email = htmlspecialchars(add_slashes($_POST['email']));
}else{
	$errors_array['email'] = "Email entry invalid.";
}

if(!empty($_POST['phone'])){
	$phone = htmlspecialchars(add_slashes($_POST['phone']));
}else{
	$errors_array['phone'] = "Phone number entry invalid.";
}

if(!empty($_POST['password'])){
	$password = htmlspecialchars($_POST['password']);
}else{
	$errors_array['password'] = "Password entry invalid.";
}

if(!empty($_POST['address_1'])){
	$address_1 = htmlspecialchars(add_slashes($_POST['address_1']));
}else{
	$errors_array['address_1'] = "Address entry invalid.";
}

if(!empty($_POST['address_2'])){
	$address_2 = htmlspecialchars(add_slashes($_POST['address_2']));
}else{
	$address_2 = null;
}

if(!empty($_POST['city'])){
	$city = htmlspecialchars(add_slashes($_POST['city']));
}else{
	$errors_array['city'] = "City entry invalid.";
}

if(isset($_POST['game_states_id'])){
	$game_states_id = $_POST['game_states_id'];
}else{
	$errors_array['game_states_id'] = "State entry invalid.";
}

if(!empty($_POST['zip'])){
	$zip = htmlspecialchars(add_slashes($_POST['zip']));
}else{
	$errors_array['zip'] = "Zip code entry invalid.";
}

if(count($errors_array)==0){
	mysqli_query($link, 'AUTOCOMMIT = 0');
	$insert_into_game_customers = "INSERT INTO game_customers (first_name, last_name, email, phone, password, address_1, address_2, city, game_states_id, zip, date_created) VALUES
	('$first_name', '$last_name', '$email', '$phone', '".password_hash($password, PASSWORD_BCRYPT)."', '$address_1', '$address_2', '$city', $game_states_id, '$zip', '$date_created')";
	$exec_insert_into_game_customers = @mysqli_query($link, $insert_into_game_customers);
	if(!$exec_insert_into_game_customers){
		rollback("Error inserting records: ".mysqli_error($link));
	}else{
		mysqli_query($link, 'COMMIT');
		redirect('You are successfully registered. Redirecting to login...', 'login.php', 2);
	}
}




?>