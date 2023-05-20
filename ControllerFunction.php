<?php
/******************************************************************
   ControllerFunction.php
   This file communicates with ControllerDisplay.php and responsible for all the database connections
   and formatting the data correctly for display.
   ******************************************************************/

function getCartItems()
{

    // get all the cart items
    $cartItems = getAllCart();

    $result = array();
    foreach ($cartItems as $cartItem):
        // get the item information based on the itemId
        $menuItemId = $cartItem["menuItemId"];
        $menuItem = getMenuItemFromCart($menuItemId);

        // add itemName and itemPrice to the cartItem array
        $cartItem['itemName'] = $menuItem['menuItemName'];
        $cartItem['itemPrice'] = $menuItem['price'];
        $result[] = $cartItem;
    endforeach;

    return $result;
}

/******************************************************************
   All the SELECT SQL connections to the Database
   ******************************************************************/

function getMenuItem($menuCategoryId)
{
    global $DB;
    $selectSQL = sprintf("SELECT * FROM MenuItem WHERE menuCategoryId = %s", $menuCategoryId);
    $menuItem = $DB->select_query($selectSQL);
    return $menuItem;
}

// return all menu categories for that branch
function getMenuCategories($menuId)
{
    global $DB;
    $selectSQL = sprintf("SELECT * FROM MenuCategory WHERE menuId = %s", $menuId);
    $menuCategory = $DB->select_query($selectSQL);
    return $menuCategory;
}

// return branch menu
function getMenu($branchId)
{
    global $DB;
    $selectSQL = sprintf("SELECT * FROM Menu WHERE branchId = %s", $branchId);
    $menu = $DB->select_query($selectSQL, 1);

    //this will return a row as an array, $menu['colName'] to get the column values
    return $menu;
}

// return all Branches
function getAllBranches()
{
    global $DB;
    $branches = $DB->select_query("SELECT * FROM Branch");
    return $branches;
}

function getAllCart()
{
    global $DB;
    $cartItems = $DB->select_query("SELECT * FROM Cart");
    return $cartItems;
}

function getAllPayments()
{
    global $DB;
    $payments = $DB->select_query("SELECT * FROM Payment");
    return $payments;
}

function getAllBills()
{
    global $DB;
    $bills = $DB->select_query("SELECT * FROM Bill");
    return $bills;
}


function getLatestCart()
{
    global $DB;
    $selectSQL = sprintf("SELECT * FROM Cart ORDER BY cartId DESC LIMIT 1;");
    $cartItem = $DB->select_query($selectSQL, 1);
    return $cartItem;
}

function getMenuItemFromCart($menuItemId)
{
    global $DB;
    $selectSQL = sprintf("SELECT * FROM MenuItem WHERE menuItemId = %s", $menuItemId);
    $menuItem = $DB->select_query($selectSQL, 1);
    return $menuItem;
}

function getBillFromPayment($billId)
{
    global $DB;
    $selectSQL = sprintf("SELECT * FROM Bill WHERE billId = %s", $billId);
    $bill = $DB->select_query($selectSQL, 1);
    return $bill;
}

function getPriceConstants($constantId)
{
    global $DB;
    $selectSQL = sprintf("SELECT * FROM PriceConstants WHERE priceConstantsId = %s", $constantId);
    $menuItem = $DB->select_query($selectSQL, 1);
    return $menuItem;
}

function getLatestBill()
{
    global $DB;
    $selectSQL = sprintf("SELECT * FROM Bill ORDER BY billId DESC LIMIT 1;");
    $bill = $DB->select_query($selectSQL, 1);
    return $bill;
}

function getLatestPayment()
{
    global $DB;
    $selectSQL = sprintf("SELECT * FROM Payment ORDER BY paymentId DESC LIMIT 1");
    $payment = $DB->select_query($selectSQL, 1);
    return $payment;
}

function getUser($username, $userpassword)
{
    global $DB;
    $selectSQL = sprintf("SELECT * FROM `User` WHERE userName = '%s' AND userPassword = md5('%s')", $username, $userpassword);
    $user = $DB->select_query($selectSQL, 1);
    return $user;
}


/******************************************************************
   All the INSERT SQL connections to the Database
   ******************************************************************/

// add item to Cart
function addToCart($menuItemId)
{
    global $DB;
    $insertSQL = sprintf("INSERT INTO Cart (`menuItemId`) VALUES ('%s')", $menuItemId);
    $DB->update_query($insertSQL);
}


function createBill($sum, $billItemIds, $paymentMethod)
{
    global $DB;
    $insertSQL = sprintf("INSERT INTO Bill VALUES (NULL, '%s', $sum)", $billItemIds);
    $DB->update_query($insertSQL);
}

function createPayment($paymentMethod, $sum, $billId)
{
    global $DB;
    $insertSQL = sprintf("INSERT INTO `payment`(`paymentId`, `billId`, `totalAmount`, `paymentMethod`) VALUES (NULL,$billId,$sum,'%s')", $paymentMethod);
    $DB->update_query($insertSQL);
}

/******************************************************************
   All the DELETE SQL connections to the Database
   In reality, no one uses DELETE. This is strictly for demo purposes.
   ******************************************************************/

// remove an item from Cart
function removeCartItem($cartId)
{
    global $DB;
    $deleteSQL = sprintf("DELETE FROM Cart WHERE cartId = %s", $cartId);
    $DB->update_query($deleteSQL);
}

function removeAllCartItems()
{
    global $DB;
    $deleteSQL = sprintf("DELETE FROM Cart ");
    $DB->update_query($deleteSQL);
}

function removePayment($paymentId)
{
    global $DB;
    $deleteSQL = sprintf("DELETE FROM Payment WHERE paymentId = %s", $paymentId);
    $DB->update_query($deleteSQL);
}

function removeBill($billId)
{
    global $DB;
    $deleteSQL = sprintf("DELETE FROM Bill WHERE billId = %s", $billId);
    $DB->update_query($deleteSQL);
}

/******************************************************************
   All the UPDATE SQL connections to the Database
   ******************************************************************/

function updateBranch($branchName, $branchAddress, $numberOfTables, $branchId)
{
    global $DB;
    $updateSQL = sprintf("UPDATE branch SET branchName='%s',branchAddress='%s',numberOfTables=%s WHERE branchId = %s", $branchName, $branchAddress, $numberOfTables, $branchId);
    $DB->update_query($updateSQL);
}
?>