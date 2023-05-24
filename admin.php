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

    echo "<div class='container my-16 px-6 mx-auto'>";
    echo "<div class='navbar text-neutral-content'>";
    // Log Out Button
    echo "<div class='navbar-start'> <form class='mb-8' action=index.php method='post'>";
    echo "<input type='hidden' name='action'><input class='btn btn-primary w-full max-w-xs' type='submit' value='Logout' />";
    echo "</form></div>";

    echo "<div class='navbar-end gap-2'>";
    // View Promotion Button
    echo "<div><form class='mb-8' action=admin.php method='post'>";
    echo "<input type='hidden' name='action' value='viewPromotions'><input class='btn btn-primary w-full max-w-xs' type='submit' value='Promotions' />";
    echo sprintf("<input type='hidden' name='promotionId' value='%s'>", 0);
    echo "</form></div>";
    // View Payments Button
    echo "<div><form class='mb-8' action=admin.php method='post'>";
    echo "<input type='hidden' name='action' value=''><input class='btn btn-primary w-full max-w-xs' type='submit' value='Payments' />";
    echo "</form></div>";
    // View Branches Button
    echo "<div><form class='mb-8' action=admin.php method='post'>";
    echo "<input type='hidden' name='action' value='viewBranches'><input class='btn btn-primary w-full max-w-xs' type='submit' value='Branches' />";
    echo sprintf("<input type='hidden' name='branchId' value='%s'>", 0);
    echo "</form></div>";
    echo "</div>";

    echo "</div>";

    if (!empty($_POST)) {
        switch ($_POST['action']) {
            case 'deletePayment':
                $paymentId = $_POST['paymentId'];
                $billId = $_POST['billId'];
                removePayment($paymentId);
                removeBill($billId);
                adminPayment();
                break;
            case 'viewPromotions':
                $promotionId = $_POST['promotionId'];
                adminPromotions($promotionId, false);
                break;
            case 'updatePromotion':
                $promotionId = $_POST['promotionId'];
                adminPromotions($promotionId, false);
                break;
            case 'cancelUpdatePromotion':
                adminPromotions(0, false);
                break;
            case 'confirmUpdatePromotion':
                updatePromotions($_POST['promotionName'], $_POST['promotionCode'], $_POST['promotionValue'], $_POST['promotionId']);
                adminPromotions(0, false);
                break;
            case 'deletePromotion':
                $promotionId = $_POST['promotionId'];
                removePromotion($promotionId);
                adminPromotions(0, false);
                break;
            case 'newPromotion':
                adminPromotions(0, true);
                break;
            case 'confirmNewPromotion':
                createPromotion($_POST['promotionName'], $_POST['promotionCode'], $_POST['promotionValue']);
                adminPromotions(0, false);
                break;
            case 'cancelNewPromotion':
                adminPromotions(0, false);
                break;
            case 'viewBranches':
                $branchId = $_POST['branchId'];
                adminBranches($branchId);
                break;
            case 'updateBranch':
                $branchId = $_POST['branchId'];
                adminBranches($branchId);
                break;
            case 'cancelUpdateBranch':
                adminBranches(0);
                break;
            case 'confirmUpdateBranch':
                if (empty($_FILES['branchImage']['name'])) {
                    $img = $_POST['originalBranchImage'];
                } else {
                    $img = $_FILES['branchImage']['name'];
                    move_uploaded_file($_FILES['branchImage']['tmp_name'], "images/$img");
                }
                updateBranch($_POST['branchName'], $_POST['branchAddress'], $_POST['numberOfTables'], $img, $_POST['branchId']);
                adminBranches(0);
                break;
            case 'seeTables':
                $branchId = $_POST['branchId'];
                adminTables($branchId);
                break;
            case 'freeTable':
                $branchId = $_POST['branchId'];
                $tableId = $_POST['tableId'];
                freeTable($tableId);
                adminTables($branchId);
                break;
            default:
                adminPayment();
                break;
        }
    } else {
        adminPayment();
    }

    function adminTables($branchId)
    {
        $tables = getAllTables($branchId);
        $branch = getBranchFromTable($branchId);
        echo "<div class='pt-8'>";
        echo sprintf("<span class='text-lg'>Branch: <b>%s</b></span><div class='divider'></div>", $branch['branchName']);
        echo "<div class='overflow-x-auto'>";
        echo "<table class='table table-zebra w-full'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Table Id</th>";
        echo "<th>Table Number</th>";
        echo "<th>Table Status</th>";
        echo "<th>Actions</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($tables as $table):
            echo "<tr>";
            echo sprintf("<td>%s</td>", $table['tableId']);
            echo sprintf("<td>%s</td>", $table['tableNo']);
            if ($table['isReserved'] == 0) {
                echo sprintf("<td><p style='color:green'>Available</p></td>");
                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action'>";
                echo sprintf("<input class='btn btn-sm btn-disabled' type='submit' value='Set Available' />");
                echo "</form></td>";
                echo "</tr>";
            } else {
                echo sprintf("<td><p style='color:red';>Occupied</p></td>");
                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='freeTable'>";
                echo sprintf("<input class='btn btn-sm btn-success' type='submit' value='Set Available' />");
                echo sprintf("<input type='hidden' name='tableId' value='%s'>", $table['tableId']);
                echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branchId);
                echo "</form></td>";
                echo "</tr>";
            }

        endforeach;

        echo "</tbody>";
        echo "</table>";
        echo "</div>";

        if (empty($tables)) {
            echo "<p><i>No tables found.</i></p>";
        }
    }

    function adminPromotions($promotionId, $isNew)
    {

        $promotions = getAllPromotions();

        echo "<div class='pt-8'>";

        echo "<div class='overflow-x-auto'>";
        echo "<table class='table table-zebra w-full'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Promotion Id</th>";
        echo "<th>Promotion Name</th>";
        echo "<th>Promotion Code</th>";
        echo "<th>Promotion Value</th>";
        echo "<th>Actions</th>";
        echo "<th></th>";
        echo "<th>";
        echo "<form class='mb-0' action=admin.php method='post'>";
        echo "<input type='hidden' name='action' value='newPromotion'>";
        echo sprintf("<input class='btn btn-sm btn-success' type='submit' value='New Promotion' />");
        echo "</form>";
        echo "</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($promotions as $promotion):
            if ($promotionId != $promotion['promotionId']) {
                echo "<tr>";
                echo sprintf("<td>%s</td>", $promotion['promotionId']);
                echo sprintf("<td>%s</td>", $promotion['promotionName']);
                echo sprintf("<td>%s</td>", $promotion['promotionCode']);
                echo sprintf("<td>%s</td>", $promotion['promotionValue']);
                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='updatePromotion'>";
                echo sprintf("<input class='btn btn-sm btn-primary' type='submit' value='Update' />");
                echo sprintf("<input type='hidden' name='promotionId' value='%s'>", $promotion['promotionId']);
                echo "</form></td>";
                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='deletePromotion'>";
                echo sprintf("<input class='btn btn-sm btn-error' type='submit' value='Delete' />");
                echo sprintf("<input type='hidden' name='promotionId' value='%s'>", $promotion['promotionId']);
                echo "</form></td>";

                echo "<td>";
                echo sprintf("<input class='btn btn-sm btn-disabled' value='' />");
                echo "</td>";

                echo "</tr>";
            } else {
                echo "<tr>";
                echo sprintf("<td>%s</td>", $promotion['promotionId']);
                echo "<form class='mt-8' method='post''>";
                echo sprintf("<td><input type='text' placeholder='%s' class='input input-bordered w-full max-w-xs' name='promotionName' value='%s'/></td>", $promotion['promotionName'], $promotion['promotionName']);
                echo sprintf("<td><input type='text' placeholder='%s' class='input input-bordered w-full max-w-xs' name='promotionCode' value='%s'/></td>", $promotion['promotionCode'], $promotion['promotionCode']);
                echo sprintf("<td><input type='text' placeholder='%s' class='input input-bordered w-full max-w-xs' name='promotionValue' value='%s'/></td>", $promotion['promotionValue'], $promotion['promotionValue']);

                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='confirmUpdatePromotion'>";
                echo sprintf("<input class='btn btn-sm btn-success' type='submit' value='Confirm' />");
                echo sprintf("<input type='hidden' name='promotionId' value='%s'>", $promotionId);
                echo "</form></td>";
                echo "</form>";

                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='cancelUpdatePromotion'>";
                echo sprintf("<input class='btn btn-sm btn-error' type='submit' value='Cancel' />");
                echo "</form></td>";

                echo "<td>";
                echo sprintf("<input class='btn btn-sm btn-disabled' value='' />");
                echo "</td>";
                echo "</tr>";
            }

        endforeach;

        if ($isNew == true) {
            echo "<tr>";
            echo sprintf("<td></td>");
            echo "<form class='mt-8' method='post''>";
            echo sprintf("<td><input type='text' placeholder='Promotion Name' class='input input-bordered w-full max-w-xs' name='promotionName'/></td>");
            echo sprintf("<td><input type='text' placeholder='Promotion Code' class='input input-bordered w-full max-w-xs' name='promotionCode'/></td>");
            echo sprintf("<td><input type='number' step='0.01' placeholder='Promotion Value' class='input input-bordered w-full max-w-xs' name='promotionValue'/></td>");

            echo "<td><form class='mb-0' action=admin.php method='post'>";
            echo "<input type='hidden' name='action' value='confirmNewPromotion'>";
            echo sprintf("<input class='btn btn-sm btn-success' type='submit' value='Confirm' />");
            echo "</form></td>";
            echo "</form>";

            echo "<td><form class='mb-0' action=admin.php method='post'>";
            echo "<input type='hidden' name='action' value='cancelNewPromotion'>";
            echo sprintf("<input class='btn btn-sm btn-error' type='submit' value='Cancel' />");
            echo "</form></td>";

            echo "<td>";
            echo sprintf("<input class='btn btn-sm btn-disabled' value='' />");
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";

        if (empty($promotions)) {
            echo "<p><i>No promotions found.</i></p>";
        }
    }

    function adminPayment()
    {
        $payments = getAllPayments();

        echo "<div class='pt-8'>";

        echo "<div class='overflow-x-auto'>";
        echo "<table class='table table-zebra w-full'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Payment Id</th>";
        echo "<th>Bill Id</th>";
        echo "<th>Total Amount</th>";
        echo "<th>DateTime</th>";
        echo "<th>Payment Method</th>";
        echo "<th>Items Ordered</th>";
        echo "<th>Dining</th>";
        echo "<th>Actions</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($payments as $payment):
            echo "<tr>";
            echo sprintf("<td>%s</td>", $payment['paymentId']);
            echo sprintf("<td>%s</td>", $payment['billId']);
            echo sprintf("<td>$%s</td>", $payment['totalAmount']);
            echo sprintf("<td>%s</td>", $payment['paymentDateTime']);
            echo sprintf("<td>%s</td>", $payment['paymentMethod']);

            $paymentBill = getBillFromPayment($payment['billId']);
            $billMenuItems = $paymentBill['menuIds'];
            $billMenuItemsArray = explode(",", $billMenuItems);
            $billTableId = $paymentBill['tableId'];
            $branchId = getTable($billTableId)['branchId'];
            $branchName = (getBranchFromTable($branchId))['branchName'];
            echo "<td>";
            foreach ($billMenuItemsArray as $billMenuItem):
                $menuItem = getMenuItemFromCart($billMenuItem);
                echo $menuItem['menuItemName'];
                echo "<br>";
            endforeach;
            echo "</td>";
            if (!empty($branchName)) {
                echo sprintf("<td>Dine-In, %s</td>", $branchName);
            } else {
                echo sprintf("<td>Takeaway, %s</td>", (getBranchFromTable($paymentBill['branchId']))['branchName']);
            }
            echo "<td><form class='mb-0' action=admin.php method='post'>";
            echo "<input type='hidden' name='action' value='deletePayment'>";
            echo sprintf("<input class='btn btn-sm btn-error' type='submit' value='Delete' />");
            echo sprintf("<input type='hidden' name='paymentId' value='%s'>", $payment['paymentId']);
            echo sprintf("<input type='hidden' name='billId' value='%s'>", $payment['billId']);
            echo "</form></td>";

            echo "</tr>";
        endforeach;
        echo "</tbody>";
        echo "</table>";
        echo "</div>";

        if (empty($payment)) {
            echo "<p><i>No payment records found.</i></p>";
        }
    }

    function adminBranches($branchId)
    {

        $branches = getAllBranches();

        echo "<div class='pt-8'>";

        echo "<div class='overflow-x-auto'>";
        echo "<table class='table table-zebra w-full'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Branch Id</th>";
        echo "<th>Branch Name</th>";
        echo "<th>Branch Address</th>";
        echo "<th>Number of Tables</th>";
        echo "<th>Branch Image</th>";
        echo "<th>Actions</th>";
        echo "<th></th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        foreach ($branches as $branch):
            if ($branchId != $branch['branchId']) {
                echo "<tr>";
                echo sprintf("<td>%s</td>", $branch['branchId']);
                echo sprintf("<td>%s</td>", $branch['branchName']);
                echo sprintf("<td>%s</td>", $branch['branchAddress']);
                echo sprintf("<td><p class='text-center'>%s</p>", $branch['numberOfTables']);
                echo "<form class='pt-2 mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='seeTables'>";
                echo sprintf("<input class='btn btn-sm btn-success' type='submit' value='View Tables' />");
                echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branch['branchId']);
                echo "</form></td>";
                echo sprintf("<td><img class='w-32 h-full' src='images/" . $branch['branchImage'] . "'/></td>");
                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='updateBranch'>";
                echo sprintf("<input class='btn btn-sm btn-primary' type='submit' value='Update' />");
                echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branch['branchId']);
                echo "</form></td>";
                echo "<td>";
                echo sprintf("<input class='btn btn-sm btn-disabled' value='' />");
                echo "</td>";
                echo "</tr>";
            } else {
                echo "<tr>";
                echo sprintf("<td>%s</td>", $branch['branchId']);
                echo "<form class='mt-8' method='post' enctype='multipart/form-data'>";
                echo sprintf("<td><input type='text' placeholder='%s' class='input input-bordered w-full max-w-xs' name='branchName' value='%s'/></td>", $branch['branchName'], $branch['branchName']);
                echo sprintf("<td><input type='text' placeholder='%s' class='input input-bordered w-full max-w-xs' name='branchAddress' value='%s'/></td>", $branch['branchAddress'], $branch['branchAddress']);
                echo sprintf("<td><input type='text' placeholder='%s' class='input input-bordered w-full max-w-xs' name='numberOfTables' value='%s'/></td>", $branch['numberOfTables'], $branch['numberOfTables']);

                echo sprintf("<td><img class='w-32 h-full' src='images/" . $branch['branchImage'] . "'/><input class='w-32' type='file' name='branchImage'/></td>");

                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='confirmUpdateBranch'>";
                echo sprintf("<input class='btn btn-sm btn-success' type='submit' value='Confirm' />");
                echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branchId);
                echo sprintf("<input type='hidden' name='originalBranchImage' value='%s'>", $branch['branchImage']);
                echo "</form></td>";
                echo "</form>";

                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='cancelUpdateBranch'>";
                echo sprintf("<input class='btn btn-sm btn-error' type='submit' value='Cancel' />");
                echo "</form></td>";
                echo "</tr>";
            }

        endforeach;
        echo "</tbody>";
        echo "</table>";
        echo "</div>";

        if (empty($branches)) {
            echo "<p><i>No branches found.</i></p>";
        }
    }

    echo "</div>";
    ?>
</body>

</html>