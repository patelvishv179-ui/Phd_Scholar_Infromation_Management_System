<?php

session_start();

date_default_timezone_set('Asia/Kolkata');

require_once "Assets/Config/Connection.php";
require_once "Assets/Helpers/flash_message.php";

// 🔹 Get data
$role = $_POST['role'] ?? '';
$login = trim($_POST['login_id'] ?? '');
$password = $_POST['password'] ?? '';

// 🔹 Basic validation
if (!$role || !$login || !$password) {

    $_SESSION['old'] = [
        'role' => $role,
        'login_id' => $login
    ];

    setMsg("danger", "All fields required");
    header("Location: login.php");
    exit;
}

// 🔹 Role validation
if (!in_array($role, ['scholar', 'admin'])) {
    setMsg("danger", "Invalid role");
    header("Location: login.php");
    exit;
}

// 🔹 Table setup
if ($role === "scholar") {
    $table = "scholar_master";
    $field = "(EMAIL=? OR MOBILE=?)";
} else {
    $table = "admin_master";
    $field = "(ADMIN_EMAIL=? OR MOBILE=?)";
}

// 🔹 Fetch user
$stmt = $con->prepare("SELECT * FROM $table WHERE $field LIMIT 1");
$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {

    $_SESSION['old'] = [
        'role' => $role,
        'login_id' => $login
    ];

    setMsg("danger", "User not found");
    header("Location: login.php");
    exit;
}

$user = $result->fetch_assoc();

// 🔹 Scholar approval check
if ($role === "scholar" && $user['APPROVE'] == 0) {

    $_SESSION['old'] = [
        'role' => $role,
        'login_id' => $login
    ];

    setMsg("danger", "Your account is pending approval");
    header("Location: login.php");
    exit;
}

// Get correct identifiers
$emailDB = ($role === "scholar") ? $user['EMAIL'] : $user['ADMIN_EMAIL'];
$mobileDB = $user['MOBILE'];

// CHECK LOCK STATUS
$check = $con->prepare("
    SELECT * FROM login_attempts 
    WHERE EMAIL=? OR MOBILE=? 
    LIMIT 1
");
$check->bind_param("ss", $emailDB, $mobileDB);
$check->execute();
$attempt = $check->get_result()->fetch_assoc();

if ($attempt && $attempt['LOCK_UNTIL'] && strtotime($attempt['LOCK_UNTIL']) > time()) {

    $_SESSION['old'] = [
        'role' => $role,
        'login_id' => $login
    ];

    $timeLeft = ceil((strtotime($attempt['LOCK_UNTIL']) - time()) / 60);

    setMsg("danger", "Account locked. Try after $timeLeft minutes.");
    header("Location: login.php");
    exit;
}

// 🔹 Password verify
$dbPass = ($role === "scholar") ? $user['SCHOLAR_PASSWORD'] : $user['ADMIN_PASSWORD'];

if (!password_verify($password, $dbPass)) {

    $_SESSION['old'] = [
        'role' => $role,
        'login_id' => $login
    ];
    // 🔍 Re-check attempt

    $check = $con->prepare("
        SELECT * FROM login_attempts 
        WHERE EMAIL=? OR MOBILE=? 
        LIMIT 1
    ");
    $check->bind_param("ss", $emailDB, $mobileDB);
    $check->execute();
    $row = $check->get_result()->fetch_assoc();

    if ($row) {

        $attempts = $row['ATTEMPTS'] + 1;

        if ($attempts >= 3) {

            $lock = date("Y-m-d H:i:s", strtotime("+10 minutes"));

            $upd = $con->prepare("
                UPDATE login_attempts 
                SET ATTEMPTS=?, LOCK_UNTIL=?, LAST_ATTEMPT=NOW()
                WHERE EMAIL=? OR MOBILE=?
            ");
            $upd->bind_param("isss", $attempts, $lock, $emailDB, $mobileDB);
            $upd->execute();

            // 📧 Send email alert
            require_once "Assets/Helpers/Send_Lock_Email.php";
            sendLockMail($emailDB);

            setMsg("danger", "Account locked for 10 minutes.");

        } else {

            $upd = $con->prepare("
                UPDATE login_attempts 
                SET ATTEMPTS=?, LAST_ATTEMPT=NOW()
                WHERE EMAIL=? OR MOBILE=?
            ");
            $upd->bind_param("iss", $attempts, $emailDB, $mobileDB);
            $upd->execute();

            setMsg("danger", "Incorrect password ($attempts/3)");
        }

    } else {

        // 🔹 First attempt
        $ins = $con->prepare("
            INSERT INTO login_attempts (EMAIL, MOBILE, ATTEMPTS, LAST_ATTEMPT)
            VALUES (?,?,1,NOW())
        ");
        $ins->bind_param("ss", $emailDB, $mobileDB);
        $ins->execute();

        setMsg("danger", "Incorrect password (1/3)");
    }

    header("Location: login.php");
    exit;
}

//  LOGIN SUCCESS

$_SESSION['user_id'] = $user['SCHOLAR_ID'] ?? $user['ADMIN_ID'];
$_SESSION['role'] = $role;

// 🔥 RESET ATTEMPTS
$del = $con->prepare("
    DELETE FROM login_attempts 
    WHERE EMAIL=? OR MOBILE=?
");
$del->bind_param("ss", $emailDB, $mobileDB);
$del->execute();

unset($_SESSION['old']);

// 🔹 Redirect
if ($role === "scholar") {

    if ($user['PROFILE_COMPLETE'] == 0) {
        header("Location: Complete_Profile.php");
        exit;
    }

    header("Location: Scholar_Dashboard.php");
    exit;

} else {

    header("Location: Admin_Dashboard.php");
    exit;
}