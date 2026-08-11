<?php
$categoryErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $category = $_POST['category'] ?? "";

    if(empty($category)){
        $categoryErr = "Please select admission category";
    }
}
?>
