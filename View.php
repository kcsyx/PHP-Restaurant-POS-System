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
            //     displayTables($branchId);
            //     break;
            case "selectBranch":
                // get the item being added 
                $branchId = $_POST['branchId'];
                displayDineOptions($branchId);
                break;
            case "selectDineOptions":
                $branchId = $_POST['branchId'];
                $isTakeaway = $_POST['isTakeaway'];
                if ($isTakeaway == false) {
                    displayTables($branchId, $isTakeaway);
                } else {
                    $tableId = "null";
                    displayMenu($branchId, $tableId, $isTakeaway);
                }
                break;
            case "selectTable":
                $branchId = $_POST['branchId'];
                $isTakeaway = $_POST['isTakeaway'];
                $tableId = $_POST['tableId'];
                displayMenu($branchId, $tableId, $isTakeaway);
                break;
            case "addToCart":
                $menuItemId = $_POST['menuItemId'];
                $tableId = $_POST['tableId'];
                $branchId = $_POST['branchId'];
                $isTakeaway = $_POST['isTakeaway'];
                addToCart($menuItemId, $tableId);
                displayMenu($branchId, $tableId, $isTakeaway);
                displayPopup();
                break;
            case "viewCart":
                $tableId = $_POST['tableId'];
                $isTakeaway = $_POST['isTakeaway'];
                $branchId = $_POST['branchId'];
                displayCart($branchId, 0, $tableId, $isTakeaway);
                break;
            case "applyPromotionCode":
                $tableId = $_POST['tableId'];
                $promotionCode = $_POST['promotionCode'];
                $isTakeaway = $_POST['isTakeaway'];
                $branchId = $_POST['branchId'];
                $promotion = checkPromoCode($promotionCode);
                if (!empty($promotion['promotionValue'])) {
                    $discount = $promotion['promotionValue'];
                    displayCart($branchId, $discount, $tableId, $isTakeaway);
                    displayDiscountPopup();
                } else {
                    $discount = 0;
                    displayCart($branchId, $discount, $tableId, $isTakeaway);
                }
                break;
            case "removeItemFromCart":
                $cartId = $_POST['cartId'];
                $tableId = $_POST['tableId'];
                $isTakeaway = $_POST['isTakeaway'];
                $branchId = $_POST['branchId'];
                removeCartItem($cartId);
                displayCart($branchId, 0, $tableId, $isTakeaway);
                break;
            case "removeAllItemsFromCart":
                $tableId = $_POST['tableId'];
                $isTakeaway = $_POST['isTakeaway'];
                $branchId = $_POST['branchId'];
                removeAllCartItems();
                displayCart($branchId, 0, $tableId, $isTakeaway);
                break;
            case "goBack":
                removeAllCartItems();
                displayBranches($branches);
                break;
            case "goBackFromTables":
                $branchId = $_POST['branchId'];
                removeAllCartItems();
                displayDineOptions($branchId);
                break;
            case "goBackFromMenu":
                $branchId = $_POST['branchId'];
                $isTakeaway = $_POST['isTakeaway'];
                removeAllCartItems();
                displayTables($branchId, $isTakeaway);
                break;
            case "goBackFromCart":
                $branchId = $_POST['branchId'];
                $isTakeaway = $_POST['isTakeaway'];
                $tableId = $_POST['tableId'];
                displayMenu($branchId, $tableId, $isTakeaway);
                break;
            case "goBackFromPay":
                $isTakeaway = $_POST['isTakeaway'];
                $branchId = $_POST['branchId'];
                $tableId = $_POST['tableId'];
                displayCart($branchId, 0, $tableId, $isTakeaway);
                break;
            case "payCart":
                $branchId = $_POST['branchId'];
                $isTakeaway = $_POST['isTakeaway'];
                $tableId = $_POST['tableId'];
                $sum = $_POST['sum'];
                $billItemIds = $_POST['billItemIds'];
                displayPay($branchId, $sum, $billItemIds, $tableId, $isTakeaway);
                break;
            case "submitPayment":
                $tableId = $_POST['tableId'];
                $isTakeaway = $_POST['isTakeaway'];
                $branchId = $_POST['branchId'];
                $sum = $_POST['sum'];
                $billItemIds = $_POST['billItemIds'];
                $paymentMethod = $_POST['payment'];
                createBill($sum, $billItemIds, $paymentMethod, $tableId);
                $billId = getLatestBill()['billId'];
                createPayment($paymentMethod, $sum, $billId);
                removeAllCartItems();
                if ($isTakeaway == false) {
                    updateTable($tableId);
                }
                displayPaymentReceipt($branchId, $tableId, $isTakeaway);
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