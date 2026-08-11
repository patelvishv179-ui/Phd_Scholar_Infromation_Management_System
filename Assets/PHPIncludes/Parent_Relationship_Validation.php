<?php
$relationErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $relation = trim($_POST['parent_relation'] ?? '');

    if(empty($relation)){
        $relationErr = "Relationship is required";
    }
    else if(!preg_match("/^[A-Za-z ]+$/", $relation)){
        $relationErr = "Only letters and spaces allowed";
    }
}
?>
