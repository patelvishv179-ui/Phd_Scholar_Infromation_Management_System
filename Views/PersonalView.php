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

    <?php
if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show">'
        . $_SESSION['success'] .
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';

    unset($_SESSION['success']); // remove after showing
}
?>

  <?php
if (!empty($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
        . $_SESSION['error'] .
        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';

    unset($_SESSION['error']);
}
?>

    <form method="post" onsubmit="return true;" enctype="multipart/form-data">

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

                        <?php if (!empty($perdata['SCHOLAR_IMAGEURL'])) { ?>
                            <img id="previewImg" src="<?= $perdata['SCHOLAR_IMAGEURL']; ?>">
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
                        class="form-control"
                        value="<?php echo $perdata['SCHOLAR_NAME']; ?>" readonly>
                   
                </div>

                <div class="col-md-4">
                    <label>Gender</label>

                    <select name="gender"
                        id="gender"
                        class="form-select">

                        <option value="">Select</option>

                        <option value="Male"
                            <?php
                            if (($_POST['gender'] ?? $perdata['GENDER']) == "Male")
                                echo "selected";
            ?>>
                            Male
                        </option>

                        <option value="Female"
                            <?php
                            if (($_POST['gender'] ?? $perdata['GENDER']) == "Female")
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
                        value="<?php echo $_POST['dob'] ?? $perdata['DOB']; ?>">

                    <small id="dob_error" class="text-danger fw-semibold">
                        <?php echo $dobErr ?? ""; ?>
                    </small>

                </div>


                <div class="col-md-4">
                    <label>Age</label>
                    <input type="text" id="age" class="form-control" placeholder="Auto Calculated From DOB" readonly>
                </div>

                <div class="col-md-4">
                    <label>Nationality</label>

                    <input type="text"
                        name="nationality"
                        id="nationality"
                        class="form-control"
                        value="<?php echo $_POST['nationality'] ?? $perdata['NATIONALITY']; ?>">

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
                        value="<?php echo $_POST['parent_name'] ?? $perdata['PARENT_NAME']; ?>">

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
                        value="<?php echo $_POST['parent_relation'] ?? $perdata['PARENT_RELATIONSHIP']; ?>">

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
                        value="<?php echo $_POST['parent_mobile'] ?? $perdata['PARENT_MOBILE']; ?>">

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
                                    echo $_POST['parent_corr_address'] ?? $perdata['PARENT_CORR_ADDRESS'];
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
                        value="<?php echo $_POST['parent_corr_pin'] ?? $perdata['PARENT_CORR_PIN']; ?>">

                    <small id="pcpin_error" class="text-danger fw-semibold">
                        <?php echo $pcpinErr ?? ""; ?>
                    </small>

                </div>

                


            </div>
        </div>

        <div class="col-md-12 ms-3 mb-3">
    <input type="checkbox" id="same_address">
    <label for="same_address" class="ms-1">Same as Parent Correspondence Address</label>
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
                                    echo $_POST['perm_address'] ?? $perdata['SCHOLAR_PERM_ADDRESS'];
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
                        value="<?php echo $_POST['perm_pin'] ?? $perdata['SCHOLAR_PERM_PIN']; ?>">

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
                        class="form-control"
                        value="<?php echo $perdata['EMAIL'] ?>" readonly>
                </div>

                <div class="col-md-4">
                    <label>Aadhar Number</label>

                    <input type="text"
                        name="adhar"
                        id="adhar"
                        class="form-control"
                        maxlength="12"
                        value="<?= htmlspecialchars($_POST['adhar'] ?? ($perdata['ADHAR_NUMBER'] ?? '')) ?>">

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
                            if (($_POST['category'] ?? $perdata['ADMISSION_CATEGORY']) == "General") echo "selected";
                            ?>>
                            General
                        </option>

                        <option value="SC"
                            <?php
                            if (($_POST['category'] ?? $perdata['ADMISSION_CATEGORY']) == "SC") echo "selected";
                            ?>>
                            SC
                        </option>

                        <option value="ST"
                            <?php
                            if (($_POST['category'] ?? $perdata['ADMISSION_CATEGORY']) == "ST") echo "selected";
                            ?>>
                            ST
                        </option>

                        <option value="PH"
                            <?php
                            if (($_POST['category'] ?? $perdata['ADMISSION_CATEGORY']) == "PH") echo "selected";
                            ?>>
                            PH
                        </option>

                        <option value="EWS"
                            <?php
                            if (($_POST['category'] ?? $perdata['ADMISSION_CATEGORY']) == "EWS") echo "selected";
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
                        value="<?php echo $_POST['institute_where'] ?? ($perdata['INSTITUTE_WHERE'] ?? ''); ?>">

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
                        value="<?php echo $_POST['applied_work'] ?? ($perdata['APPLIED_FOR_WORK'] ?? ''); ?>">

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
                        value="<?php echo $_POST['app_date'] ?? ($perdata['APPLICATION_DATE'] ?? ''); ?>">

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
                        value="<?php echo $_POST['cert_no'] ?? ($perdata['ELIGIBILITY_CERT_NO'] ?? ''); ?>">

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
                        value="<?php echo $_POST['cert_date'] ?? ($perdata['ELIGIBILITY_CERT_DATE'] ?? ''); ?>">

                    <small id="cert_date_error" class="text-danger fw-semibold">
                        <?php echo $certDateErr ?? ""; ?>
                    </small>
                </div>


            </div>
        </div>

        <!-- BUTTONS -->
        <div class="btn-area">

            <button type="submit" class="btn btn-secondary"
                name="per_back_btn" formnovalidate
                >Back
            </button>

            <div class="btn-right">

                <button type="submit" name="per_save_btn" value="save" class="btn btn-success">Save</button>

                <button type="submit" name="per_saveandnext_btn"value="save_next" class="btn btn-outline-success" onclick="return confirm('Save Details and go to next page?')">Save & Next</button>

                <button type="submit" value="next"  class="btn btn-primary"
                    name="per_next_btn" formnovalidate 
                >Next
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

<script>
setTimeout(() => {
    let alert = document.querySelector('.alert');
    if(alert){
        alert.style.display = 'none';
    }
}, 1000);
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const sameCheckbox = document.getElementById("same_address");
    const corrAddress = document.getElementById("parent_corr_address");
    const corrPin = document.getElementById("parent_corr_pin");
    const permAddress = document.getElementById("perm_address");
    const permPin = document.getElementById("perm_pin");
    // Function to copy values
    function copyAddress() {
        permAddress.value = corrAddress.value;
        permPin.value = corrPin.value;
    }
    // Checkbox change event
    sameCheckbox.addEventListener("change", function () {
        if (this.checked) {
            copyAddress();
            // Lock fields
            permAddress.readOnly = true;
            permPin.readOnly = true;
        } else {
            // Unlock fields
            permAddress.readOnly = false;
            permPin.readOnly = false;
            // Optional: clear fields
            permAddress.value = "";
            permPin.value = "";
        }
    });
    // Live sync (best UX)
    corrAddress.addEventListener("input", function () {
        if (sameCheckbox.checked) copyAddress();
    });
    corrPin.addEventListener("input", function () {
        if (sameCheckbox.checked) copyAddress();
    });
});
</script>

   <!-- ================= JS VALIDATION FILES (AUTO VERSIONING) ================= -->

<!-- Scholar Image Validation -->
<script src="Assets/JSIncludes/Scholar_Image_Validation.js?v=<?= filemtime('Assets/JSIncludes/Scholar_Image_Validation.js') ?>"></script>

<!-- Gender Validation -->
<script src="Assets/JSIncludes/Gender_Validation.js?v=<?= filemtime('Assets/JSIncludes/Gender_Validation.js') ?>"></script>

<!-- Date of Birth Validation -->
<script src="Assets/JSIncludes/DOB_Validation.js?v=<?= filemtime('Assets/JSIncludes/DOB_Validation.js') ?>"></script>

<!-- Nationality Validation -->
<script src="Assets/JSIncludes/Nationality_Validation.js?v=<?= filemtime('Assets/JSIncludes/Nationality_Validation.js') ?>"></script>

<!-- Parent / Guardian Name Validation -->
<script src="Assets/JSIncludes/Parent_Name_Validation.js?v=<?= filemtime('Assets/JSIncludes/Parent_Name_Validation.js') ?>"></script>

<!-- Parent Relationship Validation -->
<script src="Assets/JSIncludes/Parent_Relationship_Validation.js?v=<?= filemtime('Assets/JSIncludes/Parent_Relationship_Validation.js') ?>"></script>

<!-- Parent Mobile Validation -->
<script src="Assets/JSIncludes/Parent_Mobile_Validation.js?v=<?= filemtime('Assets/JSIncludes/Parent_Mobile_Validation.js') ?>"></script>

<!-- Parent Correspondence Address Validation -->
<script src="Assets/JSIncludes/Parent_Corr_Address_Validation.js?v=<?= filemtime('Assets/JSIncludes/Parent_Corr_Address_Validation.js') ?>"></script>

<!-- Parent Correspondence PIN Validation -->
<script src="Assets/JSIncludes/Parent_Corr_Pin_Validation.js?v=<?= filemtime('Assets/JSIncludes/Parent_Corr_Pin_Validation.js') ?>"></script>

<!-- Permanent Address Validation -->
<script src="Assets/JSIncludes/Permanent_Address_Validation.js?v=<?= filemtime('Assets/JSIncludes/Permanent_Address_Validation.js') ?>"></script>

<!-- Permanent PIN Validation -->
<script src="Assets/JSIncludes/Permanent_Pin_Validation.js?v=<?= filemtime('Assets/JSIncludes/Permanent_Pin_Validation.js') ?>"></script>

<!-- Aadhar Number Validation -->
<script src="Assets/JSIncludes/Aadhar_Validation.js?v=<?= filemtime('Assets/JSIncludes/Aadhar_Validation.js') ?>"></script>

<!-- Category Validation -->
<script src="Assets/JSIncludes/Category_Validation.js?v=<?= filemtime('Assets/JSIncludes/Category_Validation.js') ?>"></script>

<!-- Institute Validation -->
<script src="Assets/JSIncludes/Institute_Validation.js?v=<?= filemtime('Assets/JSIncludes/Institute_Validation.js') ?>"></script>

<!-- Applied Research Work Validation -->
<script src="Assets/JSIncludes/Applied_Work_Validation.js?v=<?= filemtime('Assets/JSIncludes/Applied_Work_Validation.js') ?>"></script>

<!-- Application Date Validation -->
<script src="Assets/JSIncludes/Application_Date_Validation.js?v=<?= filemtime('Assets/JSIncludes/Application_Date_Validation.js') ?>"></script>

<!-- Eligibility Certificate Number Validation -->
<script src="Assets/JSIncludes/Certificate_No_Validation.js?v=<?= filemtime('Assets/JSIncludes/Certificate_No_Validation.js') ?>"></script>

<!-- Eligibility Certificate Date Validation -->
<script src="Assets/JSIncludes/Certificate_Date_Validation.js?v=<?= filemtime('Assets/JSIncludes/Certificate_Date_Validation.js') ?>"></script>

<!-- ================= END OF VALIDATION FILES ================= -->