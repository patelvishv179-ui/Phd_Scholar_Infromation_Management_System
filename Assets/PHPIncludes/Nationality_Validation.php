<?php
$nationErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $nation = trim($_POST['nationality'] ?? '');

    if(empty($nation)){
        $nationErr = "Nationality is required";
    }
    else if(!preg_match("/^[A-Za-z ]+$/", $nation)){
        $nationErr = "Only letters and spaces allowed";
    }
}
?>
    