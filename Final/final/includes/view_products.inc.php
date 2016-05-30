<?php
/* Check out our products page: Print to browser current products available from database */
$toggle = isset($_GET['toggle'])?$_GET['toggle']:TRUE;
$order_by = isset($_GET['order_by'])?$_GET['order_by']:'category';
$asc_desc = ($toggle)?'ASC':'DESC';

$select_producst = "SELECT category, description, edition, keyword, price, stock_quantity, date_added, photo
	FROM games
	INNER JOIN game_categories USING (game_categories_id)
	INNER JOIN game_editions USING (game_editions_id)
	INNER JOIN game_conditions USING (game_conditions_id)
	ORDER BY $order_by $asc_desc";

$exec_select_producst = @mysqli_query($link, $select_producst);
if(!$exec_select_producst){
	rollback('Product info could not be retrieved becase '.mysqli_error($link));
}elseif(mysqli_num_rows($exec_select_producst) > 0){
	echo "<table class='product_info_table'>
		<tr class='header'>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=category&toggle=".!$toggle."'>Game</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=description&toggle=".!$toggle."'>Description</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=edition&toggle=".!$toggle."'>Edition</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=keyword&toggle=".!$toggle."'>Condition</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=price&toggle=".!$toggle."'>Price</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=stock_quantity&toggle=".!$toggle."'>Stock Quantity</a></th>
			<th><a href='".$_SERVER['PHP_SELF']."?order_by=date_added&toggle=".!$toggle."'>Date Added</a></th>
		</tr>";
	while($one_record = mysqli_fetch_assoc($exec_select_producst)){
		echo "<tr>
			<td>{$one_record['category']}</td>
			<td>{$one_record['description']}</td>
			<td>{$one_record['edition']}</td>
			<td>{$one_record['keyword']}</td>
			<td>\${$one_record['price']}</td>
			<td>{$one_record['stock_quantity']}</td>
			<td>{$one_record['date_added']}</td>
		</tr>";
	}
	echo "<tr><td colspan='7'>Number of Products:</td><td>".mysqli_num_rows($exec_select_producst)."</td></tr></table>";
	mysqli_free_result($exec_select_producst);
}else{
	echo "No Products to Show";
}
?>