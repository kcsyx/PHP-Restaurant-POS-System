<?php
	/******************************************************************
	ControllerFunction.php
	This file communicates with ControllerDisplay.php and responsible for all the database connections
	and formatting the data correctly for display.
	******************************************************************/

	// return Cart Items and append Item information to it
	function getCartItems (){

		// get all the cart items
		$cartItems = getAllCart();

		$result = array();
		foreach ($cartItems as $cartItem):
			// get the item information based on the itemId
			$item = getItem($cartItem["itemId"]);

			// add itemName and itemPrice to the cartItem array
			$cartItem['itemName'] = $item['itemName'];
			$cartItem['itemPrice'] = $item['itemPrice'];
			$result[] = $cartItem;
		endforeach;

		return $result;
	}

	/******************************************************************
	All the INSERT SQL connections to the Database
	******************************************************************/

	// add item to Cart
	function addToCart ($itemId){
		global $DB;
		$insertSQL = sprintf("INSERT INTO Cart (`itemId`) VALUES ('%s')", $itemId);
		$DB->update_query($insertSQL);
	}

	// add payment item to Cart
	function addToPayment ($paymentAmount){
		global $DB;
		$insertSQL = sprintf("INSERT INTO Payment (`paymentAmount`) VALUES ('%s')", $paymentAmount);
		$DB->update_query($insertSQL);
	}

	/******************************************************************
	All the SELECT SQL connections to the Database
	******************************************************************/

	// return a single Item
	function getItem($itemId){
		global $DB;
		$selectSQL = sprintf("SELECT * FROM Items WHERE itemId = %s", $itemId);
		$item = $DB->select_query($selectSQL, 1);
		return $item;
	}

	// return single cart item
	function getCart($cartId){
		global $DB;
		$selectSQL = sprintf("SELECT * FROM Cart WHERE cartId = %s", $cartId);
		$cartItem = $DB->select_query($selectSQL, 1);
		return $cartItem;
	}

	// return all Items
	function getAllItems(){
		global $DB;
		$items = $DB->select_query("SELECT * FROM Items");
		return $items;
	}

	// return all Cart Items
	function getAllCart(){
		global $DB;
		$cartItems = $DB->select_query("SELECT * FROM Cart");
		return $cartItems;
	}

	/******************************************************************
	All the DELETE SQL connections to the Database
	In reality, no one uses DELETE. This is strictly for demo purposes.
	******************************************************************/

	// remove an item from Cart
	function removeCartItem($cartId){
		global $DB;
		$deleteSQL = sprintf("DELETE FROM Cart WHERE cartId = %s", $cartId);
		$DB->update_query($deleteSQL);
	}

	// remove ALL items from Cart
	function removeAllCartItems(){
		global $DB;
		$deleteSQL = sprintf("DELETE FROM Cart");
		$DB->update_query($deleteSQL);
	}
	
?>