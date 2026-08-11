<?php

if (!isset($_SESSION['SCHOLAR_EMAIL'])) {
    die("Session email not set");
}

// Back Button
if (isset($_POST['edu_back_btn'])) {
    header("Location: Complete_Profile.php?page=personal");
    exit;
}

// Cancle btn in update phase
if (isset($_POST['edu_cancel_btn'])) {
    header("Location: Complete_Profile.php?page=education");
    exit;
}

// Next Button 
if (isset($_POST['edu_next_btn'])) {
    header("Location: Complete_Profile.php?page=experience");
    exit;
}

$ps = $con->prepare("SELECT SCHOLAR_ID FROM scholar_master WHERE EMAIL=?");
    $ps->bind_param("s", $_SESSION['SCHOLAR_EMAIL']);
$ps->execute();
$scholar_id = $ps->get_result()->fetch_array()['SCHOLAR_ID'];

$errors = [];

/* ================= VALIDATIONS ================= */
include "Assets/PHPIncludes/Exam_Passed_Validation.php";
include "Assets/PHPIncludes/University_Validation.php";
include "Assets/PHPIncludes/School_Collge_Validation.php";
include "Assets/PHPIncludes/Divison_Validation.php";
include "Assets/PHPIncludes/Percentage_Validation.php";
include "Assets/PHPIncludes/Year_Validation.php";
include "Assets/PHPIncludes/Subject_Offered_Validation.php";
include "Assets/PHPIncludes/No_Of_Attempts_Validation.php";
/* =============================================== */

/* ================= SAVE / UPDATE ================= */
if (isset($_POST['edu_save_btn']) || isset($_POST['edu_update_btn']) || isset($_POST['edu_saveandnext_btn'])) {

    $exam   = trim($_POST['exam_passed'] ?? '');
    $board  = trim($_POST['university_board'] ?? '');
    $school = trim($_POST['school_college'] ?? '');

    $division = trim($_POST['division'] ?? '');
    $percent  = trim($_POST['percentage'] ?? '');
    $div_percent = $division . " - " . $percent . "%";

    $year   = trim($_POST['year_of_passing'] ?? '');
    $sub    = trim($_POST['subject_offered'] ?? '');
    $att    = trim($_POST['no_of_attempts'] ?? '');

    // ---------- UPDATE ----------
    if (isset($_POST['edu_update_btn']) && empty($errors)) {

        $eid = $_POST['eid'];

        $stmt = $con->prepare("
            UPDATE scholar_education_details SET
            EXAM_PASSED=?,
            UNIVERSITY_BOARD=?,
            SCHOOL_COLLEGE=?,
            DIVISION_PERCENTAGE=?,
            YEAR_OF_PASSING=?,
            SUBJECT_OFFERED=?,
            NO_OF_ATTEMPTS=?
            WHERE EDUCATION_ID=?
        ");

        $stmt->bind_param(
            "ssssissi",
            $exam,
            $board,
            $school,
            $div_percent,
            $year,
            $sub,
            $att,
            $eid
        );

        if ($stmt->execute()) {
            header("Location: Complete_Profile.php?page=education&updated=1");
            exit;
        }

    }

    // ---------- INSERT ----------
    if ((isset($_POST['edu_save_btn']) || isset($_POST['edu_saveandnext_btn'])) && empty($errors)) {

        $stmt = $con->prepare("
            INSERT INTO scholar_education_details
            (SCHOLAR_ID, EXAM_PASSED, UNIVERSITY_BOARD, SCHOOL_COLLEGE,
             DIVISION_PERCENTAGE, YEAR_OF_PASSING,
             SUBJECT_OFFERED, NO_OF_ATTEMPTS)
            VALUES (?,?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
            "issssisi",
            $scholar_id,
            $exam,
            $board,
            $school,
            $div_percent,
            $year,
            $sub,
            $att
        );

        if ($stmt->execute()) {

            if (isset($_POST['edu_saveandnext_btn'])) {
                header("Location: Complete_Profile.php?&page=experience");
            } else {
                header("Location: Complete_Profile.php?&page=education&added=1");
            }
            exit;
        }
    }
}

/* ================= DELETE ================= */
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    mysqli_query($con, "DELETE FROM scholar_education_details WHERE EDUCATION_ID='$id'");
    header("Location: Complete_Profile.php?page=education&deleted=1");
    exit;
}

/* ================= EDIT FETCH ================= */
$editRow = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($con, "SELECT * FROM scholar_education_details WHERE EDUCATION_ID='$id'");
    $editRow = mysqli_fetch_assoc($res);
}
?>
