<?php
function setMsg($type, $message)
{
    $bg = $type === "success" 
        ? "alert-success" 
        : "alert-danger";

    $_SESSION['msg'] = "
    <div class='alert $bg alert-dismissible fade show mb-3' role='alert'>
        $message
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>";
}
?>