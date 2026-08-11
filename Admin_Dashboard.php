<?php
session_start();

// 🔐 SECURITY
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

//Connection
include("Assets/Config/Connection.php");

//Function for get value how many record in table
function getCount($table)
{
    $con = $GLOBALS['con'];

    // ✅ Allow only fixed tables
    $allowedTables = ['Subject_Available', 'Faculty_Available', 'Scholar_Master'];

    if (!in_array($table, $allowedTables)) {
        return 0;
    }

    $query = "SELECT COUNT(*) as c FROM $table";
    $result = $con->query($query);
    $row = $result->fetch_assoc();
    return $row['c'];
}
//Function for getresult
function getRecords($tbname)
{
    $con = $GLOBALS['con'];

    $allowedTables = ['Subject_Available', 'Faculty_Available', 'Scholar_Master'];

    if (!in_array($tbname, $allowedTables)) {
        return false;
    }

    return $con->query("SELECT * FROM $tbname");
}

// Total Subject Retrival
$total_subject = getCount("Subject_Available");
// Total Faculty Retrival
$total_faculty = getCount("Faculty_Available");
// Name Retrival
$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT ADMIN_NAME FROM Admin_Master WHERE ADMIN_ID=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$name = "Admin";
if ($row = $result->fetch_assoc()) {
    $name = $row['ADMIN_NAME'];

}

// total onboardings retrival
$stmt = $con->prepare("SELECT COUNT(*) as total FROM Scholar_Master WHERE APPROVE=0");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_onboarding = $row['total'];
//View Controller
$allowedViews = ['dashboard', 'manage_scholar_onboardings', 'manage_subjects', 'manage_faculties'];
$view = $_GET['view'] ?? 'dashboard';

if (!in_array($view, $allowedViews)) {
    $view = 'dashboard';
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: #eef2f7;
        }

        .sidebar {
            height: 100vh;
            background: #210547;
            color: #fff;
            position: fixed;
        }

        .content-area {
            margin-left: 254px;
        }

        .sidebar a {
            display: block;
            padding: 12px;
            color: #cbd5e1;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #3a0a7a;
            color: #fff;
        }

        .btn-p {
            background: hsl(265, 87%, 28%);
        }

        .btn-p:hover {
            background: #3a0a7a;
            color: #fff;
        }
    </style>

</head>

<body>

    <?php include "Assets/Helpers/loader.php"; ?>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-md-3 col-lg-2 sidebar p-3 text-center">

                <i class="bi bi-person-circle fs-1"></i>
                <h6 class="mt-2">Admin Panel</h6>
                <hr>

                <a href="?view=dashboard" onclick="showLoader()"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="?view=manage_scholar_onboardings" onclick="showLoader()"><i class="bi bi-mortarboard"></i> Scholar Onboardings</a>
                <a href="?view=manage_subjects" onclick="showLoader()"><i class="bi bi-person-badge"></i> Manage Subjects</a>
                <a href="?view=manage_faculties" onclick="showLoader()"><i class="bi bi-person-lines-fill"></i> Manage Faculties</a>
                <a href="Assets/Config/Logout.php" onclick="showLoader()"><i class="bi bi-box-arrow-right"></i>Logout</a>

            </div>

            <!-- CONTENT -->
            <div class="content-area col-md-9 col-lg-10 p-4">

                <?php

                if ($view == 'dashboard') {
                    // Dahsboard
                    include("Assets/Admin_Assets/Dashboard.php");
                } elseif ($view == 'manage_scholar_onboardings') {
                    //Manage Scholar Onboardings
                    include("Assets/Admin_Assets/Manage_Scholar_Onboarding.php");
                } elseif ($view == 'manage_subjects') {
                    // Manage Subjects
                    include("Assets/Admin_Assets/Manage_Subjects.php");
                } elseif ($view == 'manage_faculties') {
                    // Manage Faculties
                    include("Assets/Admin_Assets/Manage_Faculties.php");
                }
                ?>

            </div> <!-- Content Div End -->
        </div> <!-- Main Row End Inner Fluid Container -->
    </div> <!-- Main Fluid Container End -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    
    <!-- Loader -->
    <script src="Assets/Helpers/loader.js?v=<?= filemtime('Assets/Helpers/loader.js') ?>"></script>
</body>

</html>