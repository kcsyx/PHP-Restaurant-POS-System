<html>
<body>
<?php
	/******************************************************************
	View.php
	This file is where the user interacts only and checks all the POST actions
	PHP can be combined with HTML codes.
	******************************************************************/

	include("Common.php");
	$menuItems = getAllItems(); // retrieval of all the menu items
	//printArray($menuItems);

	if (!empty($_POST)):
		// debug message to check the post actions
		printArray($_POST);

		// based on the action, display the right information
		switch ($_POST['action']):
			case "addCart":
				// get the item being added 
				$itemId = $_POST['itemId'];
				displayItemAddToCart ($itemId);
				displayMenu ($menuItems);
			break;

			case "viewCart":
				displayCart ();
			break;

			case "removeCart":
				// get the cartId being removed
				$cartId = $_POST['cartId'];
				displayItemRemovedFromCart ($cartId);
			break;

			case "makePayment":
				// check if the user is a member
				$member = $_POST['member'];
				makePayment ($member);

			break;

			// by default it will always display menu
			default:
				displayMenu ($menuItems);

		endswitch;
	else:
		// if there are no action posted, it will always display menu
		displayMenu ($menuItems);
	endif;
?>

</body>
</html>