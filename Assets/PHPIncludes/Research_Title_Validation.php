<?php
$titleErr = "";

if(isset($_POST['aca_save_btn']) || isset($_POST['aca_saveandnext_btn'])){

    $title = trim($_POST['title'] ?? '');

    if(empty($title)){
        $titleErr = "Research Title is required";
    }
    else if(!preg_match("/^[A-Za-z\s\-\/]+$/", $title)){
        $titleErr = "Only letters, space, - and / allowed";
    }
}
?>
