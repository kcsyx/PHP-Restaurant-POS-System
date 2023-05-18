<?php
/******************************************************************
   ControllerDisplay.php
   This file communicates with View.php and responsible for all the display output
   ******************************************************************/
function displayPaymentReceipt($branchId)
{
    $payment = getLatestPayment();
    $cartItems = getLatestBill()['cartIds'];
    $cartItem = explode(",", $cartItems);

    echo sprintf("Transaction ID: %s", $payment['paymentId']);
    echo "<br>";
    echo sprintf("Payment Method: %s", $payment['paymentMethod']);
    echo "<br>";
    echo sprintf("Amount Paid: %s", $payment['totalAmount']);
    echo "<br>";
    echo sprintf("Transaction Date: %s", $payment['paymentDateTime']);
    echo "<hr>";
    foreach ($cartItem as $item):
        $menuItem = getMenuItemFromCart($item);
        echo $menuItem['menuItemName'];
        echo "<br>";
    endforeach;

    // Go Back Button
    echo "<form action=view.php method='post'>";
    echo "<input type='hidden' name='action' value='goBack'><input type='submit' value='Go Back' />";
    echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branchId);
    echo "</form>";
}
function displayCart($branchId)
{
    // Go Back Button
    echo "<form action=view.php method='post'>";
    echo "<input type='hidden' name='action' value='goBackFromCart'><input type='submit' value='Go Back' />";
    echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branchId);
    echo "</form>";

    $sum = 0;
    $items = getCartItems();
    $gstTax = getPriceConstants(1)['priceModifier'];
    $itemIds = array();
    echo "<b>Your Cart</b>";
    echo "<hr>";
    if (!empty($items)) {
        foreach ($items as $item):
            echo "<div>";
            echo $item['itemName'] . ' ---------- ' . $item['itemPrice'];
            $sum += $item['itemPrice'];
            array_push($itemIds, $item['menuItemId']);
            echo "<form action=view.php method='post'>";
            echo "<input type='hidden' name='action' value='removeItemFromCart'>";
            // input button to add item into the cart and hidden value to keep track on the itemId added 
            echo sprintf("<input type='submit' value='-' />");
            echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branchId);
            echo sprintf("<input type='hidden' name='cartId' value='%s'>", $item['cartId']);
            echo "</form>";
        endforeach;
        $billItemIds = implode(",", $itemIds);
    } else {
        echo "<div><p>Your cart is empty</p></div>";
    }
    $gstTaxValue = $sum * $gstTax;
    $totalSum = $sum + $gstTaxValue;
    echo "<hr>";
    echo sprintf("<b>Price: <u>%s</u></b>", number_format((float) $sum, 2, '.', ''));
    echo "<br>";
    echo sprintf("<b>GST: <u>%s</u></b>", number_format((float) $gstTaxValue, 2, '.', ''));
    echo "<br>";
    echo sprintf("<b>Total Price: <u>%s</u></b>", number_format((float) $totalSum, 2, '.', ''));

    echo "<br><br>";

    if (!empty($items)) {
        // Remove All Items from Cart Button
        echo "<form action=view.php method='post'>";
        echo "<input type='hidden' name='action' value='removeAllItemsFromCart'><input type='submit' value='Remove all items' />";
        echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branchId);
        echo "</form>";

        // Pay Button
        echo "<form action=view.php method='post'>";
        echo "<input type='hidden' name='action' value='payCart'><input type='submit' value='Proceed to Payment' />";
        echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branchId);
        echo sprintf("<input type='hidden' name='sum' value='%s'>", $totalSum);
        echo sprintf("<input type='hidden' name='billItemIds' value='%s'>", $billItemIds);

        echo "</form>";
    }
}

function displayPay($branchId, $sum, $billItemIds)
{
    echo sprintf("<b>Amount Payable: <u>%s</u></b>", number_format((float) $sum, 2, '.', ''));
    echo "<form action=view.php method='post'>";
    echo "<label for='payment'>Choose a Payment Method:</label>
    <select id='payment' name='payment' size='2'>
      <option value='VISA/MASTERCARD'>VISA/MASTERCARD</option>
      <option value='AMEX'>AMEX</option>
    </select><br><br>";
    echo "<input type='hidden' name='action' value='submitPayment'><input type='submit' value='Pay' />";
    echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branchId);
    echo sprintf("<input type='hidden' name='sum' value='%s'>", $sum);
    echo sprintf("<input type='hidden' name='billItemIds' value='%s'>", $billItemIds);
    echo "</form>";
}

function displayMenu($branchId)
{

    // View Cart Button
    echo "<form action=view.php method='post'>";
    echo "<input type='hidden' name='action' value='viewCart'><input type='submit' value='View Cart' />";
    echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branchId);
    echo "</form>";

    // Go Back Button
    echo "<form action=view.php method='post'>";
    echo "<input type='hidden' name='action' value='goBack'><input type='submit' value='Go Back' />";
    echo "</form>";

    $menuId = (getMenu($branchId))['menuId'];
    $menuCategories = getMenuCategories($menuId);

    foreach ($menuCategories as $menuCategory):
        echo "<div>";
        echo sprintf("<b>%s</b>", $menuCategory['menuCategoryName']);
        echo "</div>";
        echo "<hr>";
        $menuItems = getMenuItem($menuCategory['menuCategoryId']);
        foreach ($menuItems as $menuItem):
            echo "<div>";
            echo $menuItem['menuItemName'] . ' ---------- ' . $menuItem['price'];
            echo "<br>";
            echo sprintf("<i>%s</i>", $menuItem['menuItemDescription']);

            echo "<form action=view.php method='post'>";
            echo "<input type='hidden' name='action' value='addToCart'>";
            // input button to add item into the cart and hidden value to keep track on the itemId added 
            echo sprintf("<input type='submit' value='+' />");
            echo sprintf("<input type='hidden' name='menuItemId' value='%s'>", $menuItem['menuItemId']);
            echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branchId);
            echo "</form>";

            echo "<br><br>";
        endforeach;
    endforeach;
}

function displayBranches($branches)
{
    displayPageHeader("Branches");

    echo "<table border=1px;solid cellpadding=5 cellspacing=0;>";

    displayTableHeaders($branches, 1);

    foreach ($branches as $branch):
        echo "<form action=view.php method='post'>";
        echo "<input type='hidden' name='action' value='selectBranch'>";

        echo "<tr>";
        echo sprintf("<td>%s</td>", $branch['branchId']);
        echo sprintf("<td>%s</td>", $branch['branchName']);
        echo sprintf("<td>%s</td>", $branch['branchAddress']);
        echo sprintf("<td>%s</td>", $branch['numberOfTables']);

        echo sprintf("<td><input type='submit' value='View Branch' /></td>");
        echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branch['branchId']);

        echo "</tr>";
        echo "</form>";
    endforeach;

    echo "</table>";
}

// display table headers
// $extraColumn is for adding extra column at the end for buttons
// $color is for changing the header colors, default is orange header
function displayTableHeaders($headerArray, $extraColumn, $color = 'orange')
{

    // to get the item variable names
    $headerRow = array_keys($headerArray[0]);

    echo sprintf("<tr bgcolor='%s'>", $color);
    foreach ($headerRow as $column):
        echo sprintf("<th>%s</th>", $column);
    endforeach;

    // if extraColumn is more than 0, it will get ($extraColumn) amount of columns
    if ($extraColumn != 0):
        for ($i = 0; $i < $extraColumn; $i++):
            echo sprintf("<th></th>", $column);
        endfor;
    endif;
    echo "</tr>";
}

?>