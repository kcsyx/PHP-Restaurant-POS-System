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
    // View Payments Button
    echo "<div class='navbar-end'> <form class='mb-8' action=admin.php method='post'>";
    echo "<input type='hidden' name='action' value=''><input class='btn btn-primary w-full max-w-xs' type='submit' value='Payments' />";
    echo "</form></div>";
    // View Branches Button
    echo "<div class='navbar-end'> <form class='mb-8' action=admin.php method='post'>";
    echo "<input type='hidden' name='action' value='viewBranches'><input class='btn btn-primary w-full max-w-xs' type='submit' value='Branches' />";
    echo sprintf("<input type='hidden' name='branchId' value='%s'>", 0);
    echo "</form></div>";
    echo "</div>";

    if (!empty($_POST)) {
        switch ($_POST['action']) {
            case 'removePayment':
                $paymentId = $_POST['paymentId'];
                $billId = $_POST['billId'];
                removePayment($paymentId);
                removeBill($billId);
                adminPayment();
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
                updateBranch($_POST['branchName'], $_POST['branchAddress'], $_POST['numberOfTables'], $_POST['branchId']);
                adminBranches(0);
                break;
            default:
                adminPayment();
                break;
        }
    } else {
        adminPayment();
    }

    function adminPayment()
    {
        $payments = getAllPayments();

        echo "<div class='pt-16'>";

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
            $billMenuItems = $paymentBill['cartIds'];
            $billMenuItemsArray = explode(",", $billMenuItems);
            echo "<td>";
            foreach ($billMenuItemsArray as $billMenuItem):
                $menuItem = getMenuItemFromCart($billMenuItem);
                echo $menuItem['menuItemName'];
                echo "<br>";
            endforeach;
            echo "</td>";

            echo "<td><form class='mb-0' action=admin.php method='post'>";
            echo "<input type='hidden' name='action' value='removePayment'>";
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

        echo "<div class='pt-16'>";

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
                echo sprintf("<td>%s</td>", $branch['numberOfTables']);
                echo sprintf("<td><img class='w-32 h-full' src='images/" . $branch['branchImage'] . ".jpg'/></td>");
                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='updateBranch'>";
                echo sprintf("<input class='btn btn-sm btn-primary' type='submit' value='Update' />");
                echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branch['branchId']);
                echo "</form></td>";
                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='deleteBranch'>";
                echo sprintf("<input class='btn btn-sm btn-error' type='submit' value='Delete' />");
                echo "</form></td>";
                echo "</tr>";
            } else {
                echo "<tr>";
                echo sprintf("<td>%s</td>", $branch['branchId']);
                echo "<form class='mt-8' method='post'>";
                echo sprintf("<td><input type='text' placeholder='%s' class='input input-bordered w-full max-w-xs' name='branchName' value='%s'/></td>", $branch['branchName'], $branch['branchName']);
                echo sprintf("<td><input type='text' placeholder='%s' class='input input-bordered w-full max-w-xs' name='branchAddress' value='%s'/></td>", $branch['branchAddress'], $branch['branchAddress']);
                echo sprintf("<td><input type='text' placeholder='%s' class='input input-bordered w-full max-w-xs' name='numberOfTables' value='%s'/></td>", $branch['numberOfTables'], $branch['numberOfTables']);
                echo sprintf("<td><img class='w-32 h-full' src='images/" . $branch['branchImage'] . ".jpg'/></td>");
                echo "<td><form class='mb-0' action=admin.php method='post'>";
                echo "<input type='hidden' name='action' value='confirmUpdateBranch'>";
                echo sprintf("<input class='btn btn-sm btn-success' type='submit' value='Confirm' />");
                echo sprintf("<input type='hidden' name='branchId' value='%s'>", $branchId);
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