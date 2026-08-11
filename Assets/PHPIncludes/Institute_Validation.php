<?php
$instituteErr = "";

if(isset($_POST['per_save_btn']) || isset($_POST['per_saveandnext_btn'])){

    $institute = trim($_POST['institute_where'] ?? "");

    if(empty($institute)){
        $instituteErr = "Institute name is required";
    }
    else if(!preg_match("/^[A-Za-z.\-\s]+$/", $institute)){
        $instituteErr = "Only letters, space, dot (.) and hyphen (-) allowed";
    }
}
?>
