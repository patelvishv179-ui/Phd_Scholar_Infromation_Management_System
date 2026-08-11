    <?php

    session_start();


    $msg = $_SESSION['msg'] ?? "";
    $old = $_SESSION['old'] ?? [];
    $errors = $_SESSION['errors'] ?? [];


    require_once "Assets/Config/Connection.php";

    /* =====================================
        AJAX : Load Subjects
        ===================================== */

    if (isset($_GET['action']) && $_GET['action'] == "getSubjects") {
        $fid = $_GET['faculty_id'];

        $stmt = $con->prepare(
            "SELECT subject_id, subject_name 
                FROM Subject_Available 
                WHERE faculty_id=?"
        );
        $stmt->bind_param("i", $fid);
        $stmt->execute();
        $res = $stmt->get_result();

        echo "<option value=''>-- Select Subject --</option>";
        while ($row = $res->fetch_assoc()) {
            echo "<option value='" . $row['subject_id'] . "'>" . $row['subject_name'] . "</option>";
        }
        exit;
    }
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Scholar Registration</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

        <link rel="stylesheet" href="Assets/CSSIncludes/scholarregistrationstyle.css">
    </head>

    <body>

        <?php include "Assets/Helpers/loader.php"; ?>

        <div class="container my-3 mb-3">
            <div class="row justify-content-center">

                <div class="col-12 col-sm-10 col-md-10 col-lg-6">

                    <div class="main-card">

                        <div class="header-area">
                            <img src="Assets/Logo.svg" class="img-fluid">
                            <div class="header-center">
                                <h4>Ph.D Scholar Registration Form</h4>
                                <p>Hemchandracharya North Gujarat University</p>
                            </div>
                            <img src="Assets/Logo.svg" class="img-fluid">
                        </div>

                        <div class="p-3">

                            <div class="section-box">

                                <?php echo $msg ?>

                                <form method="POST" action="Registration_Save.php">

                                    <!-- Registration Date Field -->

                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-calendar-check"></i>&nbsp;&nbsp;Registration Date
                                    </label>

                                    <input
                                        type="date"
                                        name="registration_date"
                                        id="registration_date"
                                        class="form-control"
                                        value="<?= htmlspecialchars($old['registration_date'] ?? date('Y-m-d')) ?>"
                                        required>

                                    <div class="text-danger mt-1 error-text">
                                        <?= $errors['registration_date'] ?? '' ?>
                                    </div>


                                    <!-- Scholar Name Field -->

                                    <i class="bi bi-person"></i>
                                    <label class="form-label fw-semibold">Scholar Name:</label>

                                    <input
                                        type="text"
                                        name="scholar_name"
                                        id="scholar_name"
                                        class="form-control"
                                        value="<?= htmlspecialchars($old['scholar_name'] ?? '') ?>"
                                        required>

                                    <div class="text-danger mt-1 errortext" id="scholarNameError">
                                        <?= $errors['scholar_name'] ?? '' ?>
                                    </div>

                                    <!-- Mobile Number Field -->

                                    <i class="bi bi-phone"></i>
                                    <label class="form-label fw-semibold">Mobile Number</label>
                                    <input
                                    type="text"
                                    name="mobile_number"
                                    id="mobile_number"
                                    class="form-control mb-1"
                                    placeholder="10-digit Mobile Number"
                                    maxlength="10"
                                    inputmode="numeric"
                                    required
                                    value="<?= htmlspecialchars($old['mobile_number'] ?? '') ?>">

                                    <div class="text-danger mt-1 errortext" id="mobileError">
                                        <?= $errors['mobile_number'] ?? '' ?>
                                    </div>

                                    <!-- Email Adrress Field -->

                                    <i class="bi bi-envelope-open"></i>
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control"
                                        placeholder="example@email.com"
                                        required
                                        value="<?= htmlspecialchars($old['email'] ?? '') ?>">

                                    <div class="text-danger mt-1 errortext" id="emailError">
                                        <?= $errors['email'] ?? '' ?>
                                    </div>

                                    <!-- Password Field -->

                                    <i class="bi bi-shield-lock"></i>
                                    <label class="form-label fw-semibold">Password</label>

                                    <div class="input-group">
                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            class="form-control"
                                            placeholder="Create Password"
                                            required>

                                        <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                                            <i class="bi bi-eye-slash" id="eyeIcon"></i>
                                        </span>
                                    </div>

                                    <div class="text-danger mt-1 errortext" id="passwordError">
                                        <?= $errors['password'] ?? '' ?>
                                    </div>

                                    <!--Confirm Password Field -->
                                    <i class="bi bi-check-circle"></i>
                                    <label class="form-label fw-semibold">Confirm Password</label>

                                    <div class="input-group mb-2">
                                        <input type="password"
                                            id="cpassword"
                                            name="cpassword"
                                            class="form-control"
                                            placeholder="First please fill Password field"
                                            disabled
                                            required>
                                    </div>

                                    <div class="text-danger mt-1 errortext" id="confirmPasswordError">
                                        <?= $errors['cpassword'] ?? '' ?>
                                    </div>

                                    <!-- Faculty -->

                                    <i class="bi bi-mortarboard"></i>
                                    <label class="form-label fw-semibold">Faculty</label>
                                    <select name="faculty"
                                        id="faculty"
                                        class="form-select mb-2"
                                        required
                                        data-old="<?= htmlspecialchars($old['faculty'] ?? '') ?>">

                                        <option value="">-- Select Faculty --</option>

                                        <?php
                                        $fq = mysqli_query($con, "SELECT * FROM faculty_available");
                                        while ($f = mysqli_fetch_assoc($fq)) {

                                            $selected = (($old['faculty'] ?? '') == $f['FACULTY_ID']) ? "selected" : "";
                                        ?>
                                            <option value="<?= $f['FACULTY_ID']; ?>" <?= $selected; ?>>
                                                <?= $f['FACULTY_NAME']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <!-- Subject -->

                                    <i class="bi bi-book"></i>
                                    <label class="form-label fw-semibold">Subject</label>
                                    <select name="subject"
                                        id="subject"
                                        class="form-select mb-3"
                                        required
                                        disabled
                                        data-old="<?= htmlspecialchars($old['subject'] ?? '') ?>">

                                        <option value="">First Select Faculty</option>
                                    </select>

                                    <button type="submit"
                                        id="registerBtn"
                                        name="btnSubmit"
                                        class="btn btn-success w-100"
                                        onclick="showLoader()"
                                        >
                                        <i class="bi bi-person-plus"></i> Register As Scholar
                                    </button>

                                    <div class="login-link fw-semibold fs-6"> Already have an account? <a href="login.php" onclick="showLoader()">Login</a> </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Old Value of Faculty and Subject With Json-->
            <script>
                const OLD_FACULTY = <?= json_encode($old['faculty'] ?? "") ?>;
                const OLD_SUBJECT = <?= json_encode($old['subject'] ?? "") ?>;
            </script>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

            <!-- Scholar Name Validation-->
            <script src="./Assets/JSIncludes/Scholar_Name_Validation.js"></script>

            <!-- Mobile Validation-->
            <script src="./Assets/JSIncludes/Mobile_Validation.js"></script>

            <!-- Email Validation-->
            <script src="./Assets/JSIncludes/Email_Validation.js"></script>

            <!-- Password Validation-->
            <script src="./Assets/JSIncludes/Password_Validation.js?v=3"></script>

            <!-- Confirm Password Validation-->
            <script src="./Assets/JSIncludes/Confirm_Password_Validation.js"></script>

            <!-- Faculty + Subject Validation-->
            <script src="./Assets/JSIncludes/Faculty_subject_validation.js"></script>

            <!-- Loader -->
            <script src="Assets/Helpers/loader.js?v=<?= filemtime('Assets/Helpers/loader.js') ?>"></script>
   
    <!-- Script File for Alert DisAppear -->

    <script>
        setTimeout(() => {
            let alert = document.querySelector('.alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 2000);
    </script>

        </body>

    </html>