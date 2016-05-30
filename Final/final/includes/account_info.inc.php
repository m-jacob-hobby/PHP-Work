<?php
/* View/Modify Information Page: allow user to view and modify their account data they created in the registration page */
$select_account_info = "SELECT game_customers_id, CONCAT(first_name,' ',last_name) as 'Full Name', email, phone, CONCAT_WS(' ',address_1, address_2, city, zip) as 'address', date_created
	FROM game_customers 
	INNER JOIN game_states USING(game_states_id)
	WHERE game_customers_id = $game_customers_id";

$exec_select_account_info = @mysqli_query($link, $select_account_info);
if(!$exec_select_account_info){
	rollback('Error: Account retrieval failed due to '.mysqli_error($link));
}elseif(mysqli_num_rows($exec_select_account_info) > 0){
	echo "<table class='account_info_table'>
		<tr class='header'>
			<th>Full Name</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Address</th>
		</tr>";
	while($one_record = mysqli_fetch_assoc($exec_select_account_info)){
		echo "<tr>
			<td><a href='account_update.php?game_customers_id=".$one_record['game_customers_id']."'>{$one_record['Full Name']}</a></td>
			<td>{$one_record['email']}</td>
			<td>{$one_record['phone']}</td>
			<td>{$one_record['address']}</td>
		</tr>";
	}
	echo "<tr><td colspan='4'>Number of Customers:</td><td>".mysqli_num_rows($exec_select_account_info)."</td></tr></table>";
	mysqli_free_result($exec_select_account_info);
}else{
	echo "There are currently no customers";
}

	
?>