<html>

<body>
    <?php
    /******************************************************************
       View.php
       This file is where the user interacts only and checks all the POST actions
       PHP can be combined with HTML codes.
       ******************************************************************/

    include("Common.php");

    $branches = getAllBranches();
    if (!empty($_POST)):
        // debug message to check the post actions
        //printArray($_POST);
    
        // based on the action, display the right information
        switch ($_POST['action']):
            case "selectBranch":
                // get the item being added 
                $branchId = $_POST['branchId'];
                displayMenu($branchId);
                break;
            case "addToCart":
                $menuItemId = $_POST['menuItemId'];
                $branchId = $_POST['branchId'];
                addToCart($menuItemId);
                displayMenu($branchId);
                break;
            case "viewCart":
                $branchId = $_POST['branchId'];
                displayCart($branchId);
                break;
            case "removeItemFromCart":
                $cartId = $_POST['cartId'];
                $branchId = $_POST['branchId'];
                removeCartItem($cartId);
                displayCart($branchId);
                break;
            case "removeAllItemsFromCart":
                $branchId = $_POST['branchId'];
                removeAllCartItems();
                displayCart($branchId);
                break;
            case "goBack":
                removeAllCartItems();
                displayBranches($branches);
                break;
            case "goBackFromCart":
                $branchId = $_POST['branchId'];
                displayMenu($branchId);
                break;
            case "payCart":
                $branchId = $_POST['branchId'];
                $sum = $_POST['sum'];
                $billItemIds = $_POST['billItemIds'];
                displayPay($branchId, $sum, $billItemIds);
                break;
            case "submitPayment":
                $branchId = $_POST['branchId'];
                $sum = $_POST['sum'];
                $billItemIds = $_POST['billItemIds'];
                $paymentMethod = $_POST['payment'];
                createBill($sum, $billItemIds, $paymentMethod);
                $billId = getLatestBill()['billId'];
                createPayment($paymentMethod, $sum, $billId);
                removeAllCartItems();
                displayPaymentReceipt($branchId);
                break;
            default:
                removeAllCartItems();
                displayBranches($branches);

        endswitch;
    else:
        // if there are no action posted, it will always display menu
        removeAllCartItems();
        displayBranches($branches);
    endif;
    ?>

</body>

</html>