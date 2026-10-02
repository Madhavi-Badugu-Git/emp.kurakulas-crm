<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Accept both 'employee_id' and 'id' parameters
$user_id = isset($_GET['employee_id']) ? (int) $_GET['employee_id'] : (isset($_GET['id']) ? (int) $_GET['id'] : 0);

if ($user_id > 0) {
    // Get employee details
    $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        echo '<script>alert("User not found!"); window.location.href="employee-salary-list.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("Invalid User ID! Please select an employee first."); window.location.href="employee-salary-list.php";</script>';
    exit();
}

// Handle salary update
$successMessage = '';
$errorMessage = '';

if (isset($_POST['update_salary'])) {
    $basic_salary = (float) ($_POST['basic_salary'] ?? 0);
    $da = (float) ($_POST['da'] ?? 0);
    $hra = (float) ($_POST['hra'] ?? 0);
    $other_allowance = (float) ($_POST['other_allowance'] ?? 0);
    $epf_applicable = isset($_POST['epf_applicable']) ? 1 : 0;
    $esi_applicable = isset($_POST['esi_applicable']) ? 1 : 0;

    // Get UAN and ESI from form
    $uan_no = trim($_POST['uan_no'] ?? '');
    $esi_no = trim($_POST['esi_no'] ?? '');

    // ============================================================
    // VALIDATE UAN AND ESI - ONLY DIGITS, EXACT LENGTH
    // ============================================================
    $validationErrors = [];

    // Validate UAN Number - Only if EPF is applicable (PF is OPTIONAL)
    if ($epf_applicable && !empty($uan_no)) {
        // Check if contains only digits
        if (!preg_match('/^[0-9]+$/', $uan_no)) {
            $validationErrors[] = "UAN Number must contain only DIGITS (0-9). No letters allowed!";
        } elseif (strlen($uan_no) != 12) {
            $validationErrors[] = "UAN Number must be exactly 12 digits. You entered " . strlen($uan_no) . " digits.";
        }
    }

    // Validate ESI Number - Only if ESI is applicable (ESI is OPTIONAL)
    if ($esi_applicable && !empty($esi_no)) {
        // Check if contains only digits
        if (!preg_match('/^[0-9]+$/', $esi_no)) {
            $validationErrors[] = "ESI Number must contain only DIGITS (0-9). No letters allowed!";
        } elseif (strlen($esi_no) != 17) {
            $validationErrors[] = "ESI Number must be exactly 17 digits. You entered " . strlen($esi_no) . " digits.";
        }
    }

    // If validation fails, show errors
    if (!empty($validationErrors)) {
        echo '<script>';
        foreach ($validationErrors as $error) {
            echo 'iziToast.error({ title: "Validation Error", message: "' . $error . '", position: "topRight" });';
        }
        echo '</script>';
    } else {
        $effective_from = $_POST['effective_from'] ?? date('Y-m-d');
        $effective_to = isset($_POST['effective_to']) && !empty($_POST['effective_to']) ? $_POST['effective_to'] : null;

        // Calculate gross salary
        $gross_salary = $basic_salary + $da + $hra + $other_allowance;

        // Start transaction
        $conn->begin_transaction();

        try {
            // Check if columns exist in tbl_user
            $columns = [];
            $columnQuery = "SHOW COLUMNS FROM tbl_user";
            $columnResult = mysqli_query($conn, $columnQuery);
            if ($columnResult) {
                while ($col = mysqli_fetch_assoc($columnResult)) {
                    $columns[] = $col['Field'];
                }
            }

            // Check if columns exist
            $hasBasicSalary = in_array('basic_salary', $columns);
            $hasDa = in_array('da', $columns);
            $hasHra = in_array('house_rent_allowance', $columns);
            $hasOtherAllowance = in_array('other_allowances', $columns);
            $hasEpf = in_array('epf_applicable', $columns);
            $hasEsi = in_array('esi_applicable', $columns);
            $hasUan = in_array('uan_no', $columns);
            $hasEsiNo = in_array('esi_no', $columns);
            $hasGrossSalary = in_array('gross_salary', $columns);

            // Build dynamic update query for tbl_user
            $updateFields = [];
            $updateParams = [];
            $types = "";

            if ($hasBasicSalary) {
                $updateFields[] = "basic_salary = ?";
                $updateParams[] = $basic_salary;
                $types .= "d";
            }
            if ($hasDa) {
                $updateFields[] = "da = ?";
                $updateParams[] = $da;
                $types .= "d";
            }
            if ($hasHra) {
                $updateFields[] = "house_rent_allowance = ?";
                $updateParams[] = $hra;
                $types .= "d";
            }
            if ($hasOtherAllowance) {
                $updateFields[] = "other_allowances = ?";
                $updateParams[] = $other_allowance;
                $types .= "d";
            }
            if ($hasEpf) {
                $updateFields[] = "epf_applicable = ?";
                $updateParams[] = $epf_applicable;
                $types .= "i";
            }
            if ($hasEsi) {
                $updateFields[] = "esi_applicable = ?";
                $updateParams[] = $esi_applicable;
                $types .= "i";
            }
            if ($hasUan) {
                $updateFields[] = "uan_no = ?";
                $updateParams[] = $uan_no;
                $types .= "s";
            }
            if ($hasEsiNo) {
                $updateFields[] = "esi_no = ?";
                $updateParams[] = $esi_no;
                $types .= "s";
            }
            if ($hasGrossSalary) {
                $updateFields[] = "gross_salary = ?";
                $updateParams[] = $gross_salary;
                $types .= "d";
            }

            $updateFields[] = "updated_at = NOW()";
            $updateParams[] = $user_id;
            $types .= "i";

            if (!empty($updateFields)) {
                $updateSql = "UPDATE tbl_user SET " . implode(", ", $updateFields) . " WHERE id = ?";
                $update_stmt = $conn->prepare($updateSql);
                $update_stmt->bind_param($types, ...$updateParams);
                
                if (!$update_stmt->execute()) {
                    throw new Exception("Failed to update user table: " . $update_stmt->error);
                }
                $update_stmt->close();
            }

            // Check/Create salary_structures table with UAN and ESI columns
            $table_check = "SHOW TABLES LIKE 'salary_structures'";
            $table_result = $conn->query($table_check);

            if ($table_result && $table_result->num_rows > 0) {
                // Check if uan_no column exists
                $check_uan = $conn->query("SHOW COLUMNS FROM salary_structures LIKE 'uan_no'");
                if ($check_uan && $check_uan->num_rows == 0) {
                    $conn->query("ALTER TABLE salary_structures ADD COLUMN uan_no VARCHAR(12) DEFAULT NULL AFTER esi_applicable");
                }

                // Check if esi_no column exists
                $check_esi = $conn->query("SHOW COLUMNS FROM salary_structures LIKE 'esi_no'");
                if ($check_esi && $check_esi->num_rows == 0) {
                    $conn->query("ALTER TABLE salary_structures ADD COLUMN esi_no VARCHAR(17) DEFAULT NULL AFTER uan_no");
                }

                // Close current active structure
                $close_stmt = $conn->prepare("UPDATE salary_structures 
                                             SET effective_to = DATE_SUB(?, INTERVAL 1 DAY) 
                                             WHERE employee_id = ? AND effective_to IS NULL");
                $close_stmt->bind_param("si", $effective_from, $user_id);
                $close_stmt->execute();
                $close_stmt->close();

                // Insert new structure with UAN and ESI
                $insert_stmt = $conn->prepare("INSERT INTO salary_structures 
                                               (employee_id, basic, da, hra, other_allowance, 
                                                epf_applicable, esi_applicable, uan_no, esi_no,
                                                effective_from) 
                                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_stmt->bind_param(
                    "iddddiisss",
                    $user_id,
                    $basic_salary,
                    $da,
                    $hra,
                    $other_allowance,
                    $epf_applicable,
                    $esi_applicable,
                    $uan_no,
                    $esi_no,
                    $effective_from
                );

                if (!$insert_stmt->execute()) {
                    throw new Exception("Failed to insert salary structure: " . $insert_stmt->error);
                }
                $insert_stmt->close();
            } else {
                // Create salary_structures table with UAN and ESI columns
                // EPF is OPTIONAL -> default 0
                // ESI is OPTIONAL -> default 0
                $create_table = "CREATE TABLE IF NOT EXISTS `salary_structures` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `employee_id` int(11) NOT NULL,
                    `basic` decimal(15,2) DEFAULT 0.00,
                    `da` decimal(15,2) DEFAULT 0.00,
                    `hra` decimal(15,2) DEFAULT 0.00,
                    `other_allowance` decimal(15,2) DEFAULT 0.00,
                    `epf_applicable` tinyint(1) DEFAULT 0,
                    `esi_applicable` tinyint(1) DEFAULT 0,
                    `uan_no` varchar(12) DEFAULT NULL,
                    `esi_no` varchar(17) DEFAULT NULL,
                    `effective_from` date NOT NULL,
                    `effective_to` date DEFAULT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `employee_id` (`employee_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
                $conn->query($create_table);

                // Insert the record
                $insert_stmt = $conn->prepare("INSERT INTO salary_structures 
                                               (employee_id, basic, da, hra, other_allowance, 
                                                epf_applicable, esi_applicable, uan_no, esi_no,
                                                effective_from) 
                                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_stmt->bind_param(
                    "iddddiisss",
                    $user_id,
                    $basic_salary,
                    $da,
                    $hra,
                    $other_allowance,
                    $epf_applicable,
                    $esi_applicable,
                    $uan_no,
                    $esi_no,
                    $effective_from
                );
                $insert_stmt->execute();
                $insert_stmt->close();
            }

            // Commit transaction
            $conn->commit();

            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    iziToast.success({
                        title: "Success",
                        message: "Salary structure updated successfully!",
                        position: "topRight",
                        timeout: 3000
                    });
                    setTimeout(function() {
                        window.location.href = "salary.php?employee_id=' . $user_id . '";
                    }, 2000);
                });
            </script>';

        } catch (Exception $e) {
            $conn->rollback();
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    iziToast.error({
                        title: "Error",
                        message: "Error: ' . $e->getMessage() . '",
                        position: "topRight"
                    });
                });
            </script>';
        }
    }
}

// Get salary history from salary_structures with UAN and ESI
$history = [];
$table_check = "SHOW TABLES LIKE 'salary_structures'";
$table_result = $conn->query($table_check);

if ($table_result && $table_result->num_rows > 0) {
    // Check and add missing columns if needed
    $check_uan = $conn->query("SHOW COLUMNS FROM salary_structures LIKE 'uan_no'");
    if ($check_uan && $check_uan->num_rows == 0) {
        $conn->query("ALTER TABLE salary_structures ADD COLUMN uan_no VARCHAR(12) DEFAULT NULL");
    }

    $check_esi = $conn->query("SHOW COLUMNS FROM salary_structures LIKE 'esi_no'");
    if ($check_esi && $check_esi->num_rows == 0) {
        $conn->query("ALTER TABLE salary_structures ADD COLUMN esi_no VARCHAR(17) DEFAULT NULL");
    }

    // Get history with UAN and ESI
    $history_stmt = $conn->prepare("SELECT * FROM salary_structures 
                                    WHERE employee_id = ? 
                                    ORDER BY effective_from DESC");
    $history_stmt->bind_param("i", $user_id);
    $history_stmt->execute();
    $history_result = $history_stmt->get_result();

    while ($row = $history_result->fetch_assoc()) {
        $history[] = $row;
    }
    $history_stmt->close();
}

// Current salary values (from tbl_user)
$basic = $user['basic_salary'] ?? 0;
$da = $user['da'] ?? 0;
$hra = $user['house_rent_allowance'] ?? 0;
$other = $user['other_allowances'] ?? 0;
$total = $basic + $da + $hra + $other;
// PF is OPTIONAL -> default 0 (unchecked)
$epf = $user['epf_applicable'] ?? 0;
// ESI is OPTIONAL -> default 0 (unchecked)
$esi = $user['esi_applicable'] ?? 0;

// Get UAN and ESI from user table
$uan_no = $user['uan_no'] ?? '';
$esi_no = $user['esi_no'] ?? '';

// Proper employee name and code
$firstName = $user['firstName'] ?? '';
$lastName = $user['lastName'] ?? '';
$username = $user['username'] ?? 'Unknown';

$employee_name = htmlspecialchars(trim($firstName . ' ' . $lastName));
if (empty($employee_name)) {
    $employee_name = htmlspecialchars($username);
}
$employee_code = htmlspecialchars($username);
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary - <?= $employee_name ?></title>
    <?php include('../includes/header.php'); ?>
</head>

<style>
    .layout-container {
        display: flex;
        min-height: 100vh;
        position: relative;
    }

    .layout-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        flex-shrink: 0;
        width: 260px;
        background: #1a2332;
        color: #ffffff;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .layout-sidebar::-webkit-scrollbar {
        width: 4px;
    }
    .layout-sidebar::-webkit-scrollbar-thumb {
        background: #4a5568;
        border-radius: 4px;
    }
    .layout-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .layout-page {
        flex: 1;
        min-height: 100vh;
        overflow-y: auto;
        margin-left: 0;
    }

    .salary-card {
        margin-bottom: 25px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .form-label {
        font-weight: 600;
        font-size: 13px;
        color: #344767;
        margin-bottom: 4px;
    }

    .input-group-text {
        background: #f8f9fa;
        border-color: #d2d6da;
        font-size: 13px;
    }

    .form-control {
        border-color: #d2d6da;
        font-size: 13px;
        border-radius: 6px;
        height: 38px;
    }

    .form-control:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.15);
    }

    .form-control::placeholder {
        color: #adb5bd;
        font-size: 12px;
    }

    .form-text {
        font-size: 11px;
        color: #6b7a8f;
        margin-top: 2px;
    }

    .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 15px 24px;
    }

    .card-header h5 {
        font-weight: 600;
        color: #1a2332;
        font-size: 16px;
    }

    .btn-primary {
        background: #696cff;
        border-color: #696cff;
        padding: 8px 28px;
        color: #ffffff;
        font-size: 14px;
    }

    .btn-primary:hover {
        background: #5a5de0;
        border-color: #5a5de0;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);
        color: #ffffff;
    }

    .btn-secondary {
        background: #6c757d;
        border-color: #6c757d;
        padding: 8px 28px;
        color: #ffffff;
        font-size: 14px;
    }

    .btn-secondary:hover {
        background: #5a6268;
        border-color: #5a6268;
        color: #ffffff;
    }

    .btn-sm {
        padding: 4px 14px;
        font-size: 12px;
    }

    .gross-display {
        background: linear-gradient(135deg, #f0f7ff 0%, #e8f0fe 100%);
        border: 2px solid #696cff;
        border-radius: 10px;
        padding: 15px 20px;
        text-align: center;
    }

    .gross-display .label {
        font-size: 12px;
        color: #6b7a8f;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .gross-display .amount {
        font-size: 28px;
        font-weight: 700;
        color: #696cff;
    }

    .gross-display .amount .currency {
        font-size: 18px;
        color: #6b7a8f;
    }

    .history-table {
        font-size: 13px;
    }

    .history-table th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 8px;
    }

    .history-table td {
        padding: 8px;
        vertical-align: middle;
    }

    .badge-current {
        background: #28a745;
        color: #fff;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 600;
    }

    .info-text {
        font-size: 13px;
        color: #6b7a8f;
        padding: 10px 16px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 4px solid #696cff;
        margin-bottom: 15px;
    }

    .info-text i {
        color: #696cff;
    }

    .page-title {
        font-size: 20px;
        font-weight: 600;
    }

    .employee-badge {
        background: #696cff;
        color: #fff;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
    }

    .employee-badge .id-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 8px;
        border-radius: 4px;
        margin-left: 6px;
    }

    .statutory-section {
        background: #f8fafc;
        border-radius: 8px;
        padding: 15px 20px;
        border: 1px solid #e9ecef;
        margin-top: 8px;
    }

    .statutory-section .section-title {
        font-size: 13px;
        font-weight: 600;
        color: #344767;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e9ecef;
    }

    .statutory-section .form-check {
        margin-right: 20px;
    }

    .statutory-section .form-check-label {
        font-size: 13px;
        font-weight: 500;
    }

    .uan-esi-display {
        background: #e8f0fe;
        border: 1px solid #696cff;
        border-radius: 4px;
        padding: 3px 10px;
        display: inline-block;
        font-family: monospace;
        font-weight: 600;
        color: #696cff;
        font-size: 12px;
    }

    .no-data {
        color: #adb5bd;
        font-style: italic;
    }

    .breadcrumb-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 10px 16px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    .breadcrumb-box a {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        font-weight: 500;
        color: #198754;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .breadcrumb-box a i {
        font-size: 16px;
        color: #198754;
    }

    .breadcrumb-box a:hover {
        color: #0f5132;
        text-decoration: underline;
    }

    .breadcrumb-box .separator {
        color: #9ca3af;
        font-size: 16px;
        font-weight: 600;
    }

    .breadcrumb-box .active {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        font-weight: 600;
        color: #1a2332;
        text-decoration: none;
    }

    .breadcrumb-box .active i {
        font-size: 16px;
        color: #1a2332;
    }

    .statutory-section .row {
        margin: 0;
    }

    .statutory-section .col-md-6 {
        padding: 0 10px;
    }

    .statutory-section .mb-0 {
        margin-bottom: 0 !important;
    }

    .statutory-section .form-check {
        padding-left: 0;
    }

    .statutory-section .form-check-input {
        margin-right: 8px;
    }

    .statutory-section .form-check-label {
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
    }

    .statutory-section .input-group {
        margin-top: 2px;
    }

    .layout-sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9998;
    }

    .layout-sidebar-overlay.active {
        display: block;
    }

    @media (max-width: 768px) {
        .layout-sidebar {
            position: fixed;
            left: -280px;
            width: 280px;
            transition: left 0.3s ease;
            z-index: 9999;
        }

        .layout-sidebar.open {
            left: 0;
        }

        .gross-display .amount {
            font-size: 22px;
        }
        .page-title {
            font-size: 17px;
        }
        .card-header {
            flex-direction: column;
            gap: 8px;
            align-items: flex-start !important;
        }
        .employee-badge {
            font-size: 11px;
            padding: 5px 10px;
        }
        .history-table {
            font-size: 11px;
        }
        .history-table th,
        .history-table td {
            padding: 4px;
        }
        .breadcrumb-box {
            padding: 6px 10px;
        }
        .breadcrumb-box a,
        .breadcrumb-box .active {
            font-size: 12px;
        }
        .statutory-section .col-md-6 {
            margin-bottom: 8px;
        }
        .statutory-section .col-md-6:last-child {
            margin-bottom: 0;
        }
        .statutory-section .row .col-md-6 {
            padding: 0 5px;
        }
    }

    @media (max-width: 480px) {
        .breadcrumb-box {
            flex-wrap: wrap;
            gap: 3px;
        }
        .breadcrumb-box a,
        .breadcrumb-box .active {
            font-size: 10px;
        }
        .breadcrumb-box a i,
        .breadcrumb-box .active i {
            font-size: 12px;
        }
        .form-control {
            font-size: 12px;
            height: 34px;
        }
        .gross-display .amount {
            font-size: 18px;
        }
        .employee-badge {
            font-size: 10px;
            padding: 4px 8px;
        }
        .history-table {
            font-size: 10px;
        }
        .statutory-section {
            padding: 10px 12px;
        }
        .statutory-section .form-check-label {
            font-size: 12px;
        }
    }
</style>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Side Menu - Sticky -->
            <div class="layout-sidebar" id="sidebar">
                <?php include('../includes/sideMenu.php'); ?>
            </div>

            <!-- Mobile Sidebar Overlay -->
            <div class="layout-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

            <!-- Page Content -->
            <div class="layout-page">

                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Breadcrumb -->
                        <div class="breadcrumb-box">
                            <a href="../dashboard/superAdmin">
                                <i class="bx bx-home"></i> Dashboard
                            </a>
                            <span class="separator">›</span>
                            <a href="employee-salary-list.php">
                                <i class="bx bx-group"></i> Active EMP List
                            </a>
                            <span class="separator">›</span>
                            <span class="active">
                                <i class="bx bx-dollar-circle"></i> Salary
                            </span>
                        </div>

                        <!-- Page Header -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold py-2 mb-0 page-title">
                                <span class="text-muted fw-light"></span>
                            </h4>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="employee-badge">
                                    <?= $employee_name ?>
                                    <span class="id-badge"><?= $employee_code ?></span>
                                </span>
                                <a href="employee-salary-list.php" class="btn btn-secondary btn-sm">
                                    <i class="bx bx-arrow-back me-1"></i> Back
                                </a>
                            </div>
                        </div>

                        <!-- Set/Revise Salary Card -->
                        <div class="row">
                            <div class="col-xl">
                                <div class="card salary-card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><i class="bx bx-rupee me-2"></i>Set / Revise Salary</h5>
                                    </div>
                                    <div class="card-body">

                                        <div class="info-text">
                                            <i class="bx bx-info-circle me-1"></i>
                                            Saving a new structure automatically closes the current one the day before
                                            the new effective date, keeping full history.
                                            <strong>EPF is optional</strong> — enable it only if applicable.
                                            <strong>ESI is optional</strong> — enable it only if applicable.
                                        </div>

                                        <form action="" method="POST" id="salaryForm" novalidate>
                                            <div class="row g-2">
                                                <!-- Basic Salary -->
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="basic_salary">Basic <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-rupee"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="basic_salary" id="basic_salary"
                                                                value="<?= htmlspecialchars($basic) ?>" onkeyup="calculateGross()"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- DA -->
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="da">DA</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-rupee"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="da" id="da" value="<?= htmlspecialchars($da) ?>"
                                                                onkeyup="calculateGross()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- HRA -->
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="hra">HRA</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-rupee"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="hra" id="hra" value="<?= htmlspecialchars($hra) ?>"
                                                                onkeyup="calculateGross()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Other Allowance -->
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="other_allowance">Other
                                                            Allowance</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-rupee"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="other_allowance" id="other_allowance"
                                                                value="<?= htmlspecialchars($other) ?>" onkeyup="calculateGross()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Effective From -->
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label class="form-label" for="effective_from">Effective From
                                                            <span class="text-danger">*</span></label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-calendar"></i></span>
                                                            <input type="date" class="form-control"
                                                                name="effective_from" id="effective_from"
                                                                value="<?= date('Y-m-d') ?>" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Statutory Details -->
                                                <div class="col-md-12">
                                                    <div class="statutory-section">
                                                        <div class="section-title">
                                                            <i class="bx bx-shield me-2"></i>Statutory Details
                                                        </div>
                                                        
                                                        <!-- EPF/ESI Checkboxes -->
                                                        <div class="row g-1 mb-2">
                                                            <div class="col-12">
                                                                <div class="d-flex gap-4 flex-wrap">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="epf_applicable" id="epf_applicable"
                                                                            <?= $epf ? 'checked' : '' ?>>
                                                                        <label class="form-check-label"
                                                                            for="epf_applicable">
                                                                            <strong>EPF Applicable</strong>
                                                                            <small class="text-muted">(Optional)</small>
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="esi_applicable" id="esi_applicable"
                                                                            <?= $esi ? 'checked' : '' ?>>
                                                                        <label class="form-check-label"
                                                                            for="esi_applicable">
                                                                            <strong>ESI Applicable</strong>
                                                                            <small class="text-muted">(Optional)</small>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- UAN Input - EXACTLY 12 DIGITS ONLY -->
                                                        <div class="row g-1">
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="uan_no">
                                                                    <i class="bx bx-id-card me-1"></i> UAN No.
                                                                    <small class="text-muted">(Universal Account Number)</small>
                                                                </label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-hash"></i></span>
                                                                    <input type="text" class="form-control"
                                                                        name="uan_no" id="uan_no"
                                                                        value="<?= htmlspecialchars($uan_no) ?>"
                                                                        placeholder="Enter 12-digit UAN Number"
                                                                        maxlength="12"
                                                                        minlength="12"
                                                                        pattern="[0-9]{12}"
                                                                        title="UAN must be exactly 12 digits (0-9) only">
                                                                </div>
                                                                <div class="form-text"><strong>Must be exactly 12 DIGITS</strong> (0-9) only. Example: 123456789012</div>
                                                            </div>

                                                            <!-- ESI Input - EXACTLY 17 DIGITS ONLY -->
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="esi_no">
                                                                    <i class="bx bx-id-card me-1"></i> ESI No.
                                                                    <small class="text-muted">(Employee State Insurance)</small>
                                                                </label>
                                                                <div class="input-group input-group-merge">
                                                                    <span class="input-group-text"><i
                                                                            class="bx bx-hash"></i></span>
                                                                    <input type="text" class="form-control"
                                                                        name="esi_no" id="esi_no"
                                                                        value="<?= htmlspecialchars($esi_no) ?>"
                                                                        placeholder="Enter 17-digit ESI Number"
                                                                        maxlength="17"
                                                                        minlength="17"
                                                                        pattern="[0-9]{17}"
                                                                        title="ESI must be exactly 17 digits (0-9) only">
                                                                </div>
                                                                <div class="form-text"><strong>Must be exactly 17 DIGITS</strong> (0-9) only. Example: 123456789012345</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Gross Salary Display -->
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <div class="gross-display">
                                                        <div class="label">Gross Salary (Auto-Calculated)</div>
                                                        <div class="amount">
                                                            <span class="currency">₹</span>
                                                            <span
                                                                id="gross_display"><?= number_format($total, 2) ?></span>
                                                        </div>
                                                        <input type="hidden" name="gross_salary" id="gross_salary"
                                                            value="<?= $total ?>">
                                                        <div class="mt-1">
                                                            <small class="text-muted">Gross = Basic + DA + HRA +
                                                                Other</small>
                                                            <button type="button" class="btn btn-sm btn-primary ms-2"
                                                                onclick="calculateGross()">
                                                                <i class="bx bx-calculator me-1"></i> Calculate
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Buttons -->
                                            <div class="mt-2">
                                                <button type="submit" name="update_salary" class="btn btn-primary">
                                                    <i class="bx bx-save me-1"></i> Save Salary Structure
                                                </button>
                                                <a href="employee-salary-list.php" class="btn btn-secondary ms-2">
                                                    <i class="bx bx-arrow-back me-1"></i> Cancel
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- History Section -->
                        <div class="row mt-3">
                            <div class="col-xl">
                                <div class="card">
                                    <div
                                        class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                        <h5 class="mb-0"><i class="bx bx-history me-2"></i>History</h5>
                                        <span class="badge bg-secondary">Total: <?= count($history) ?> records</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover history-table">
                                                <thead>
                                                    <tr>
                                                        <th>Basic</th>
                                                        <th>DA</th>
                                                        <th>HRA</th>
                                                        <th>Other</th>
                                                        <th>EPF</th>
                                                        <th>ESI</th>
                                                        <th>UAN</th>
                                                        <th>ESI No.</th>
                                                        <th>From</th>
                                                        <th>To</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (count($history) > 0): ?>
                                                        <?php foreach ($history as $h): ?>
                                                            <tr>
                                                                <td><strong>₹<?= number_format($h['basic'] ?? 0, 2) ?></strong>
                                                                </td>
                                                                <td>₹<?= number_format($h['da'] ?? 0, 2) ?></td>
                                                                <td>₹<?= number_format($h['hra'] ?? 0, 2) ?></td>
                                                                <td>₹<?= number_format($h['other_allowance'] ?? 0, 2) ?></td>
                                                                <td><?= ($h['epf_applicable'] ?? 0) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
                                                                </td>
                                                                <td><?= ($h['esi_applicable'] ?? 0) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $uan_value = trim($h['uan_no'] ?? '');
                                                                    if (!empty($uan_value)) {
                                                                        echo '<span class="uan-esi-display">' . htmlspecialchars($uan_value) . '</span>';
                                                                    } else {
                                                                        echo '<span class="no-data">-</span>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $esi_value = trim($h['esi_no'] ?? '');
                                                                    if (!empty($esi_value)) {
                                                                        echo '<span class="uan-esi-display">' . htmlspecialchars($esi_value) . '</span>';
                                                                    } else {
                                                                        echo '<span class="no-data">-</span>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?= isset($h['effective_from']) ? date('d M Y', strtotime($h['effective_from'])) : '-' ?>
                                                                </td>
                                                                <td>
                                                                    <?php if (isset($h['effective_to']) && !empty($h['effective_to'])): ?>
                                                                        <?= date('d M Y', strtotime($h['effective_to'])) ?>
                                                                    <?php else: ?>
                                                                        <span class="badge-current">Current</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="10" class="text-center text-muted py-3">
                                                                <i class="bx bx-history me-1"></i> No salary history found
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <?php include('../includes/footer.php'); ?>

                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/script.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuToggle = document.querySelector('.menu-toggle');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !menuToggle?.contains(event.target)) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                }
            }
        });

        function calculateGross() {
            const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
            const da = parseFloat(document.getElementById('da').value) || 0;
            const hra = parseFloat(document.getElementById('hra').value) || 0;
            const other = parseFloat(document.getElementById('other_allowance').value) || 0;

            const gross = basic + da + hra + other;
            document.getElementById('gross_display').textContent = gross.toFixed(2);
            document.getElementById('gross_salary').value = gross.toFixed(2);
        }

        // Calculate on load
        document.addEventListener('DOMContentLoaded', function() {
            calculateGross();
        });

        // ============================================================
        // UAN VALIDATION - EXACTLY 12 DIGITS, NO LETTERS
        // ============================================================
        document.getElementById('uan_no').addEventListener('blur', function() {
            const val = this.value.trim();
            
            // Skip if empty (optional field)
            if (val === '') {
                this.classList.remove('is-invalid', 'is-valid');
                return;
            }
            
            // Check if contains only digits
            if (!/^\d+$/.test(val)) {
                iziToast.error({
                    title: "Invalid UAN",
                    message: "UAN must contain only DIGITS (0-9). No letters allowed!",
                    position: "topRight"
                });
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                // Remove non-digit characters
                this.value = val.replace(/[^0-9]/g, '');
                return;
            }
            
            // Check length
            if (val.length !== 12) {
                iziToast.error({
                    title: "Invalid UAN",
                    message: "UAN must be exactly 12 digits. You entered " + val.length + " digits.",
                    position: "topRight"
                });
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                iziToast.success({
                    title: "Valid UAN",
                    message: "UAN is valid (12 digits)",
                    position: "topRight",
                    timeout: 2000
                });
            }
        });

        // Allow only digits while typing in UAN field
        document.getElementById('uan_no').addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
                e.preventDefault();
                iziToast.info({
                    title: "Info",
                    message: "Only digits (0-9) are allowed for UAN",
                    position: "topRight",
                    timeout: 2000
                });
            }
        });

        // ============================================================
        // ESI VALIDATION - EXACTLY 17 DIGITS, NO LETTERS
        // ============================================================
        document.getElementById('esi_no').addEventListener('blur', function() {
            const val = this.value.trim();
            
            // Skip if empty (optional field)
            if (val === '') {
                this.classList.remove('is-invalid', 'is-valid');
                return;
            }
            
            // Check if contains only digits
            if (!/^\d+$/.test(val)) {
                iziToast.error({
                    title: "Invalid ESI",
                    message: "ESI must contain only DIGITS (0-9). No letters allowed!",
                    position: "topRight"
                });
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
                // Remove non-digit characters
                this.value = val.replace(/[^0-9]/g, '');
                return;
            }
            
            // Check length
            if (val.length !== 17) {
                iziToast.error({
                    title: "Invalid ESI",
                    message: "ESI must be exactly 17 digits. You entered " + val.length + " digits.",
                    position: "topRight"
                });
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                iziToast.success({
                    title: "Valid ESI",
                    message: "ESI is valid (17 digits)",
                    position: "topRight",
                    timeout: 2000
                });
            }
        });

        // Allow only digits while typing in ESI field
        document.getElementById('esi_no').addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete' && e.key !== 'Tab') {
                e.preventDefault();
                iziToast.info({
                    title: "Info",
                    message: "Only digits (0-9) are allowed for ESI",
                    position: "topRight",
                    timeout: 2000
                });
            }
        });

        // ============================================================
        // FORM SUBMISSION VALIDATION
        // UAN only validated if EPF is checked (PF is OPTIONAL)
        // ESI only validated if ESI is checked (ESI is OPTIONAL)
        // ============================================================
        document.getElementById('salaryForm').addEventListener('submit', function(e) {
            const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
            if (basic <= 0) {
                e.preventDefault();
                iziToast.error({
                    title: "Error",
                    message: "Basic salary must be greater than 0",
                    position: "topRight"
                });
                document.getElementById('basic_salary').focus();
                return false;
            }
            
            // Validate UAN only if EPF is applicable and UAN is filled
            const epfChecked = document.getElementById('epf_applicable').checked;
            const uan = document.getElementById('uan_no').value.trim();
            if (epfChecked && uan !== '') {
                if (!/^\d{12}$/.test(uan)) {
                    e.preventDefault();
                    iziToast.error({
                        title: "Invalid UAN",
                        message: "UAN must be exactly 12 DIGITS (0-9). Example: 123456789012",
                        position: "topRight"
                    });
                    document.getElementById('uan_no').focus();
                    return false;
                }
            }
            
            // Validate ESI only if ESI is applicable and ESI is filled
            const esiChecked = document.getElementById('esi_applicable').checked;
            const esi = document.getElementById('esi_no').value.trim();
            if (esiChecked && esi !== '') {
                if (!/^\d{17}$/.test(esi)) {
                    e.preventDefault();
                    iziToast.error({
                        title: "Invalid ESI",
                        message: "ESI must be exactly 17 DIGITS (0-9). Example: 123456789012345",
                        position: "topRight"
                    });
                    document.getElementById('esi_no').focus();
                    return false;
                }
            }
        });
    </script>
</body>
</html>