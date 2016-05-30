<?php

/*Handle Shipping & Billing*/
if(!empty($_POST['address_1'])){
	$address_1 = htmlspecialchars(add_slashes($_POST['address_1']));
}else{
	$errors_array['address_1'] = "Invalid address entered";
}

if(!empty($_POST['address_2'])){
	$address_2 = htmlspecialchars(add_slashes($_POST['address_2']));
}else{
	$address_2 = null;
}

if(!empty($_POST['city'])){
	$city = htmlspecialchars(add_slashes($_POST['city']));
}else{
	$errors_array['city'] = "Invalid city entered";
}

if(isset($_POST['game_states_id'])){
	$game_states_id = $_POST['game_states_id'];
}else{
	$errors_array['game_states_id'] = "Invalid state selected";
}

if(!empty($_POST['zip'])){
	$zip = htmlspecialchars(add_slashes($_POST['zip']));
}else{
	$errors_array['zip'] = "Invalid zip entered";
}

/*Handle payment method*/
if(isset($_POST['credit_type'])){
	$credit_type = $_POST['credit_type'];
}else{
	$errors_array['credit_type'] = "Invalid credit type entered";
}

if(!empty($_POST['credit_no'])&&is_numeric(trim($_POST['credit_no']))){
	$credit_no = trim($_POST['credit_no']);
	$credit_no_four = substr($credit_no, -4);
}else{
	$errors_array['credit_no'] = "Invalid credit number entered";
}

/*Handle shipping method*/

if(isset($_POST['game_carriers_methods_id'])){
	$game_carriers_methods_id = $_POST['game_carriers_methods_id'];
}else{
	$errors_array['game_carriers_methods_id'] = 'Invalid shipping method selected';
}
$select_shipping_fee = "SELECT fee from game_carriers_methods WHERE game_carriers_methods_id = $game_carriers_methods_id";
$exec_select_shipping_fee = @mysqli_query($link, $select_shipping_fee);
if(!$exec_select_shipping_fee){
	rollback("Error: Shipping method error due to: ".mysqli_error($link));
}else{
	$one_record = mysqli_fetch_assoc($exec_select_shipping_fee);
	$fee = $one_record['fee'];
}

/*Handle quantity selection*/

if(!empty($_POST['quantity'])&&is_array($_POST['quantity'])){
	$quantity = $_POST['quantity'];
	
	foreach($quantity as $games_id=>$arr){
		foreach($arr as $price => $value){
			$order_total += ($price * $value);
			$shipping_fee += ($fee * $value);
		}
		$amount_charged = $order_total + $shipping_fee;
	}
	if(!is_numeric($amount_charged) || $amount_charged == 0){
		$errors_array['quantity'] = "Invalid quantity";
	}
}else{
	$errors_array['quantity'] = "Need quantity entry";
}

if(count($errors_array)==0){
	mysqli_query($link, 'AUTOCOMMIT = 0');
	$insert_shipping_addresses = "INSERT INTO game_shipping_addresses (address_1, address_2, city, game_states_id, zip, date_created) 
		VALUES ('$address_1', '$address_2', '$city', $game_states_id, '$zip', now())";
	$exec_insert_shipping_addresses = @mysqli_query($link, $insert_shipping_addresses);
	if(!$exec_insert_shipping_addresses){
		rollback("The following error occurred when inserting into game_shipping_addresses: ".mysqli_error($link));
	}else{
		$game_shipping_addresses_id = mysqli_insert_id($link);
		$insert_billing_addresses = "INSERT INTO game_billing_addresses (address_1, address_2, city, game_states_id, zip, date_created) 
		VALUES ('$address_1', '$address_2', '$city', $game_states_id, '$zip', now())";
		$exec_insert_billing_addresses = @mysqli_query($link, $insert_billing_addresses);
		if(!$exec_insert_billing_addresses){
			rollback("The following error occurred when inserting into game_billing_addresses: ".mysqli_error($link));
		}else{
			$game_billing_addresses_id = mysqli_insert_id($link);
			$insert_transactions = "INSERT into game_transactions (amount_charged, type, response_code, response_reason, response_text, date_created) VALUES ($amount_charged, 'credit', 'OK', '', 'Confirmed', now())";
			$exec_insert_transactions = @mysqli_query($link, $insert_transactions);
			if(!$exec_insert_transactions){
				rollback("The following error occurred when inserting into game_transactions: ".mysqli_error($link));
			}else{
				$game_transactions_id = mysqli_insert_id($link);
				$insert_orders = "INSERT into game_orders (game_customers_id, game_transactions_id, game_shipping_addresses_id, game_carriers_methods_id, game_billing_addresses_id, credit_no, credit_type, order_total, shipping_fee, order_date) VALUES($game_customers_id, $game_transactions_id, $game_shipping_addresses_id, $game_carriers_methods_id, $game_billing_addresses_id, '$credit_no_four', '$credit_type', $order_total, $shipping_fee, now())";
				$exec_insert_orders = @mysqli_query($link, $insert_orders);
				if(!$exec_insert_orders){
					rollback("The following error occurred when inserting into game_orders: ".mysqli_error($link));
				}else{
					$game_orders_id = mysqli_insert_id($link);
					foreach($quantity as $games_id=>$arr){
						foreach($arr as $price => $value){
							if(!empty($value)){
								$type_total = $price * $value;
								$insert_orders_games = "INSERT into game_orders_games (game_orders_id, games_id, quantity, price) VALUES ($game_orders_id, $games_id, $value, $type_total)";
								$exec_insert_orders_games = @mysqli_query($link, $insert_orders_games);
								if(!$exec_insert_orders_games){
									rollback('The following error ocurred when inserting into game orders'.mysqli_error($link));
								}else{
									$select_stock_quantity = "SELECT stock_quantity from games where games_id = $games_id";
									$exec_select_stock_quantity = @mysqli_query($link, $select_stock_quantity);
									if(!$exec_select_stock_quantity){
										rollback('The following error ocurred when selecting stock quantity'.mysqli_error($link));
									}else{
										$one_record = mysqli_fetch_assoc($exec_select_stock_quantity);
										$stock_quantity = $one_record['stock_quantity'];
										$updated_quantity = $stock_quantity - $value;
										$update_games = "UPDATE games SET stock_quantity = $updated_quantity WHERE games_id = $games_id";
										$exec_update_games = @mysqli_query($link, $update_games);
										if(!$exec_update_games){
											rollback('The following error ocurred when updating stock quantity'.mysqli_error($link));
										}
									}
								}
							}
						}
					}
					mysqli_query($link, 'COMMIT');
					redirect('Orders were placed...', 'view_previous_orders.php', 1);
				}
			}
		}
	}
}




?>