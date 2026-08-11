<?php

$success = "";
$error = "";

if (!isset($_SESSION['SCHOLAR_EMAIL'])) {
    die("Session email not set");
}

//Back Button
if (isset($_POST['exp_back_btn'])) {
    header("Location: Complete_Profile.php?page=education");
    exit;
}

// Cancle btn in update phase
if (isset($_POST['exp_cancel'])) {
    header("Location: Complete_Profile.php?page=experience");
    exit;
}

//Next Button
if (isset($_POST['exp_next'])) {
    header("Location: Preview_Profile.php");
    exit;
}

$ps = $con->prepare("SELECT SCHOLAR_ID FROM scholar_master WHERE EMAIL=?");
$ps->bind_param("s", $_SESSION['SCHOLAR_EMAIL']);
$ps->execute();
$scholar_id = $ps->get_result()->fetch_array()['SCHOLAR_ID'];

/* ================= DELETE ================= */
if (isset($_GET['delete_id'])){
    $id = intval($_GET['delete_id']);
    $stmt = $con->prepare("DELETE FROM scholar_experience_details WHERE EXPERIENCE_ID=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $_SESSION['success'] = "Record Deleted Successfully";
    } else {
        $_SESSION['error'] = "Delete failed or record not found";
    }
    header("Location: Complete_Profile.php?page=experience");
    exit;
}

/* ================= EDIT LOAD ================= */
$editData = [];
if (isset($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);
    $q = mysqli_query($con, "SELECT * FROM scholar_experience_details WHERE EXPERIENCE_ID=$id");
    $editData = mysqli_fetch_assoc($q);
}


//SAVE AND NEXT BUTTON

if (isset($_POST['exp_save_next'])) {

    // form values
    $emp_name   = trim($_POST['emp_name_address'] ?? '');
    $post_held  = trim($_POST['post_held'] ?? '');
    $pay_scale  = trim($_POST['pay_scale'] ?? '');
    $emolument  = trim($_POST['total_emolument'] ?? '');
    $nature_id  = $_POST['nature_id'] ?? '';
    $from       = $_POST['service_from'] ?? '';
    $to         = $_POST['service_to'] ?? '';

    // ================= CHECK STATES =================

    // any field filled?
    $anyFilled = (
        !empty($emp_name) ||
        !empty($post_held) ||
        !empty($pay_scale) ||
        !empty($nature_id) ||
        !empty($emolument) ||   
        !empty($from) ||
        !empty($to)
    );

    // all required filled?
    $allFilled = (
        !empty($emp_name) &&
        !empty($post_held) &&
        !empty($pay_scale) &&
        !empty($nature_id) &&
        !empty($from) &&
        !empty($to)
    );

    // ================= CASE 1 =================
    // Half filled form → error
    if ($anyFilled && !$allFilled) {
        $error = "All fields are required";
    }

    // ================= CASE 2 =================
    else {

        // 👉 FULL FORM → SAVE FIRST
        if ($allFilled) {

            // 🔥 SAFE CALCULATION (PHP)
            $total = "0.0";

            $start = new DateTime($from);
            $end   = new DateTime($to);

            if ($end >= $start) {
                $diff = $start->diff($end);
                $years  = $diff->y;
                $months = $diff->m;

                $total = $years . "." . str_pad($months, 2, "0", STR_PAD_LEFT);
            }

            // INSERT
            $stmt = $con->prepare("
                INSERT INTO scholar_experience_details
                (SCHOLAR_ID, EMPLOYEE_NAME_ADDRESS, POST_HELD, PAY_SCALE, TOTAL_EMOLUMENT,
                 NATURE_ID, SERVICE_FROM, SERVICE_TO, TOTAL_EXPERIENCE)
                VALUES (?,?,?,?,?,?,?,?,?)
            ");

            $stmt->bind_param(
                "isssdssss",
                $scholar_id,
                $emp_name,
                $post_held,
                $pay_scale,
                $emolument,
                $nature_id,
                $from,
                $to,
                $total
            );

            $stmt->execute();
        }

        // ================= FINAL CHECK =================
        // At least one record required
        $check = mysqli_query($con, "
            SELECT COUNT(*) as total 
            FROM scholar_experience_details 
            WHERE SCHOLAR_ID='$scholar_id'
        ");

        $row = mysqli_fetch_assoc($check);

        if ($row['total'] > 0) {
            header("Location: Preview_Profile.php");
            exit;
        } else {
           $_SESSION['error'] = "Please add at least one experience record";
header("Location: Complete_Profile.php?page=experience");
exit;
        }
    }
}

//UPDATE BUTTON

$success = "";
$error = "";

if (isset($_POST['exp_update'])) {

    // values
    $emp_name   = trim($_POST['emp_name_address']);
    $post_held  = trim($_POST['post_held']);
    $pay_scale  = trim($_POST['pay_scale']);
    $emolument  = trim($_POST['total_emolument']);
    $nature_id  = $_POST['nature_id'];
    $level_id   = ($_POST['level_id'] == "") ? NULL : intval($_POST['level_id']);
    $category_id = ($_POST['category_id'] == "") ? NULL : intval($_POST['category_id']);
    $from       = $_POST['service_from'];
    $to         = $_POST['service_to'];
    $total      = $_POST['total_experience'];

    // 🔥 VALIDATION (ALL required)
    if (
        empty($emp_name) ||
        empty($post_held) ||
        empty($pay_scale) ||
        empty($nature_id) ||
        empty($from) ||
        empty($to)
    ) {
        $error = "All fields are required";
    } else {

        $id = intval($_POST['exp_id']);

        // UPDATE
        $stmt = $con->prepare("
            UPDATE scholar_experience_details SET
            EMPLOYEE_NAME_ADDRESS=?,
            POST_HELD=?,
            PAY_SCALE=?,
            TOTAL_EMOLUMENT=?,
            NATURE_ID=?,
            LEVEL_ID=?,
            CATEGORY_ID=?,
            SERVICE_FROM=?,
            SERVICE_TO=?,
            TOTAL_EXPERIENCE=?
            WHERE EXPERIENCE_ID=?
        ");

        $stmt->bind_param(
            "sssdiiisssi",
            $emp_name,
            $post_held,
            $pay_scale,
            $emolument,
            $nature_id,
            $level_id,
            $category_id,
            $from,
            $to,
            $total,
            $id
        );

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                $success = "Record Updated Successfully";

                $editData = [];
            } else {
                $error = "No changes detected";
            }
        } else {
            $error = "Update failed";
        }
    }
}


// SAVE BUTTON

if (isset($_POST['exp_save'])) {

    // values lo
    $emp_name   = trim($_POST['emp_name_address']);
    $post_held  = trim($_POST['post_held']);
    $pay_scale  = trim($_POST['pay_scale']);
    $emolument  = trim($_POST['total_emolument']);
    $nature_id  = $_POST['nature_id'];
    $from       = $_POST['service_from'];
    $to         = $_POST['service_to'];
    $total      = $_POST['total_experience'];
    $level_id = ($_POST['level_id'] == "") ? NULL : intval($_POST['level_id']);
    $category_id = ($_POST['category_id'] == "") ? NULL : intval($_POST['category_id']);
    // 🔥 VALIDATION
    if (
        empty($emp_name) ||
        empty($post_held) ||
        empty($pay_scale) ||
        empty($nature_id) ||
        empty($from) ||
        empty($to) ||
        $level_id === "" || $level_id === null ||
        $category_id === "" || $category_id === null
    ) {
        $error = "All fields are required";
    } else {

        // 🔥 SAFE CALCULATION (PHP)
        $total = "0.0";

        $start = new DateTime($from);
        $end   = new DateTime($to);

        if ($end >= $start) {
            $diff = $start->diff($end);
            $years  = $diff->y;
            $months = $diff->m;

            $total = $years . "." . str_pad($months, 2, "0", STR_PAD_LEFT);
        }


        // INSERT
        $stmt = $con->prepare("
            INSERT INTO scholar_experience_details
(SCHOLAR_ID, EMPLOYEE_NAME_ADDRESS, POST_HELD, PAY_SCALE, TOTAL_EMOLUMENT,
 NATURE_ID, LEVEL_ID, CATEGORY_ID, SERVICE_FROM, SERVICE_TO, TOTAL_EXPERIENCE)
VALUES (?,?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->bind_param(
            "isssdiiisss",
            $scholar_id,
            $emp_name,
            $post_held,
            $pay_scale,
            $emolument,
            $nature_id,
            $level_id,
            $category_id,
            $from,
            $to,
            $total
        );

        if ($stmt->execute()) {
            $success = "Record Added Successfully";
        } else {
            $error = "Database Error";
        }
    }
}
