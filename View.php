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
            case "goBack":
                displayBranches($branches);;
                break;
            default:
                displayBranches($branches);

        endswitch;
    else:
        // if there are no action posted, it will always display menu
        displayBranches($branches);
    endif;
    ?>

</body>

</html>