<?php
$genderErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $gender = $_POST['gender'] ?? '';

    if(empty($gender)){
        $genderErr = "Please select Gender";
    }
}
?>
