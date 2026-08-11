<?php
if (!isset($_SESSION['user_id']) || !isset($_SESSION['SCHOLAR_EMAIL'])) {
    header("Location: login.php");
    exit;
}
?>

<style>
    .main-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
    }

    .section-box {
        background: #f8f9fb;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .heading-blue {
        color: #0d6efd;
        font-weight: 700;
    }

    .btn-area {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
    }

    .btn-right {
        display: flex;
        gap: 12px;
    }

    .btn-area button {
        min-width: 130px;
        height: 42px;
        font-weight: 600;
        border-radius: 6px;
    }

    .btn-gradient {
        background: linear-gradient(90deg, #28a745, #007bff);
        color: white;
    }
</style>

<div class="main-card  p-3">

    <h4 class="heading-blue mb-3">Academic Details</h4>

    <?php if (!empty($successMsg)) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $successMsg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if (!empty($errorMsg)) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $errorMsg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <div class="section-box">

        <form method="post">

            <div class="row">

                <div class="col-md-4">
                    <label>Entrance Sheet Number</label>
                    <input type="text" name="entrance" id="entrance" class="form-control"
                        value="<?= $_POST['entrance'] ?? ($acadata['ENTRANCE_SHEET_NO'] ?? '') ?>">
                    <small class="text-danger" id="entranceError"><?php echo $entranceErr ?? ""; ?></small>
                </div>

                <div class="col-md-4">
                    <label>Fee Receipt No</label>
                    <input type="text" name="fee_no" id="fee_no" class="form-control"
                        value="<?= $_POST['fee_no'] ?? ($acadata['FEE_RECEIPT_NO'] ?? '') ?>">
                    <small class="text-danger" id="feeError"><?php echo $feeErr ?? ""; ?></small>
                </div>

                <div class="col-md-4">
                    <label>Fee Receipt Date</label>
                    <input type="date" name="fee_date" class="form-control" id="fee_date"
                        value="<?= $_POST['fee_date'] ?? ($acadata['FEE_RECEIPT_DATE'] ?? '') ?>">
                    <small class="text-danger" id="feeDateError"><?php echo $feeDateErr ?? ""; ?></small>
                </div>

                <div class="col-md-4">
                    <label>Registration Number</label>
                    <input type="text" class="form-control"
                        value="<?= $acadata['SCHOLAR_REGISTRATION_NUMBER'] ?? '' ?>" readonly>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Registration Date</label>
                    <input type="date" class="form-control"
                        value="<?= $acadata['REGISTRATION_DATE'] ?? '' ?>" readonly>
                </div>


                <div class="col-md-12">
                    <label>Full Title of Proposed Research</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="<?= $_POST['title'] ?? ($acadata['RESEARCH_TITLE'] ?? '') ?>">
                    <small class="text-danger" id="titleError"><?php echo $titleErr ?? ""; ?></small>
                </div>

                <?php
                $facultynamefecth = "Select FACULTY_NAME from faculty_available where FACULTY_ID= ?";
                $facstmt = $con->prepare($facultynamefecth);
                $facstmt->bind_param("i", $acadata['FACULTY_ID']);
                $facstmt->execute();
                $facultyname = $facstmt->get_result()->fetch_array()['FACULTY_NAME'];
                ?>

                <div class="col-md-6">
                    <label>Faculty</label>
                    <input type="text" class="form-control"
                        value="<?php echo $facultyname; ?> " readonly>
                </div>

                <?php
                $subjectnamefecth = "Select SUBJECT_NAME from subject_available where SUBJECT_ID= ?";
                $substmt = $con->prepare($subjectnamefecth);
                $substmt->bind_param("i", $acadata['SUBJECT_ID']);
                $substmt->execute();
                $subjectname = $substmt->get_result()->fetch_array()['SUBJECT_NAME'];

                ?>

                <div class="col-md-6">
                    <label>Subject</label>
                    <input type="text" class="form-control"
                        value="<?php echo $subjectname; ?>" readonly>
                </div>


                <div class="col-md-12">
                    <label>Name & Address of Guide</label>
                    <textarea name="guide" class="form-control" id="guide"><?php
                                                                echo $_POST['guide'] ?? ($acadata['GUIDE_NAME_ADDRESS'] ?? '');
                                                                ?></textarea>
                    <small class="text-danger" id="guideError"><?php echo $guideErr ?? ""; ?></small>
                </div>

            </div>

            <!-- BUTTONS -->
            <div class="btn-area">

                <!-- BACK DISABLED -->
                <button type="button" class="btn btn-secondary" disabled>Back</button>

                <div class="btn-right">

                    <button type="submit" name="aca_save_btn" class="btn btn-success">
                        Save
                    </button>

                    <button type="submit" name="aca_saveandnext_btn"
                     class="btn btn-outline-success" 
                     onclick="return confirm('Save Details and go to next page?')">
                        Save & Next
                    </button>

                    <!-- NEXT WITHOUT SAVE -->
                    <button type="submit"
                        class="btn btn-primary"
                        name="aca_next_btn">
                        Next
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>

<!-- Entarance Sheet Number Validation Script -->
<script src="Assets/JSIncludes/Entrance_Sheet_Number_Validation.js?v=2"></script>

<!-- Fee Receipt Number Validation Script -->
<script src="Assets/JSIncludes/Fee_Receipt_Number_Validation.js?v=<?php echo time(); ?>"></script>

<!-- Fee Receipt Date Validation Script -->
<script src="Assets/JSIncludes/Fee_Receipt_Date_Validation.js?v=<?php echo time(); ?>"></script>

<!-- Research Title Validation Script -->
<script src="Assets/JSIncludes/Research_Title_Validation.js?v=<?php echo time(); ?>"></script>

<!-- Guide Validation Script -->
<script src="Assets/JSIncludes/Guide_Validation.js?v=<?php echo time(); ?>"></script>