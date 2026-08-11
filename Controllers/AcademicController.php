<?php

if (!isset($_SESSION['user_id']) || !isset($_SESSION['SCHOLAR_EMAIL'])) {
    header("Location: login.php");
    exit;
}

// ============ Next Button ======================

if (isset($_POST['aca_next_btn'])) {
    header("Location: Complete_Profile.php?page=personal");
    exit;
}
$scholar_id = $_SESSION['user_id'];

// ================= FETCH DATA =================
$q = $con->prepare("SELECT s.*, f.FACULTY_NAME, sub.SUBJECT_NAME
FROM scholar_master s
JOIN faculty_available f ON s.FACULTY_ID = f.FACULTY_ID
JOIN subject_available sub ON s.SUBJECT_ID = sub.SUBJECT_ID
WHERE s.SCHOLAR_ID = ?");
$q->bind_param("i", $scholar_id);
$q->execute();
$acadata = $q->get_result()->fetch_assoc();

// ================= VALIDATIONS =================
include 'Assets/PHPIncludes/Entrance_Sheet_Number_Validation.php';
include 'Assets/PHPIncludes/Fee_Receipt_Number_Validation.php';
include 'Assets/PHPIncludes/Fee_Receipt_Date_Validation.php';
include 'Assets/PHPIncludes/Research_Title_Validation.php';
include 'Assets/PHPIncludes/Guide_Validation.php';

// ================= SAVE Button =================
if (isset($_POST['aca_save_btn']) || isset($_POST['aca_saveandnext_btn'])) {

    if (
        empty($entranceErr) &&
        empty($feeErr) &&
        empty($feeDateErr) &&
        empty($titleErr) &&
        empty($guideErr)
    ) {

        $entrance = trim($_POST['entrance']);
        $fee_no   = trim($_POST['fee_no']);
        $fee_date = $_POST['fee_date'];
        $title    = trim($_POST['title']);
        $guide    = trim($_POST['guide']);

        $stmt = $con->prepare("
            UPDATE scholar_master SET
            ENTRANCE_SHEET_NO=?,
            FEE_RECEIPT_NO=?,
            FEE_RECEIPT_DATE=?,
            RESEARCH_TITLE=?,
            GUIDE_NAME_ADDRESS=?
            WHERE SCHOLAR_ID=?
        ");

        $stmt->bind_param(
            "sssssi",
            $entrance,
            $fee_no,
            $fee_date,
            $title,
            $guide,
            $scholar_id
        );

        if ($stmt->execute()) {

            // ===== WHEN DATA CHANGED =====
            if ($stmt->affected_rows > 0) {

                // Save button message
                if (isset($_POST['aca_save_btn'])) {
                    $successMsg = "Academic Details Saved Successfully";
                }

                // Save & Next button
                if (isset($_POST['aca_saveandnext_btn'])) {
                    header("Location: Complete_Profile.php?page=personal");
                    exit;
                }

            }
            // ===== WHEN NO DATA CHANGED =====
            else {
                $errorMsg = "Please first change data, then save.";
            }
        } else {
            $errorMsg = "Error in saving data. Please try again.";
        }

        $stmt->close();
    }
}
?>