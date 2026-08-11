<?php
ob_start();
session_start();

require_once "Assets/Config/Connection.php";

$id = $_SESSION['user_id'];


$stmt = $con->prepare("SELECT * FROM scholar_master WHERE SCHOLAR_ID=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$_SESSION['email'] = $data['EMAIL'];
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholar Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ================= RESPONSIVE DESIGN ================= */

        @media (max-width: 768px) {

            /* Sidebar hide on mobile */
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            /* Content full width */
            .content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }

            /* Dashboard stack vertically */
            .dashboard {
                flex-direction: column;
            }

            /* Profile mini center */
            .profile-mini {
                margin-bottom: 15px;
            }

            /* Sidebar links inline */
            .sidebar a {
                display: inline-block;
                width: 48%;
                margin: 4px 1%;
                text-align: center;
            }

            /* Tabs wrap */
            .profile-tabs,
            .tabs-wrapper {
                flex-wrap: wrap;
            }

            /* Buttons full width */
            .edu-btn-area {
                flex-direction: column;
                gap: 12px;
            }

            .edu-btn-area button,
            .edu-btn-area a {
                width: 100%;
            }

        }

        /* Extra small phones */
        @media (max-width: 480px) {

            .sidebar a {
                width: 100%;
            }

        }

        body {
            background: #f4f6f9;
            margin: 0;
            font-family: Segoe UI;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
            margin-top: -20px;
        }

        .sidebar {
            width: 20%;
            background: #1f2937;
            color: white;
            padding: 25px;

            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }


        .sidebar a {
            color: #d1d5db;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #2563eb;
            color: white;
        }

        .profile-mini {
            text-align: center;
            margin-bottom: 30px;
        }

        .content {
            margin-left: 20%;
            width: 80%;
            padding: 40px;
        }


        .profile-box {
            background: white;
            width: 500px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        .photo-box {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 2px solid #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-tabs {
            display: flex;
            gap: 10px;
            background: #f1f5f9;
            padding: 10px;
            border-radius: 10px;
            position: sticky;
            top: 80px;
            /* IMPORTANT */
            z-index: 1000;
        }

        /* Sticky container */
        .tabs-wrapper {
            position: sticky;
            top: 0;
            background: #f4f6f9;
            padding: 12px;
            border-radius: 10px;
            z-index: 1000;

            display: flex;
            gap: 12px;

            /* space from form */
            margin-bottom: 20px;
        }

        /* Tab buttons */
        .tab-btn {
            padding: 10px 22px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;

            background: white;
            color: #2563eb;
            border: 1px solid #2563eb;

            transition: 0.2s ease;
        }

        .tab-btn:hover {
            background: #2563eb;
            color: white;
        }

        .tab-btn.active {
            background: #2563eb;
            color: white;
        }



        .tab-link {
            padding: 8px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            color: #2563eb;
            background: #ffffff;
            border: 1px solid #c7d2fe;
            transition: 0.3s;
        }

        .tab-link:hover {
            background: #2563eb;
            color: #fff;
        }

        .tab-link.active {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 0 6px rgba(37, 99, 235, 0.4);
        }


        .edu-btn-area {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
        }

        .edu-btn-right {
            display: flex;
            gap: 12px;
        }

        .edu-btn-area button,
        .edu-btn-area a {
            min-width: 130px;
            height: 42px;
            font-weight: 600;
            border-radius: 6px;
        }

        .edu-btn-gradient {
            background: linear-gradient(90deg, #28a745, #007bff);
            color: white;
        }

        .edu-btn-gradient:hover {
            background: linear-gradient(90deg, #10ee40, #076fde);
            color: white;
        }
    </style>

</head>

<body>

    <div class="dashboard">

        <!-- ===== LEFT SIDEBAR ===== -->
        <div class="sidebar">

            <div class="profile-mini">
                <i class="bi bi-person-circle fs-1"></i>
                <h6>Welcome <?= htmlspecialchars($data['SCHOLAR_NAME']); ?></h6>
            </div>

            <a href="Scholar_Dashboard.php?view=profile"
                class="<?php echo (!isset($_GET['view']) || $_GET['view'] == 'profile') ? 'active' : ''; ?>">
                <i class="bi bi-person"></i> View Profile
            </a>

            <a href="Scholar_Dashboard.php?view=update"
                class="<?php echo (isset($_GET['view']) && $_GET['view'] == 'update') ? 'active' : ''; ?>">
                <i class="bi bi-pencil-square"></i> Update Profile
            </a>


            <a href="Assets/PHPIncludes/Logout.php">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>

        </div>

        <!-- ===== RIGHT CONTENT ===== -->
        <div class="content">

            <?php
            // DEFAULT VIEW PROFILE
            if (!isset($_GET['view'])) {
                include("View_Profile.php");
            } else {

                if ($_GET['view'] == "profile") {
                    include("View_Profile.php");
                } elseif ($_GET['view'] == "update") {
                    include("Update_Profile.php");
                }
            }
            ?>

        </div>

    </div>

    <!-- Close Button js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Entarance Sheet Number Validation Script -->
    <script src="Assets/JSIncludes/Entrance_Sheet_Number_Validation.js"></script>

    <!-- Fee Receipt Number Validation Script -->
    <script src="Assets/JSIncludes/Fee_Receipt_Number_Validation.js"></script>

    <!-- Fee Receipt Date Validation Script -->
    <script src="Assets/JSIncludes/Fee_Receipt_Date_Validation.js"></script>

    <!-- Registration Number Validation Script -->
    <script src="Assets/JSIncludes/Regi_Number_Date_Validation.js"></script>

    <!-- Research Title Validation Script -->
    <script src="Assets/JSIncludes/Research_Title_Validation.js"></script>

    <!-- Faculty and Subject Validation Script -->
    <script src="Assets/JSIncludes/Faculty_Subject_Edit_Validation.js?v=2"></script>

    <!-- Guide Validation Script -->
    <script src="Assets/JSIncludes/Guide_Validation.js"></script>

    <!-- Scholar Name Edit Validation -->
    <script src="Assets/JSIncludes/Scholar_Name_Edit_Validation.js"></script>

    <!-- Scholar image Validation Script -->
    <script src="Assets/JSIncludes/Scholar_Image_Validation.js"></script>

    <!-- Gender Validation Script -->
    <script src="Assets/JSIncludes/Gender_Validation.js"></script>

    <!-- DOB Validation Script -->
    <script src="Assets/JSIncludes/DOB_Validation.js"></script>

    <!-- Nationality Validation Script -->
    <script src="Assets/JSIncludes/Nationality_Validation.js"></script>

    <!-- Parent / Guardian Name Validation Script -->
    <script src="Assets/JSIncludes/Parent_Name_Validation.js"></script>

    <!-- Parent Relationship Validation Script -->
    <script src="Assets/JSIncludes/Parent_Relationship_Validation.js"></script>

    <!-- Parent Mobile Validation Script -->
    <script src="Assets/JSIncludes/Parent_Mobile_Validation.js"></script>

    <!-- Parent Correspondence Address Validation Script -->
    <script src="Assets/JSIncludes/Parent_Corr_Address_Validation.js"></script>

    <!-- Parent Correspondence Pin Validation Script -->
    <script src="Assets/JSIncludes/Parent_Corr_Pin_Validation.js"></script>

    <!-- Permanent Address Validation Script -->
    <script src="Assets/JSIncludes/Permanent_Address_Validation.js"></script>

    <!-- Permanent Pin Validation Script  -->
    <script src="Assets/JSIncludes/Permanent_Pin_Validation.js"></script>

    <!-- Email Edit Validation Script -->
    <script src="Assets/JSIncludes/Email_Edit_Validation.js"></script>

    <!-- Aadhar Validation Script -->
    <script src="Assets/JSIncludes/Aadhar_Validation.js"></script>

    <!-- Category Validation Script -->
    <script src="Assets/JSIncludes/Category_Validation.js"></script>

    <!-- Institute Validation Script -->
    <script src="Assets/JSIncludes/Institute_Validation.js"></script>

    <!-- Applied For Work Validation Script -->
    <script src="Assets/JSIncludes/Applied_Work_Validation.js"></script>

    <!-- Application Date Validation Script -->
    <script src="Assets/JSIncludes/Application_Date_Validation.js"></script>

    <!-- Eligibility Certificate No Validation Script -->
    <script src="Assets/JSIncludes/Certificate_No_Validation.js"></script>

    <!-- Eligibility Certificate Date Validation Script -->
    <script src="Assets/JSIncludes/Certificate_Date_Validation.js"></script>

    <!-- Exam Passed Validation -->
    <script src="Assets/JSIncludes/Exam_Passed_Validation.js?v=1"></script>

    <!-- University / Board Validation Script -->
    <script src="Assets/JSIncludes/University_Validation.js"></script>

    <!-- School / College Validation Script -->
    <script src="Assets/JSIncludes/School_Collge_Validation.js"></script>

    <!-- Division Validation Script -->
    <script src="Assets/JSIncludes/Divison_Validation.js"></script>

    <!-- Percentage Validation Script -->
    <script src="Assets/JSIncludes/Percentage_Validation.js"></script>

    <!-- Year of Passing Validation Script -->
    <script src="Assets/JSIncludes/Year_Validation.js"></script>

    <!-- Subejct Validation Script -->
    <script src="Assets/JSIncludes/Subject_Offered_Validation.js"></script>

    <!-- No of Attempts Validation Script -->
    <script src="Assets/JSIncludes/No_Of_Attempts_Validation.js"></script>

</body>

</html>

<?php ob_end_flush(); ?>