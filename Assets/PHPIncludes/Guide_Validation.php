<?php
$guideErr = "";

if(isset($_POST['aca_save_btn']) || isset($_POST['aca_saveandnext_btn'])){

    $guide = trim($_POST['guide'] ?? '');

    if(empty($guide)){
        $guideErr = "Guide Name & Address is required";
    }
    else if(!preg_match("/^[A-Za-z\s\-\/]+$/", $guide)){
        $guideErr = "Only letters, space, - and / allowed";
    }
}
?>
