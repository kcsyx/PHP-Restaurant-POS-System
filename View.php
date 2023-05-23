<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.6/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2/dist/tailwind.min.css" rel="stylesheet" type="text/css" />
</head>

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
            // case "selectBranch":
            //     // get the item being added 
            //     $branchId = $_POST['branchId'];
            //     displayMenu($branchId);
            //     break;
            case "selectBranch":
                // get the item being added 
                $branchId = $_POST['branchId'];
                displayTables($branchId);
                break;
            case "selectTable":
                $branchId = $_POST['branchId'];
                $tableId = $_POST['tableId'];
                displayMenu($branchId, $tableId);
                break;
            case "addToCart":
                $menuItemId = $_POST['menuItemId'];
                $tableId = $_POST['tableId'];
                $branchId = $_POST['branchId'];
                addToCart($menuItemId, $tableId);
                displayMenu($branchId, $tableId);
                displayPopup();
                break;
            case "viewCart":
                $tableId = $_POST['tableId'];
                $branchId = $_POST['branchId'];
                displayCart($branchId, 0, $tableId);
                break;
            case "applyPromotionCode":
                $tableId = $_POST['tableId'];
                $promotionCode = $_POST['promotionCode'];
                $branchId = $_POST['branchId'];
                $promotion = checkPromoCode($promotionCode);
                if (!empty($promotion['promotionValue'])) {
                    $discount = $promotion['promotionValue'];
                    displayCart($branchId, $discount, $tableId);
                    displayDiscountPopup();
                } else {
                    $discount = 0;
                    displayCart($branchId, $discount, $tableId);
                }
                break;
            case "removeItemFromCart":
                $cartId = $_POST['cartId'];
                $tableId = $_POST['tableId'];
                $branchId = $_POST['branchId'];
                removeCartItem($cartId);
                displayCart($branchId, 0, $tableId);
                break;
            case "removeAllItemsFromCart":
                $tableId = $_POST['tableId'];
                $branchId = $_POST['branchId'];
                removeAllCartItems();
                displayCart($branchId, 0, $tableId);
                break;
            case "goBack":
                removeAllCartItems();
                displayBranches($branches);
                break;
            case "goBackFromMenu":
                $branchId = $_POST['branchId'];
                displayTables($branchId);
                break;
            case "goBackFromCart":
                $branchId = $_POST['branchId'];
                $tableId = $_POST['tableId'];
                displayMenu($branchId, $tableId);
                break;
            case "goBackFromPay":
                $branchId = $_POST['branchId'];
                $tableId = $_POST['tableId'];
                displayCart($branchId, 0, $tableId);
                break;
            case "payCart":
                $branchId = $_POST['branchId'];
                $tableId = $_POST['tableId'];
                $sum = $_POST['sum'];
                $billItemIds = $_POST['billItemIds'];
                displayPay($branchId, $sum, $billItemIds, $tableId);
                break;
            case "submitPayment":
                $tableId = $_POST['tableId'];
                $branchId = $_POST['branchId'];
                $sum = $_POST['sum'];
                $billItemIds = $_POST['billItemIds'];
                $paymentMethod = $_POST['payment'];
                createBill($sum, $billItemIds, $paymentMethod, $tableId);
                $billId = getLatestBill()['billId'];
                createPayment($paymentMethod, $sum, $billId);
                removeAllCartItems();
                updateTable($tableId);
                displayPaymentReceipt($branchId, $tableId);
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