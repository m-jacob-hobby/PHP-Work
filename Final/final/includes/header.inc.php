<!doctype html>
<html>
<head>
	<title><?php if(isset($title)){ echo $title; }else{ echo 'Midwest Board Games'; } ?></title>
	<meta charset='utf-8'>
	<style>
		.product_info_table, .account_info_table {
			border-collapse: collapse;
			border: 3px yellow;
		}
		.product_info_table .header, .product_info_table td, .account_info_table .header, .account_info_table td{
			margin: 0;
			padding: 15px;
			border-spacing: 5px;
		}
		.product_info_table .header, .account_info_table .header{
			border: 4px black;
			background: white;
			color: black;
		}
		.product_info_table td, .account_info_table td{
			border: 1px black;
			background: yellow;
			color: black;			
		}
		
		.mainSection{
			width: 85%;
			margin: 0 auto;
			background: orange;
			padding: 10px;
		}
		
		nav.topNavBar{
			width: 85%;
			margin: 0 auto;
			margin-bottom: 1em;
			padding: 10px 0px;
		}

		nav.topNavBar ul {
			list-style: none;
		}

		nav.topNavBar:after {
			content: "";
			display: block;
			clear: both;
		}
		
		nav.topNavBar>ul>li {
			float: left;
			position: relative;
		}

		nav.topNavBar ul li a{
			background: orange;
			color: white;
			text-decoration: none;
			padding: 5px 10px;
			white-space: nowrap;
		}

		nav.topNavBar ul li:hover>a{
			color: orange;
			background: white;
		}	
	</style>
</head>
<body>
<section class='mainSection'>
	<nav class='topNavBar'>
		<ul class='left_main_ul'>
			<li>
				<?php
				echo "+|~Midwest Board Games~|+<br>";
				if(isset($_SESSION['game_customers_id']) && isset($_SESSION['full_name'])){
					echo "Welcome back, {$_SESSION['full_name']} <a href='logout.php'>Logout</a><br>";
				}else{
					echo "<a href='login.php'>Login</a>";
				}
				?>
			</li>
			<li><a href='registration.php'>Register Account</a></li>
			<li><a href='account_info.php'>View/Edit Account</a></li>
			<li><a href='view_products.php'>Check Out Our Products!</a></li>
			<li><a href='order.php'>Place an Order!</a></li>
			<li><a href='view_current_orders.php'>View Pending Orders</a></li>
			<li><a href='view_previous_orders.php'>View Past Orders</a></li>
		</ul>
	</nav>