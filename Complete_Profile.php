<?php
session_start();

require_once "Assets/Config/Connection.php";

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'scholar') {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];
$stmt = $con->prepare("
SELECT SCHOLAR_ID, SCHOLAR_NAME, EMAIL, PROFILE_COMPLETE
FROM scholar_master 
WHERE SCHOLAR_ID=?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    // Invalid user / tampering case
    session_destroy();
    header("Location: login.php");
    exit;
}
$data = $result->fetch_assoc();
$stmt->close();

$_SESSION['SCHOLAR_EMAIL'] = $data['EMAIL'];
$_SESSION['SCHOLAR_NAME'] = $data['SCHOLAR_NAME'];
$_SESSION['user_id'] = $data['SCHOLAR_ID'];

require_once "Controllers/AcademicController.php";
require_once "Controllers/PersonalController.php";
require_once "Controllers/EducationController.php";
require_once "Controllers/ExperienceController.php";

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholar Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap Bundle JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>


        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

           body {
            background: #f4f6f9;
            margin: 0;
            font-family: Segoe UI;
            height: 100%;
            width: 100%;
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
            margin-bottom: 10px;
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

        .logout-btn {
            padding: 10px 22px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            color: #dc3545;
            border: 1px solid #dc3545;
            transition: 0.2s ease;
        }

        .logout-btn:hover {
            background-color: #86121d;
            color: #fff;
            border: 2px solid #fff;
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


    
    </style>

</head>

<body class="bg-light">

    <div class="container mt-3 mb-4 ">

        <div class="mx-auto" style="max-width:1000px;">

            <div class="border rounded shadow-sm p-4 bg-white">

                <?php include("ViewController.php"); ?>

            </div>

        </div>

    </div>
    <!-- Close Button js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>