<?php
/******************************************************************
   ControllerDisplay.php
   This file communicates with View.php and responsible for all the display output
   ******************************************************************/

function displayMenu($branchId)
{
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