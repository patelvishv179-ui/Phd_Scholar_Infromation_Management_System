    <?php

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['SCHOLAR_EMAIL'])) {
        header("Location: login.php");
        exit;
    }

    //Back Button
    if (isset($_POST['per_back_btn'])) {
        header("Location: Complete_Profile.php?page=academic");
        exit;
    }

    //Next Button
    if (isset($_POST['per_next_btn'])) {
        header("Location: Complete_Profile.php?page=education");
        exit;
    }

    $scholar_id = $_SESSION['user_id'];

    $stmt = $con->prepare("
    SELECT s.SCHOLAR_NAME, s.EMAIL, p.*
    FROM scholar_master s
    LEFT JOIN Scholar_Personal_Details p
    ON s.SCHOLAR_ID = p.SCHOLAR_ID
    WHERE s.SCHOLAR_ID = ?
    ");
    $stmt->bind_param("i", $scholar_id);
    $stmt->execute();
    $perdata = $stmt->get_result()->fetch_assoc();

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

if(
    !empty($genderErr) ||
    !empty($dobErr) ||
    !empty($nationErr) ||
    !empty($parentNameErr) ||
    !empty($relationErr) ||
    !empty($parentMobileErr) ||
    !empty($pcorrErr) ||
    !empty($pcpinErr) ||
    !empty($permAddrErr) ||
    !empty($permPinErr) ||
    !empty($aadharErr) ||
    !empty($categoryErr) ||
    !empty($instituteErr) ||
    !empty($appliedWorkErr) ||
    !empty($appDateErr) ||
    !empty($certNoErr) ||
    !empty($certDateErr) ||
    !empty($imageErr)
){

    header("Location: Complete_Profile.php?page=personal");
    exit;

}



    // ================= SAVE BUTTON =================
    if (isset($_POST['per_save_btn'])) {

    // ===== CHECK EMPTY FIELDS =====
    if (
    empty($_POST['gender']) ||
    empty($_POST['dob']) ||
    empty($_POST['nationality']) ||
    empty($_POST['parent_name']) ||
    empty($_POST['parent_relation']) ||
    empty($_POST['parent_mobile']) ||
    empty($_POST['parent_corr_address']) ||
    empty($_POST['parent_corr_pin']) ||
    empty($_POST['perm_address']) ||
    empty($_POST['perm_pin']) ||
    empty($_POST['adhar']) ||
    empty($_POST['category']) ||
    empty($_POST['institute_where']) ||
    empty($_POST['applied_work']) ||
    empty($_POST['app_date']) ||
    empty($_POST['cert_no']) ||
    empty($_POST['cert_date'])
    ) {
    // $_SESSION['error'] = "Please fill all fields first";
    // header("Location: Complete_Profile.php?page=personal");
    // exit;
    }

    // ===== IMAGE UPLOAD =====
    $imgPath = $perdata['SCHOLAR_IMAGEURL'] ?? '';

    if (!empty($_FILES['scholar_image']['name'])) {

    $folder = "Assets/ScholarProfileImages/";
    if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
    }

    $ext = pathinfo($_FILES['scholar_image']['name'], PATHINFO_EXTENSION);
    $filename = $perdata['SCHOLAR_REGISTRATION_NUMBER'] . "." . $ext;

    move_uploaded_file($_FILES['scholar_image']['tmp_name'], $folder . $filename);
    $imgPath = $folder . $filename;
    }

    // ===== SANITIZE =====
    $gender = trim($_POST['gender']);
    $dob = trim($_POST['dob']);
    $nationality = htmlspecialchars(trim($_POST['nationality']));
    $parent_name = htmlspecialchars(trim($_POST['parent_name']));
    $parent_relation = htmlspecialchars(trim($_POST['parent_relation']));
    $pcorr_address = htmlspecialchars(trim($_POST['parent_corr_address']));
    $pcorr_pin = trim($_POST['parent_corr_pin']);
    $perm_address = htmlspecialchars(trim($_POST['perm_address']));
    $perm_pin = trim($_POST['perm_pin']);
    $parent_mobile = trim($_POST['parent_mobile']);
    $adhar = trim($_POST['adhar']);
    $category = trim($_POST['category']);
    $institute = htmlspecialchars(trim($_POST['institute_where']));
    $applied_work = htmlspecialchars(trim($_POST['applied_work']));
    $app_date = trim($_POST['app_date']);
    $cert_no = htmlspecialchars(trim($_POST['cert_no']));
    $cert_date = trim($_POST['cert_date']);

    // ===== CHECK RECORD EXISTS =====
    $check = $con->prepare("SELECT SCHOLAR_ID FROM Scholar_Personal_Details WHERE SCHOLAR_ID=?");
    $check->bind_param("i", $scholar_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows == 0) {

    // 🔥 INSERT
    $stmt = $con->prepare("
    INSERT INTO Scholar_Personal_Details (
    SCHOLAR_ID, GENDER, DOB, NATIONALITY, PARENT_NAME, PARENT_RELATIONSHIP,
    PARENT_CORR_ADDRESS, PARENT_CORR_PIN,
    SCHOLAR_PERM_ADDRESS, SCHOLAR_PERM_PIN,
    PARENT_MOBILE, ADHAR_NUMBER, ADMISSION_CATEGORY,
    INSTITUTE_WHERE, APPLIED_FOR_WORK, APPLICATION_DATE,
    ELIGIBILITY_CERT_NO, ELIGIBILITY_CERT_DATE, SCHOLAR_IMAGEURL
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
    "issssssssssssssssss",
    $scholar_id,
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
    $imgPath
    );
    } else {

    // 🔥 UPDATE
    $stmt = $con->prepare("
    UPDATE Scholar_Personal_Details SET
    GENDER=?, DOB=?, NATIONALITY=?, PARENT_NAME=?, PARENT_RELATIONSHIP=?,
    PARENT_CORR_ADDRESS=?, PARENT_CORR_PIN=?,
    SCHOLAR_PERM_ADDRESS=?, SCHOLAR_PERM_PIN=?,
    PARENT_MOBILE=?, ADHAR_NUMBER=?, ADMISSION_CATEGORY=?,
    INSTITUTE_WHERE=?, APPLIED_FOR_WORK=?, APPLICATION_DATE=?,
    ELIGIBILITY_CERT_NO=?, ELIGIBILITY_CERT_DATE=?, SCHOLAR_IMAGEURL=?
    WHERE SCHOLAR_ID=?
    ");

    $stmt->bind_param(
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
    }

    // ===== EXECUTE =====
    if ($stmt->execute()) {
    $_SESSION['success'] = "Data Saved Successfully";
    } else {
    $_SESSION['error'] = "Error while saving data!";
    }

    $stmt->close();

    header("Location: Complete_Profile.php?page=personal");
    exit;
    }

    //Save & Next Button
    // ================= SAVE & NEXT BUTTON =================
    if (isset($_POST['per_saveandnext_btn'])) {

    $check = $con->prepare("SELECT SCHOLAR_ID FROM Scholar_Personal_Details WHERE SCHOLAR_ID=?");
    $check->bind_param("i", $scholar_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows == 0) {

    $stmt = $con->prepare("
    INSERT INTO Scholar_Personal_Details (
    SCHOLAR_ID, GENDER, DOB, NATIONALITY, PARENT_NAME, PARENT_RELATIONSHIP,
    PARENT_CORR_ADDRESS, PARENT_CORR_PIN,
    SCHOLAR_PERM_ADDRESS, SCHOLAR_PERM_PIN,
    PARENT_MOBILE, ADHAR_NUMBER, ADMISSION_CATEGORY,
    INSTITUTE_WHERE, APPLIED_FOR_WORK, APPLICATION_DATE,
    ELIGIBILITY_CERT_NO, ELIGIBILITY_CERT_DATE, SCHOLAR_IMAGEURL
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
    "issssssssssssssssss",
    $scholar_id,
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
    $imgPath
    );
    }

    // ===== CHECK EMPTY FIELDS =====
    if (
    empty($_POST['gender']) ||
    empty($_POST['dob']) ||
    empty($_POST['nationality']) ||
    empty($_POST['parent_name']) ||
    empty($_POST['parent_relation']) ||
    empty($_POST['parent_mobile']) ||
    empty($_POST['parent_corr_address']) ||
    empty($_POST['parent_corr_pin']) ||
    empty($_POST['perm_address']) ||
    empty($_POST['perm_pin']) ||
    empty($_POST['adhar']) ||
    empty($_POST['category']) ||
    empty($_POST['institute_where']) ||
    empty($_POST['applied_work']) ||
    empty($_POST['app_date']) ||
    empty($_POST['cert_no']) ||
    empty($_POST['cert_date'])
    ) {

    $_SESSION['error'] = "Please fill all fields first";
    header("Location: Complete_Profile.php?page=personal");
    exit;
    }

    // ===== IMAGE =====
    $imgPath = $perdata['SCHOLAR_IMAGEURL'];

    if (!empty($_FILES['scholar_image']['name'])) {
    $folder = "Assets/ScholarProfileImages/";
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    $ext = pathinfo($_FILES['scholar_image']['name'], PATHINFO_EXTENSION);
    $filename = $perdata['SCHOLAR_REGISTRATION_NUMBER'] . "." . $ext;

    move_uploaded_file($_FILES['scholar_image']['tmp_name'], $folder . $filename);
    $imgPath = $folder . $filename;
    }

    // ===== SANITIZE =====
    $gender = trim($_POST['gender']);
    $dob = trim($_POST['dob']);
    $nationality = htmlspecialchars(trim($_POST['nationality']));
    $parent_name = htmlspecialchars(trim($_POST['parent_name']));
    $parent_relation = htmlspecialchars(trim($_POST['parent_relation']));
    $pcorr_address = htmlspecialchars(trim($_POST['parent_corr_address']));
    $pcorr_pin = trim($_POST['parent_corr_pin']);
    $perm_address = htmlspecialchars(trim($_POST['perm_address']));
    $perm_pin = trim($_POST['perm_pin']);
    $parent_mobile = trim($_POST['parent_mobile']);
    $adhar = trim($_POST['adhar']);
    $category = trim($_POST['category']);
    $institute = htmlspecialchars(trim($_POST['institute_where']));
    $applied_work = htmlspecialchars(trim($_POST['applied_work']));
    $app_date = trim($_POST['app_date']);
    $cert_no = htmlspecialchars(trim($_POST['cert_no']));
    $cert_date = trim($_POST['cert_date']);

    // ===== UPDATE =====
    $stmt = $con->prepare("
    UPDATE Scholar_Personal_Details SET
    GENDER=?, DOB=?, NATIONALITY=?, PARENT_NAME=?, PARENT_RELATIONSHIP=?,
    PARENT_CORR_ADDRESS=?, PARENT_CORR_PIN=?,
    SCHOLAR_PERM_ADDRESS=?, SCHOLAR_PERM_PIN=?,
    PARENT_MOBILE=?, ADHAR_NUMBER=?, ADMISSION_CATEGORY=?,
    INSTITUTE_WHERE=?, APPLIED_FOR_WORK=?, APPLICATION_DATE=?,
    ELIGIBILITY_CERT_NO=?, ELIGIBILITY_CERT_DATE=?, SCHOLAR_IMAGEURL=?
    WHERE SCHOLAR_ID=?
    ");

    $stmt->bind_param(
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

    if ($stmt->execute()) {

    // ===== DATA CHANGED =====
    if ($stmt->affected_rows > 0) {

    header("Location: Complete_Profile.php?page=education");
    exit;
    }
    // ===== NO CHANGE =====
    else {

    $_SESSION['error'] = "Please first change data, then go next.";

    header("Location: Complete_Profile.php?page=personal");
    exit;
    }
    } else {

    $_SESSION['error'] = "Something went wrong! Please try again.";

    header("Location: Complete_Profile.php?page=personal");
    exit;
    }

    $stmt->close();
    }

    ?>