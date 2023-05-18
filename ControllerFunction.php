<?php
/******************************************************************
   ControllerFunction.php
   This file communicates with ControllerDisplay.php and responsible for all the database connections
   and formatting the data correctly for display.
   ******************************************************************/

function getMenuItems($branchId)
{
    $menu = getMenu($branchId);
    $result = array();
    $menuCategories = getMenuCategories($menu['menuId']);

    foreach ($menuCategories as $menuCategory):
        $menuItem = getMenuItem($menuCategory['menuCategoryId']);
        array_push($result, $menuItem);
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

?>