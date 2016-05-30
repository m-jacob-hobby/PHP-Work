<?php
/* View past orders Page: print to browser customer's previous order selections */
$toggle = isset($_GET['toggle'])?$_GET['toggle']:TRUE;
$order_by = isset($_GET['order_by'])?$_GET['order_by']:'category';
$asc_desc = ($toggle)?'ASC':'DESC';

$select_previous_orders = "SELECT game_orders_games.game_orders_id, CONCAT_WS(' ',game_shipping_addresses.address_1, game_shipping_addresses.address_2, game_shipping_addresses.city, state, game_shipping_addresses.zip) as 'Shipping Address', CONCAT_WS(' ',game_billing_addresses.address_1, game_billing_addresses.address_2, game_billing_addresses.city, state, game_billing_addresses.zip) as 'Billing Address', GROUP_CONCAT(category SEPARATOR '<br><hr>') as category, GROUP_CONCAT(edition SEPARATOR '<br><hr>') as edition, GROUP_CONCAT(keyword SEPARATOR '<br><hr>') as keyword, GROUP_CONCAT(game_orders_games.quantity SEPARATOR '<br><hr>') as quantity, GROUP_CONCAT(game_orders_games.price SEPARATOR '<br><hr>') as price, credit_no, credit_type, order_total, shipping_fee, order_date, shipping_date
	FROM game_customers
	INNER JOIN game_states USING (game_states_id)
	INNER JOIN game_orders USING (game_customers_id)
	INNER JOIN game_shipping_addresses USING (game_shipping_addresses_id)
	INNER JOIN game_billing_addresses USING (game_billing_addresses_id)
	INNER JOIN game_orders_games USING (game_orders_id)
	INNER JOIN games USING (games_id)
	INNER JOIN game_categories USING (game_categories_id)
	INNER JOIN game_editions USING (game_editions_id)
	INNER JOIN game_conditions USING (game_conditions_id)
	WHERE game_customers_id = $game_customers_id
	GROUP BY game_orders_games.game_orders_id
	ORDER BY $order_by $asc_desc";

$exec_select_previous_orders = @mysqli_query($link, $select_previous_orders);
if(!$exec_select_previous_orders){
	rollback('Error: No orders retreived due to '.mysqli_error($link));
}elseif(mysqli_num_rows($exec_select_previous_orders) > 0){
	echo "<table class='product_info_table'>
		<tr class='header'>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=game_shipping_addresses.address_1&toggle=".!$toggle."'>Shipping Address</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=game_billing_addresses.address_1&toggle=".!$toggle."'>Billing Address</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=category&toggle=".!$toggle."'>Game</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=edition&toggle=".!$toggle."'>Edition</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=keyword&toggle=".!$toggle."'>Condition</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=game_orders_games.quantity&toggle=".!$toggle."'>Quantity</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=game_orders_games.price&toggle=".!$toggle."'>Price</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=credit_no&toggle=".!$toggle."'>Credit No</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=credit_type&toggle=".!$toggle."'>Credit Type</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=order_total&toggle=".!$toggle."'>Order Total</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=shipping_fee&toggle=".!$toggle."'>Shipping Fee</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=order_date&toggle=".!$toggle."'>Order Date</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=shipping_date&toggle=".!$toggle."'>Shipping Date</a></th>
		</tr>";
	while($one_record = mysqli_fetch_assoc($exec_select_previous_orders)){
		echo "<tr>
			<td>{$one_record['Shipping Address']}</td>
			<td>{$one_record['Billing Address']}</td>
			<td>{$one_record['category']}</td>
			<td>{$one_record['edition']}</td>
			<td>{$one_record['keyword']}</td>
			<td>{$one_record['quantity']}</td>
			<td>\${$one_record['price']}</td>
			<td>{$one_record['credit_no']}</td>
			<td>{$one_record['credit_type']}</td>
			<td>\${$one_record['order_total']}</td>
			<td>\${$one_record['shipping_fee']}</td>
			<td>{$one_record['order_date']}</td>
			<td>{$one_record['shipping_date']}</td>
		</tr>";
	}
	echo "<tr><td colspan='12'>Number of Prior Orders:</td><td>".mysqli_num_rows($exec_select_previous_orders)."</td></tr></table>";
	mysqli_free_result($exec_select_previous_orders);
}else{
	echo "There are currently no orders.";
}
?>