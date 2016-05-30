<form action='<? echo $_SERVER['PHP_SELF']; ?>' method='POST' name='order_form' id='order_form' enctype='multipart/form-data'>

	<fieldset><legend>Products</legend>
	<?php
	/*Access and create table with stock information from games*/
	$toggle = isset($_GET['toggle'])?$_GET['toggle']:TRUE;
	$order_by = isset($_GET['order_by'])?$_GET['order_by']:'category';
	$asc_desc = ($toggle)?'ASC':'DESC';

	$select_producst = "SELECT games_id, category, description, edition, keyword, price, stock_quantity
		FROM games
		INNER JOIN game_categories USING (game_categories_id)
		INNER JOIN game_editions USING (game_editions_id)
		INNER JOIN game_conditions USING (game_conditions_id)
		ORDER BY $order_by $asc_desc";

	$exec_select_producst = @mysqli_query($link, $select_producst);
	if(!$exec_select_producst){
		rollback('Error! Product info retreival failed due to '.mysqli_error($link));
	}elseif(mysqli_num_rows($exec_select_producst) > 0){
		echo "<table class='product_info_table'>
			<tr class='header'>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=category&toggle=".!$toggle."'>Game</a></th>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=description&toggle=".!$toggle."'>Description</a></th>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=edition&toggle=".!$toggle."'>Edition</a></th>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=keyword&toggle=".!$toggle."'>Condition</a></th>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=price&toggle=".!$toggle."'>Price</a></th>
				<th><a href='".$_SERVER['PHP_SELF']."?order_by=stock_quantity&toggle=".!$toggle."'>Stock Quantity</a></th>
				<th>Quantity</th>
			</tr>";
		while($one_record = mysqli_fetch_assoc($exec_select_producst)){
			$games_id = $one_record['games_id'];
			$price = $one_record['price'];
			$max = $one_record['stock_quantity'];
			echo "<tr>
				<td>{$one_record['category']}</td>
				<td>{$one_record['description']}</td>
				<td>{$one_record['edition']}</td>
				<td>{$one_record['keyword']}</td>
				<td>\${$one_record['price']}</td>
				<td>{$one_record['stock_quantity']}</td>
				<td><input type='number' name='quantity[$games_id][$price]' id='quantity' min='0' max='$max'";
					if(isset($quantity)&&!empty($quantity[$games_id][$price])) echo "value='{$quantity[$games_id][$price]}'";
				echo "></td></tr>";
		}
		echo "</table>";
		mysqli_free_result($exec_select_producst);
	}else{
		echo "No Product to Show";
	}
	?>
	</fieldset>
	
	<fieldset><legend>Payment</legend>
		<?php 
		/*Credit type radio selection*/
		create_checkbox_radio_drop_down('Credit Type: ', 'radio', 'credit_type', ['visa'=>'Visa', 'master'=>'Master', 'discover'=>'Discover'], $errors_array); 
		?>
		<?php 
		/*Credit card number textbox*/
		create_form_field('Credit No: ', 'text', 'credit_no', 'credit_no', ['maxlength'=>'20', 'size'=>'16', 'title'=>'Type in your credit no', 'required'=>'required', 'pattern'=>'[0-9]{16,20}', 'placeholder'=>'XXXXXXXXXXXXXXXX'], $errors_array); 
		?>
	</fieldset>	
	
	<fieldset><legend>Shipping Method</legend>	
		<?php
		/*Shipping carrier drop down menu*/
		$select_carriers_methods = "SELECT game_carriers_methods_id, carrier, method, fee from game_carriers_methods";
		$exec_select_carriers_methods = @mysqli_query($link, $select_carriers_methods);
		if(!$exec_select_carriers_methods){
			exit("Error: ".mysqli_error($link));
			mysqli_close($link);
		}else{
			$multi_array = array();
			while($one_record = mysqli_fetch_assoc($exec_select_carriers_methods)){
				$multi_array[] = $one_record;
			}
			create_drop_down_from_query('Shipping Method: ', 'game_carriers_methods_id', 'game_carriers_methods_id', $multi_array, ['title'=>'Shipping Method'], $errors_array);
		}
		?>
	</fieldset>
	
	<fieldset><legend>Shipping & Billing Address</legend>
	<?php
		/*Access mysql database access*/
		$select_address = "SELECT address_1, address_2, city, game_states_id, zip from game_customers WHERE game_customers_id=$game_customers_id";
		$exec_select_address = @mysqli_query($link, $select_address);
		if(!$exec_select_address){
			rollback('Error: '.mysqli_error($link));
		}else{
			$one_record = mysqli_fetch_assoc($exec_select_address);
			$address_1 = $one_record['address_1'];
			$address_2 = $one_record['address_2'];
			$city = $one_record['city'];
			$game_states_id = $one_record['game_states_id'];
			$zip = $one_record['zip'];
		}
		
		/*Address line 1 text box*/
		create_form_field('Address 1:', 'text', 'address_1', 'address_1', ['maxlength'=>'100', 'size'=>'50', 'tabindex'=>'6', 'title'=>'Home Address', 'required'=>'required', 'pattern'=>'[A-Za-z0-9_\.\#\' \-:=]{2,100}', 'placeholder'=>''], $errors_array);
		/*Address line 2 text box*/
		create_form_field('Address 2:', 'text', 'address_2', 'address_2', ['maxlength'=>'100', 'size'=>'50', 'tabindex'=>'7', 'title'=>'Home Address', 'pattern'=>'[A-Za-z0-9_\.\#\' \-:=]{0,100}', 'placeholder'=>''], $errors_array);
		/*City text box*/
		create_form_field('City:', 'text', 'city', 'city', ['maxlength'=>'50', 'size'=>'20', 'tabindex'=>'8', 'title'=>'City', 'pattern'=>'[A-Za-z]{2,50}', 'placeholder'=>'HomeTown'], $errors_array);
		/*State drop down menu*/
		$select_states = "SELECT game_states_id, state, abbr from game_states";
		$exec_select_states = @mysqli_query($link, $select_states);
		if(!$exec_select_states){
			exit("Error: ".mysqli_error($link));
			mysqli_close($link);
		}else{
			$multi_array = array();
			while($one_record = mysqli_fetch_assoc($exec_select_states)){
				$multi_array[] = $one_record;
			}
			create_drop_down_from_query('State: ', 'game_states_id', 'game_states_id', $multi_array, ['tabindex'=>'9', 'title'=>'State'], $errors_array);
		}
		/*Zip code text box*/	
		create_form_field('Zip:', 'text', 'zip', 'zip', ['maxlength'=>'5', 'size'=>'5', 'tabindex'=>'10', 'title'=>'Zip Code', 'placeholder'=>''], $errors_array);	
	?>
	</fieldset>	
	
	<fieldset>
	<p>
		<label>
			<input type='hidden' value='form_submitted' name='form_submitted' id='form_submitted' />
			<input type='submit' value='Submit' />
			<input type='reset' value='Reset' />
		</label>
	</p>
	</fieldset>

</form>