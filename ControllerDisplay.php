<?php
	/******************************************************************
	ControllerDisplay.php
	This file communicates with View.php and responsible for all the display output
	******************************************************************/

	// function to display Item added from cart
	function displayItemAddToCart ($itemId){
		addToCart ($itemId);		// add item to cart
		$item = getItem($itemId); 	// get item information based on the ItemId
		
		echo sprintf("<p><font color='red'><b>%s</b></font> has been added to Cart.</p>", $item['itemName']);
	}

	// function to display Item removed from cart
	function displayItemRemovedFromCart ($cartId){
		$cartItem = getCart($cartId); 			// get item information based on the CartId
		$item = getItem($cartItem['itemId']); 	// get item information based on the ItemId
		removeCartItem ($cartId);				// remove item from cart

		echo sprintf("<p><font color='red'><b>%s</b></font> has been removed from the Cart.</p>", $item['itemName']);
		displayCart ();							// display what is left in the cart
	}

	// function to display all the Items in the Menu
	function displayMenu ($menuArray){
		displayPageHeader ("MENU");

		// display the number of Items in the Cart
		$cartItems = getAllCart();
		echo sprintf("<p>Cart Items: %s", sizeof($cartItems));

		// View Cart Button
		echo "<form action=view.php method='post'>";
			echo "<input type='hidden' name='action' value='viewCart'><input type='submit' value='View Cart' />";
		echo "</form>";

		echo "<table border=1px;solid cellpadding=5 cellspacing=0;>";
		
		// display the orange row headers and add one column button
		displayTableHeaders($menuArray, 1);

		// for loop to display Menu Items
		foreach ($menuArray as $item):
			// every new row, there is a form to post back the data
			echo "<form action=view.php method='post'>";
				// hidden html tag to keep track of the action - addCart
				echo "<input type='hidden' name='action' value='addCart'>";
				
				// start of a new row <tr></tr>
				echo "<tr>";

					// start of the columns <td></td>
					echo sprintf("<td>%s</td>", $item['itemId']);
					echo sprintf("<td>%s</td>", $item['itemName']);
					echo sprintf("<td>%s</td>", number_format($item['itemPrice'],2));

					// input button to add item into the cart and hidden value to keep track on the itemId added 
					echo sprintf("<td><input type='submit' value='+' /></td>");
					echo sprintf("<input type='hidden' name='itemId' value='%s'>", $item['itemId']);

				echo "</tr>";
			echo "</form>";
		endforeach;

		echo "</table>";		
	}

	// Function to display all the Items in the Cart
	function displayCart (){
		displayPageHeader ("CART");

		// Back to Menu button
		echo "<form action=view.php method='post'>";
			// hidden html tag to keep track of the action - Back to Menu and a [Back to Menu] submit button
			echo "<input type='hidden' name='action' value='Menu'><input type='submit' value='Back to Menu' /><br>";
		echo "</form>";

		// get Cart Items
		$cartItems = getCartItems ();
		// for summing up the cart total
		$totalCartAmount = 0;

		// If there are items in the Cart and count the number of items in the array
		if (sizeof($cartItems) > 0):
			echo "<table border=1px;solid cellpadding=5 cellspacing=0;>";
			
			// display the orange row headers and add one column button
			displayTableHeaders($cartItems, 1);

			// for loop to display Items in the Cart in table form
			foreach ($cartItems as $item):
				echo "<form action=view.php method='post'>";
					
				// hidden html tag to keep track of the action - remove cart
					echo "<input type='hidden' name='action' value='removeCart'>";
					
					// display each cart items in row
					echo "<tr>";

						// display the column data
						echo sprintf("<td>%s</td>", $item['cartId']);
						echo sprintf("<td>%s</td>", $item['itemId']);
						echo sprintf("<td>%s</td>", $item['itemName']);
						echo sprintf("<td>%s</td>", number_format($item['itemPrice'],2));

						// adds up all the cart amount 
						$totalCartAmount += $item['itemPrice']; 

						// input button to remove item from the cart and hidden value to keep track on the CartId 
						echo sprintf("<td><input type='submit' value='-' /></td>");
						echo sprintf("<input type='hidden' name='cartId' value='%s'>", $item['cartId']);

					echo "</tr>";
				echo "</form>";
			endforeach;

			echo "</table><br>";

			// Shows form to post for make payment
			echo "<form action=view.php method='post'>";
				
				// show the cart total message
				echo sprintf("<p>Cart total: <font color='blue'><b>$%s</b></font></p>", number_format($totalCartAmount, 2));

				// ask if user is a member
				echo "Are you a member?&nbsp;";

				// show the differet member options
				echo "<select name='member'>";
					echo "<option value='YES'>YES</option>";
					echo "<option value='NO' selected>NO</option>"; // default NO is selected
				echo "</select>";
				
				echo sprintf("<p><b><font color='green'>Be our member and earn points! A minimum spending $10 is required. Every $10 = 1 point.</font></b></p>");

				// hidden html tag to keep track of the action - Make Payment and a [Make Payment] submit button
				echo "<p><input type='hidden' name='action' value='makePayment'><input type='submit' value='Make Payment' /></p>";

			echo "</form>";
		else:
			// show messsage that Cart is empty
			echo "<p>Cart is empty.</p>";
		endif;
	}

	// function to make payment and display payment information
	function makePayment($member){
		displayPageHeader ("PAYMENT");

		// get Cart Items
		$cartItems = getCartItems ();
		$paymentAmount = 0;

		// Make sure there are items in the Cart
		// normally make payment button will only show when there are items in the cart
		if (sizeof($cartItems) > 0):
			echo "<p>Summary of the Items ordered:</p>";

			echo "<table border=1px;solid cellpadding=5 cellspacing=0;>";
			
			// display the row headers and not adding extra column
			// overidding changing the color of the header
			displayTableHeaders($cartItems, 0, "#90EE90");

			// for loop to display Items in the Cart in table form
			foreach ($cartItems as $item):
				echo "<form action=view.php method='post'>";
					echo "<tr>";

						// start of the columns <td></td>
						echo sprintf("<td>%s</td>", $item['cartId']);
						echo sprintf("<td>%s</td>", $item['itemId']);
						echo sprintf("<td>%s</td>", $item['itemName']);
						echo sprintf("<td>%s</td>", number_format($item['itemPrice'],2));

						// add all the items' price
						$paymentAmount += $item['itemPrice'];

					echo "</tr>";
				echo "</form>";
			endforeach;

			// add Payment history information
			addToPayment ($paymentAmount);
			// remove all items in the cart
			removeAllCartItems();

			echo "</table>";	

			// show success message
			// set payment amount to 2 decimal point
			echo sprintf("<p><font color='blue'><b>$%s</b></font> has been paid.</p>", number_format($paymentAmount, 2));

			// show the respective message depending on whether the user is a member
			if ($member == "YES"):
				$points = 0;
				$appendS = "";

				// check spending more than $10
				if ($paymentAmount >10):
					$points = number_format($paymentAmount/10, 0);

					// if user ends more than 1 point, add the correct grammar and append an "s"
					if ($points > 1):
						$appendS = "s";
					endif;

					echo sprintf("<p>You have earned <font color='green'><b>%s</b></font> point%s!</p>", $points, $appendS);
				else:
					echo "<p>Spend a minimum of $10 to earn points!</p>";
				endif;
			endif;

			echo "<p>Thank you for your business!</p>";

		else:
			// show messsage that Cart is empty
			echo "<p>Cart is empty.</p>";
		endif;

		// Back to Menu button
		echo "<form action=view.php method='post'>";
		// hidden html tag to keep track of the action - Make Payment and a [Make Payment] submit button
		echo "<p><input type='hidden' name='action' value='Menu'><input type='submit' value='Back to Menu' /></p>";
		echo "</form>";
	}

	// display table headers
	// $extraColumn is for adding extra column at the end for buttons
	// $color is for changing the header colors, default is orange header
	function displayTableHeaders($headerArray, $extraColumn, $color = 'orange'){

		// to get the item variable names
		$headerRow = array_keys($headerArray[0]);

		echo sprintf("<tr bgcolor='%s'>", $color);
			foreach ($headerRow as $column):
				echo sprintf("<th>%s</th>", $column);
			endforeach;

			// if extraColumn is more than 0, it will get ($extraColumn) amount of columns
			if ($extraColumn != 0):
				for ($i=0; $i<$extraColumn; $i++):
					echo sprintf("<th></th>", $column);
				endfor;
			endif;
		echo "</tr>";
	}

?>