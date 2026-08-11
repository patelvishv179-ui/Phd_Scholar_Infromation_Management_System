<?php
$parentNameErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $parent_name = trim($_POST['parent_name'] ?? '');

    if(empty($parent_name)){
        $parentNameErr = "Parent / Guardian Name is required";
    }
    else if(!preg_match("/^[A-Za-z ]+$/", $parent_name)){
        $parentNameErr = "Only letters and spaces allowed";
    }
}
?>
