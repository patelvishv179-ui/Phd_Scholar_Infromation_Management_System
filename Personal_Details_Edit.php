<?php


$ps = $con->prepare("SELECT SCHOLAR_ID FROM scholar_master WHERE EMAIL=?");
$ps->bind_param("s", $_SESSION['email']);
$ps->execute();
$scholar_id = $ps->get_result()->fetch_array()['SCHOLAR_ID'];

$stmt = $con->prepare("
SELECT 
    sm.SCHOLAR_NAME,
    sm.EMAIL,
     sm.SCHOLAR_REGISTRATION_NUMBER,
    
    spd.GENDER,
    spd.DOB,
    spd.NATIONALITY,
    spd.PARENT_NAME,
    spd.PARENT_RELATIONSHIP,
    spd.PARENT_CORR_ADDRESS,
    spd.PARENT_CORR_PIN,
    spd.SCHOLAR_PERM_ADDRESS,
    spd.SCHOLAR_PERM_PIN,
    spd.PARENT_MOBILE,
    spd.ADHAR_NUMBER,
    spd.ADMISSION_CATEGORY,
    spd.INSTITUTE_WHERE,
    spd.APPLIED_FOR_WORK,
    spd.APPLICATION_DATE,
    spd.ELIGIBILITY_CERT_NO,
    spd.ELIGIBILITY_CERT_DATE,
    spd.SCHOLAR_IMAGEURL

FROM scholar_master sm
LEFT JOIN scholar_personal_details spd 
ON sm.SCHOLAR_ID = spd.SCHOLAR_ID

WHERE sm.SCHOLAR_ID = ?
");
$stmt->bind_param("i", $scholar_id);   // i = integer
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();

$stmt->close();

// Gender Validation
include 'Assets/PHPIncludes/Gender_Validation.php';

// DOB Validation
include 'Assets/PHPIncludes/DOB_Validation.php';

// Nationality Validation
include 'Assets/PHPIncludes/Nationality_Validation.php';

// Parent / Guardian Name Validation
include 'Assets/PHPIncludes/Parent_Name_Validation.php';

// Parent Relationship Validation
include 'Assets/PHPIncludes/Parent_Relationship_Validation.php';

// Parent Mobile Validation
include 'Assets/PHPIncludes/Parent_Mobile_Validation.php';

// Parent Correspondence Address Validation
include 'Assets/PHPIncludes/Parent_Corr_Address_Validation.php';

// Parent Correspondence Pin Validation
include 'Assets/PHPIncludes/Parent_Corr_Pin_Validation.php';

// Permanent Address Validation
include 'Assets/PHPIncludes/Permanent_Address_Validation.php';

// Permanent Pin Validation
include 'Assets/PHPIncludes/Permanent_Pin_Validation.php';

// Aadhar Validation
include 'Assets/PHPIncludes/Aadhar_Validation.php';

// Other Validations can be added here
include 'Assets/PHPIncludes/Category_Validation.php';

// Institute Where Validation
include 'Assets/PHPIncludes/Institute_Validation.php';

// Applied For Work Validation
include 'Assets/PHPIncludes/Applied_Work_Validation.php';

// Application Date Validation
include 'Assets/PHPIncludes/Application_Date_Validation.php';

// Eligibility Certificate No Validation
include 'Assets/PHPIncludes/Certificate_No_Validation.php';

// Eligibility Certificate Date Validation
include 'Assets/PHPIncludes/Certificate_Date_Validation.php';

// Image Upload Validation
include 'Assets/PHPIncludes/Scholar_Image_Validation.php';

// SAVE
// ================= FINAL SAVE / UPDATE =================
if (isset($_POST['save']) || isset($_POST['save_next'])) {

    $imgPath = $data['SCHOLAR_IMAGEURL']; // old image

    if (isset($_FILES['scholar_image']) && $_FILES['scholar_image']['name'] != "") {

        $folder = "Assets/ScholarProfileImages/";
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $ext = pathinfo($_FILES['scholar_image']['name'], PATHINFO_EXTENSION);
        $filename = $data['SCHOLAR_REGISTRATION_NUMBER'] . "." . $ext;

        move_uploaded_file($_FILES['scholar_image']['tmp_name'], $folder . $filename);

        $imgPath = $folder . $filename;
    }


    // Run update only if NO validation errors
    if (
        empty($scholarNameErr) &&
        empty($genderErr) &&
        empty($dobErr) &&
        empty($nationErr) &&
        empty($parentNameErr) &&
        empty($relationErr) &&
        empty($parentMobileErr) &&
        empty($pcorrErr) &&
        empty($pcpinErr) &&
        empty($permAddrErr) &&
        empty($permPinErr) &&
        empty($emailErr) &&
        empty($aadharErr) &&
        empty($categoryErr) &&
        empty($instituteErr) &&
        empty($appliedWorkErr) &&
        empty($appDateErr) &&
        empty($certNoErr) &&
        empty($certDateErr)
    ) {

        // ===== SANITIZE VALUES =====
        $scholar_name   = htmlspecialchars(trim($_POST['scholar_name']));
        $gender         = trim($_POST['gender']);
        $dob            = trim($_POST['dob']);
        $nationality    = htmlspecialchars(trim($_POST['nationality']));
        $parent_name    = htmlspecialchars(trim($_POST['parent_name']));
        $parent_relation = htmlspecialchars(trim($_POST['parent_relation']));
        $pcorr_address  = htmlspecialchars(trim($_POST['parent_corr_address']));
        $pcorr_pin      = trim($_POST['parent_corr_pin']);
        $perm_address   = htmlspecialchars(trim($_POST['perm_address']));
        $perm_pin       = trim($_POST['perm_pin']);
        $parent_mobile  = trim($_POST['parent_mobile']);
        $email          = trim($_POST['scholar_email']);
        $adhar          = trim($_POST['adhar']);
        $category       = trim($_POST['category']);
        $institute      = htmlspecialchars(trim($_POST['institute_where']));
        $applied_work   = htmlspecialchars(trim($_POST['applied_work']));
        $app_date       = trim($_POST['app_date']);
        $cert_no        = htmlspecialchars(trim($_POST['cert_no']));
        $cert_date      = trim($_POST['cert_date']);

$stmt1 = $con->prepare("
UPDATE scholar_master SET
SCHOLAR_NAME=?,
EMAIL=?
WHERE SCHOLAR_ID=?
");

$stmt1->bind_param("ssi", $scholar_name, $email, $scholar_id);
$stmt1->execute();
$stmt1->close();

$stmt2 = $con->prepare("
UPDATE scholar_personal_details SET
GENDER=?,
DOB=?,
NATIONALITY=?,
PARENT_NAME=?,
PARENT_RELATIONSHIP=?,
PARENT_CORR_ADDRESS=?,
PARENT_CORR_PIN=?,
SCHOLAR_PERM_ADDRESS=?,
SCHOLAR_PERM_PIN=?,
PARENT_MOBILE=?,
ADHAR_NUMBER=?,
ADMISSION_CATEGORY=?,
INSTITUTE_WHERE=?,
APPLIED_FOR_WORK=?,
APPLICATION_DATE=?,
ELIGIBILITY_CERT_NO=?,
ELIGIBILITY_CERT_DATE=?,
SCHOLAR_IMAGEURL=?
WHERE SCHOLAR_ID=?
");

$stmt2->bind_param(
"ssssssssssssssssssi",
$gender,
$dob,
$nationality,
$parent_name,
$parent_relation,
$pcorr_address,
$pcorr_pin,
$perm_address,
$perm_pin,
$parent_mobile,
$adhar,
$category,
$institute,
$applied_work,
$app_date,
$cert_no,
$cert_date,
$imgPath,
$scholar_id
);

        if ($stmt2->execute()) {

            // ===== WHEN DATA CHANGED =====
            if ($stmt2->affected_rows > 0) {

                // Save clicked
                if (isset($_POST['save'])) {
                    $success = "Personal Details Saved Successfully";
                }

                // Save & Next clicked
                if (isset($_POST['save_next'])) {

                    echo "
<script>
alert('Scholar Details Updated. Redirecting to next page.');
window.location.href='Scholar_Dashboard.php?view=update&page=education';
</script>
";
                    exit;
                }
            }
            // ===== WHEN NO DATA CHANGED =====
            else {
                $error = "Please first change data, then save.";
            }
        } else {
            $error = "Database Update Failed";
        }

        $stmt2->close();
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .main-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
        padding: 20px;
    }

    .section-box {
        background: #f8f9fb;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        margin-bottom: 15px;
    }

    label {
        font-weight: 600;
        font-size: 14px;
    }

    .form-control,
    .form-select {
        height: 38px;
        font-size: 14px;
    }

    textarea.form-control {
        height: auto;
    }

    .btn-area {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .btn-right {
        display: flex;
        gap: 10px;
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

    .btn-gradient:hover {
        background: linear-gradient(90deg, #10ee40, #076fde);
        color: white;
    }

    .photo-box {
        width: 120px;
        height: 120px;
        border: 2px dashed #999;
        border-radius: 50%;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        background: #f8f9fa;
    }

    .photo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-box .overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        opacity: 0;
        transition: 0.3s;
    }

    .photo-box:hover .overlay {
        opacity: 1;
    }
</style>

<div class="main-card">

    <h5 class="text-primary mb-3">Personal Information</h5>

    <?php if (!empty($success)) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>

    <?php if (!empty($error)) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php } ?>


    <form method="post" enctype="multipart/form-data">

        <!-- BASIC INFO -->
        <div class="section-box">
            <div class="row g-2">

                <div class="col-md-12 text-center">

                    <!-- Hidden File Input -->
                    <input type="file" name="scholar_image" id="scholar_image"
                        accept=".jpg,.jpeg,.png" hidden>

                    <!-- Preview Box -->
                    <div class="photo-box mx-auto"
                        onclick="document.getElementById('scholar_image').click()">

                        <?php if (!empty($data['SCHOLAR_IMAGEURL'])) { ?>
                            <img id="previewImg" src="<?= $data['SCHOLAR_IMAGEURL']; ?>">
                        <?php } else { ?>
                            <img id="previewImg" src="https://cdn-icons-png.flaticon.com/512/847/847969.png">
                        <?php } ?>

                        <div class="overlay">Click to Upload</div>
                    </div>

                    <small class="text-danger fw-semibold">
                        <?= $imageErr ?? ""; ?>
                    </small>
                </div>


                <div class="col-md-4">
                    <label>Scholar Name</label>

                    <input type="text"
                        name="scholar_name"
                        id="scholar_name"
                        class="form-control"
                        value="<?php echo $_POST['scholar_name'] ?? $data['SCHOLAR_NAME']; ?>">

                    <small id="scholar_name_error" class="text-danger fw-semibold">
                        <?php echo $scholarNameErr ?? ""; ?>
                    </small>
                </div>



                <div class="col-md-4">
                    <label>Gender</label>

                    <select name="gender"
                        id="gender"
                        class="form-select">

                        <option value="">Select</option>

                        <option value="Male"
                            <?php
                            if (($_POST['gender'] ?? $data['GENDER']) == "Male")
                                echo "selected";
                            ?>>
                            Male
                        </option>

                        <option value="Female"
                            <?php
                            if (($_POST['gender'] ?? $data['GENDER']) == "Female")
                                echo "selected";
                            ?>>
                            Female
                        </option>

                    </select>

                    <small id="gender_error" class="text-danger fw-semibold">
                        <?php echo $genderErr ?? ""; ?>
                    </small>

                </div>


                <div class="col-md-4">
                    <label>DOB</label>

                    <input type="date"
                        name="dob"
                        id="dob"
                        class="form-control"
                        value="<?php echo $_POST['dob'] ?? $data['DOB']; ?>">

                    <small id="dob_error" class="text-danger fw-semibold">
                        <?php echo $dobErr ?? ""; ?>
                    </small>

                </div>


                <div class="col-md-4">
                    <label>Age</label>
                    <input type="text" id="age" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                    <label>Nationality</label>

                    <input type="text"
                        name="nationality"
                        id="nationality"
                        class="form-control"
                        value="<?php echo $_POST['nationality'] ?? $data['NATIONALITY']; ?>">

                    <small id="nationality_error" class="text-danger fw-semibold">
                        <?php echo $nationErr ?? ""; ?>
                    </small>

                </div>


            </div>
        </div>

        <!-- PARENT INFO -->
        <div class="section-box">
            <h6 class="text-primary mb-2">Parent / Guardian Information</h6>
            <div class="row g-2">

                <div class="col-md-4">
                    <label>Parent/Guardian Name</label>

                    <input type="text"
                        name="parent_name"
                        id="parent_name"
                        class="form-control"
                        value="<?php echo $_POST['parent_name'] ?? $data['PARENT_NAME']; ?>">

                    <small id="parent_name_error" class="text-danger fw-semibold">
                        <?php echo $parentNameErr ?? ""; ?>
                    </small>

                </div>


                <div class="col-md-4">
                    <label>Relationship</label>

                    <input type="text"
                        name="parent_relation"
                        id="parent_relation"
                        class="form-control"
                        value="<?php echo $_POST['parent_relation'] ?? $data['PARENT_RELATIONSHIP']; ?>">

                    <small id="relation_error" class="text-danger fw-semibold">
                        <?php echo $relationErr ?? ""; ?>
                    </small>

                </div>


                <div class="col-md-4">
                    <label>Parent Mobile</label>

                    <input type="text"
                        name="parent_mobile"
                        id="parent_mobile"
                        maxlength="10"
                        class="form-control"
                        value="<?php echo $_POST['parent_mobile'] ?? $data['PARENT_MOBILE']; ?>">

                    <small id="parent_mobile_error" class="text-danger fw-semibold">
                        <?php echo $parentMobileErr ?? ""; ?>
                    </small>

                </div>


                <div class="col-md-6">
                    <label>Correspondence Address</label>

                    <textarea name="parent_corr_address"
                        id="parent_corr_address"
                        class="form-control"
                        rows="3"><?php
                                    echo $_POST['parent_corr_address'] ?? $data['PARENT_CORR_ADDRESS'];
                                    ?></textarea>

                    <small id="pcorr_error" class="text-danger fw-semibold">
                        <?php echo $pcorrErr ?? ""; ?>
                    </small>

                </div>


                <div class="col-md-3">
                    <label>Pin</label>

                    <input type="text"
                        name="parent_corr_pin"
                        id="parent_corr_pin"
                        maxlength="6"
                        class="form-control"
                        value="<?php echo $_POST['parent_corr_pin'] ?? $data['PARENT_CORR_PIN']; ?>">

                    <small id="pcpin_error" class="text-danger fw-semibold">
                        <?php echo $pcpinErr ?? ""; ?>
                    </small>

                </div>


            </div>
        </div>

        <!-- PERMANENT -->
        <div class="section-box">
            <h6 class="text-primary mb-2">Permanent Address</h6>
            <div class="row g-2">

                <div class="col-md-6">
                    <label>Address</label>

                    <textarea name="perm_address"
                        id="perm_address"
                        class="form-control"
                        rows="3"><?php
                                    echo $_POST['perm_address'] ?? $data['SCHOLAR_PERM_ADDRESS'];
                                    ?></textarea>

                    <small id="perm_address_error" class="text-danger fw-semibold">
                        <?php echo $permAddrErr ?? ""; ?>
                    </small>

                </div>


                <div class="col-md-3">
                    <label>Pin</label>

                    <input type="text"
                        name="perm_pin"
                        id="perm_pin"
                        maxlength="6"
                        class="form-control"
                        value="<?php echo $_POST['perm_pin'] ?? $data['SCHOLAR_PERM_PIN']; ?>">

                    <small id="perm_pin_error" class="text-danger fw-semibold">
                        <?php echo $permPinErr ?? ""; ?>
                    </small>

                </div>


            </div>
        </div>

        <!-- OTHER -->
        <div class="section-box">
            <h6 class="text-primary mb-2">Other Details</h6>
            <div class="row g-2">

                <div class="col-md-4">
                    <label>Email</label>

                    <input type="email"
                        name="scholar_email"
                        id="scholar_email"
                        class="form-control"
                        value="<?php echo $_POST['scholar_email'] ?? $data['EMAIL']; ?>">

                    <small id="email_error" class="text-danger fw-semibold">
                        <?php echo $emailErr ?? ""; ?>
                    </small>

                </div>

                <div class="col-md-4">
                    <label>Aadhar Number</label>

                    <input type="text"
                        name="adhar"
                        id="adhar"
                        class="form-control"
                        maxlength="12"
                        value="<?php echo $_POST['adhar'] ?? $data['ADHAR_NUMBER']; ?>">

                    <small id="adhar_error" class="text-danger fw-semibold">
                        <?php echo $aadharErr ?? ""; ?>
                    </small>

                </div>


                <div class="col-md-4">
                    <label>Admission Category</label>

                    <select name="category" id="category" class="form-select">
                        <option value="">-- Select Category --</option>

                        <option value="General"
                            <?php
                            if (($_POST['category'] ?? $data['ADMISSION_CATEGORY']) == "General") echo "selected";
                            ?>>
                            General
                        </option>

                        <option value="SC"
                            <?php
                            if (($_POST['category'] ?? $data['ADMISSION_CATEGORY']) == "SC") echo "selected";
                            ?>>
                            SC
                        </option>

                        <option value="ST"
                            <?php
                            if (($_POST['category'] ?? $data['ADMISSION_CATEGORY']) == "ST") echo "selected";
                            ?>>
                            ST
                        </option>

                        <option value="PH"
                            <?php
                            if (($_POST['category'] ?? $data['ADMISSION_CATEGORY']) == "PH") echo "selected";
                            ?>>
                            PH
                        </option>

                        <option value="EWS"
                            <?php
                            if (($_POST['category'] ?? $data['ADMISSION_CATEGORY']) == "EWS") echo "selected";
                            ?>>
                            EWS
                        </option>

                    </select>

                    <small id="category_error" class="text-danger fw-semibold">
                        <?php echo $categoryErr ?? ""; ?>
                    </small>

                </div>


                <div class="col-md-6">
                    <label>Institute Where Applicant Process to Work</label>

                    <input type="text"
                        name="institute_where"
                        id="institute_where"
                        class="form-control"
                        value="<?php echo $_POST['institute_where'] ?? ($data['INSTITUTE_WHERE'] ?? ''); ?>">

                    <small id="institute_error" class="text-danger fw-semibold">
                        <?php echo $instituteErr ?? ""; ?>
                    </small>
                </div>


                <div class="col-md-6">
                    <label>Applied For Work</label>

                    <input type="text"
                        name="applied_work"
                        id="applied_work"
                        class="form-control"
                        value="<?php echo $_POST['applied_work'] ?? ($data['APPLIED_FOR_WORK'] ?? ''); ?>">

                    <small id="applied_work_error" class="text-danger fw-semibold">
                        <?php echo $appliedWorkErr ?? ""; ?>
                    </small>
                </div>


                <div class="col-md-4">
                    <label>Application Date</label>

                    <input type="date"
                        name="app_date"
                        id="app_date"
                        class="form-control"
                        value="<?php echo $_POST['app_date'] ?? ($data['APPLICATION_DATE'] ?? ''); ?>">

                    <small id="app_date_error" class="text-danger fw-semibold">
                        <?php echo $appDateErr ?? ""; ?>
                    </small>
                </div>


                <div class="col-md-4">
                    <label>Eligibility Certificate No</label>

                    <input type="text"
                        name="cert_no"
                        id="cert_no"
                        class="form-control"
                        value="<?php echo $_POST['cert_no'] ?? ($data['ELIGIBILITY_CERT_NO'] ?? ''); ?>">

                    <small id="cert_no_error" class="text-danger fw-semibold">
                        <?php echo $certNoErr ?? ""; ?>
                    </small>
                </div>


                <div class="col-md-4">
                    <label>Certificate Date</label>

                    <input type="date"
                        name="cert_date"
                        id="cert_date"
                        class="form-control"
                        value="<?php echo $_POST['cert_date'] ?? ($data['ELIGIBILITY_CERT_DATE'] ?? ''); ?>">

                    <small id="cert_date_error" class="text-danger fw-semibold">
                        <?php echo $certDateErr ?? ""; ?>
                    </small>
                </div>


            </div>
        </div>

        <!-- BUTTONS -->
        <div class="btn-area">

            <button type="button" class="btn btn-secondary"
                onclick="window.location='Scholar_Dashboard.php?view=update&page=academic'">
                Back
            </button>

            <div class="btn-right">

                <button type="submit" name="save" class="btn btn-success">Save</button>

                <button type="submit" name="save_next" class="btn btn-outline-success">Save & Next</button>

                <button type="button" class="btn btn-primary"
                    onclick="window.location='Scholar_Dashboard.php?view=update&page=education'">
                    Next
                </button>


            </div>
        </div>

    </form>
</div>

<script>
    function calculateAge(dob) {
        let birth = new Date(dob);
        let today = new Date();
        let age = today.getFullYear() - birth.getFullYear();
        let m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age;
    }

    document.getElementById("dob").addEventListener("change", function() {
        document.getElementById("age").value = calculateAge(this.value);
    });

    window.onload = function() {
        let d = document.getElementById("dob").value;
        if (d != "") {
            document.getElementById("age").value = calculateAge(d);
        }
    }
</script>

<script>
    document.getElementById("scholar_image").addEventListener("change", function() {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("previewImg").src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>