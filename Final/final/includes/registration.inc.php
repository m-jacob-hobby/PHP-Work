<form action='<? echo $_SERVER['PHP_SELF']; ?>' method='POST' name='registration_form' id='registration_form' enctype='multipart/form-data'>
	<fieldset><legend>Registration</legend>
		<?php
			/* First name text field */
			create_form_field('First Name:', 'text', 'first_name', 'first_name', ['maxlength'=>'30', 'size'=>'20', 'tabindex'=>'1', 'title'=>'Type in Your First Name Here', 'required'=>'required', 'pattern'=>'[A-Za-z]{2,20}', 'placeholder'=>'First Name'], $errors_array);
			/* Last name text field */
			create_form_field('Last Name:', 'text', 'last_name', 'last_name', ['maxlength'=>'30', 'size'=>'20', 'tabindex'=>'2', 'title'=>'Type in Your Last Name Here', 'required'=>'required', 'pattern'=>'[A-Za-z]{2,20}', 'placeholder'=>'Last Name'], $errors_array);
			/* Email text field */
			create_form_field('Email:', 'email', 'email', 'email', ['maxlength'=>'40', 'size'=>'20', 'tabindex'=>'3', 'title'=>'Type in Your email Here', 'required'=>'required', 'placeholder'=>'email@you.com'], $errors_array);
			/* Phone number text field */
			create_form_field('Phone:', 'tel', 'phone', 'phone', ['maxlength'=>'20', 'size'=>'20', 'tabindex'=>'4', 'title'=>'Type in Your Phone Number', 'placeholder'=>'(XXX)-XXX-XXXX'], $errors_array);
			/* Password text field */
			create_form_field('Password:', 'password', 'password', 'password', ['maxlength'=>'15', 'size'=>'10', 'tabindex'=>'5', 'title'=>'Type in Your Password', 'required'=>'required', 'placeholder'=>'xxxxxxxx'], $errors_array);
			/* Address line 1 text field */
			create_form_field('Address 1:', 'text', 'address_1', 'address_1', ['maxlength'=>'100', 'size'=>'50', 'tabindex'=>'6', 'title'=>'Home Address', 'required'=>'required', 'pattern'=>'[A-Za-z0-9_\.\#\' \-:=]{2,100}', 'placeholder'=>'100 Market Street'], $errors_array);
			/* Address line 2 text field */
			create_form_field('Address 2:', 'text', 'address_2', 'address_2', ['maxlength'=>'100', 'size'=>'50', 'tabindex'=>'7', 'title'=>'Home Address', 'pattern'=>'[A-Za-z0-9_\.\#\' \-:=]{0,100}', 'placeholder'=>'Suite #9'], $errors_array);
			/* City text field */
			create_form_field('City:', 'text', 'city', 'city', ['maxlength'=>'50', 'size'=>'20', 'tabindex'=>'8', 'title'=>'City', 'pattern'=>'[A-Za-z]{2,50}', 'placeholder'=>'HomeTown'], $errors_array);
			/* Drop down menu for state selection */
			$select_states = "SELECT game_states_id, state, abbr from game_states";
			$exec_select_states = @mysqli_query($link, $select_states);
			if(!$exec_select_states){
				exit("The following error occurred: ".mysqli_error($link));
				mysqli_close($link);
			}else{
				$multi_array = array();
				while($one_record = mysqli_fetch_assoc($exec_select_states)){
					$multi_array[] = $one_record;
				}
				create_drop_down_from_query('State: ', 'game_states_id', 'game_states_id', $multi_array, ['tabindex'=>'9', 'title'=>'State'], $errors_array);
			}
			/* Zip Code text field */
			create_form_field('Zip:', 'text', 'zip', 'zip', ['maxlength'=>'5', 'size'=>'5', 'tabindex'=>'10', 'title'=>'Zip Code', 'placeholder'=>'44555'], $errors_array);
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