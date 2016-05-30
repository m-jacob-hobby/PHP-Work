<?php
// Delete order data from game_database
mysqli_query($link, "SET AUTOCOMMIT = 0");
$select_games = "SELECT games_id, quantity from game_orders_games WHERE game_orders_id = $game_orders_id";
$exec_select_games = @mysqli_query($link, $select_games);
if(!$exec_select_games){
	rollback('Ordered games could not be retrieved because '.mysqli_error($link));
}else{
	while($one_record = mysqli_fetch_assoc($exec_select_games)){
		$quantity = $one_record['quantity'];
		$games_id = $one_record['games_id'];
		$update_games = "UPDATE games set stock_quantity = (stock_quantity+$quantity) WHERE games_id = $games_id";
		$exec_update_games = @mysqli_query($link, $update_games);
		if(!$exec_select_games){
			rollback('Update unsuccessful due to '.mysqli_error($link));
		}
	}
	$delete_order = "DELETE game_shipping_addresses.*, game_billing_addresses.*, game_transactions.* FROM game_orders 
	INNER JOIN game_billing_addresses USING (game_billing_addresses_id)
	INNER JOIN game_shipping_addresses USING (game_shipping_addresses_id)
	INNER JOIN game_transactions USING (game_transactions_id)
	WHERE game_orders_id = $game_orders_id";
	$exec_delete_order = @mysqli_query($link, $delete_order);
	if(!$exec_delete_order){
		rollback('Could not delete due to '.mysqli_error($link));
	}else{
		mysqli_query($link, "COMMIT");
		redirect('Deletion successful...', 'view_current_orders.php', 1);
	}	
}
?>