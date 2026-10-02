<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($user_id > 0) {
    // Get employee details
    $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        echo '<script>alert("User not found!"); window.location.href="list.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("Invalid User ID!"); window.location.href="list.php";</script>';
    exit();
}

// Handle salary update
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

    $effective_from = $_POST['effective_from'] ?? date('Y-m-d');
    $effective_to = isset($_POST['effective_to']) && !empty($_POST['effective_to']) ? $_POST['effective_to'] : null;

    // Calculate gross salary
    $gross_salary = $basic_salary + $da + $hra + $other_allowance;

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update tbl_user with UAN and ESI
        $update_stmt = $conn->prepare("UPDATE tbl_user SET 
                    basic_salary = ?,
                    da = ?,
                    house_rent_allowance = ?,
                    other_allowances = ?,
                    epf_applicable = ?,
                    esi_applicable = ?,
                    uan_no = ?,
                    esi_no = ?,
                    gross_salary = ?,
                    updated_at = NOW()
                    WHERE id = ?");

        $update_stmt->bind_param(
            "ddddddssdi",
            $basic_salary,
            $da,
            $hra,
            $other_allowance,
            $epf_applicable,
            $esi_applicable,
            $uan_no,
            $esi_no,
            $gross_salary,
            $user_id
        );

        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update user table: " . $update_stmt->error);
        }
        $update_stmt->close();

        // Check/Create salary_structures table with UAN and ESI columns
        $table_check = "SHOW TABLES LIKE 'salary_structures'";
        $table_result = $conn->query($table_check);

        if ($table_result && $table_result->num_rows > 0) {
            // Check if uan_no column exists
            $check_uan = $conn->query("SHOW COLUMNS FROM salary_structures LIKE 'uan_no'");
            if ($check_uan->num_rows == 0) {
                $conn->query("ALTER TABLE salary_structures ADD COLUMN uan_no VARCHAR(50) DEFAULT NULL AFTER esi_applicable");
            }

            // Check if esi_no column exists
            $check_esi = $conn->query("SHOW COLUMNS FROM salary_structures LIKE 'esi_no'");
            if ($check_esi->num_rows == 0) {
                $conn->query("ALTER TABLE salary_structures ADD COLUMN esi_no VARCHAR(50) DEFAULT NULL AFTER uan_no");
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
            $create_table = "CREATE TABLE IF NOT EXISTS `salary_structures` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `employee_id` int(11) NOT NULL,
                `basic` decimal(15,2) DEFAULT 0.00,
                `da` decimal(15,2) DEFAULT 0.00,
                `hra` decimal(15,2) DEFAULT 0.00,
                `other_allowance` decimal(15,2) DEFAULT 0.00,
                `epf_applicable` tinyint(1) DEFAULT 1,
                `esi_applicable` tinyint(1) DEFAULT 1,
                `uan_no` varchar(50) DEFAULT NULL,
                `esi_no` varchar(50) DEFAULT NULL,
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
            iziToast.success({
                title: "Success",
                message: "Salary structure updated successfully! UAN: ' . $uan_no . ', ESI: ' . $esi_no . '",
                position: "topRight",
            });
            setTimeout(() => { window.location.href="salary.php?id=' . $user_id . '"; }, 1500);
        </script>';

    } catch (Exception $e) {
        $conn->rollback();
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Error: ' . $e->getMessage() . '",
                position: "topRight",
            });
        </script>';
    }
}

// Get salary history from salary_structures with UAN and ESI
$history = [];
$table_check = "SHOW TABLES LIKE 'salary_structures'";
$table_result = $conn->query($table_check);

if ($table_result && $table_result->num_rows > 0) {
    // Check and add missing columns if needed
    $check_uan = $conn->query("SHOW COLUMNS FROM salary_structures LIKE 'uan_no'");
    if ($check_uan->num_rows == 0) {
        $conn->query("ALTER TABLE salary_structures ADD COLUMN uan_no VARCHAR(50) DEFAULT NULL");
    }

    $check_esi = $conn->query("SHOW COLUMNS FROM salary_structures LIKE 'esi_no'");
    if ($check_esi->num_rows == 0) {
        $conn->query("ALTER TABLE salary_structures ADD COLUMN esi_no VARCHAR(50) DEFAULT NULL");
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
$epf = $user['epf_applicable'] ?? 1;
$esi = $user['esi_applicable'] ?? 1;

// Get UAN and ESI from user table
$uan_no = $user['uan_no'] ?? '';
$esi_no = $user['esi_no'] ?? '';

$employee_name = htmlspecialchars($user['firstName'] . ' ' . $user['lastName']);
$employee_code = $user['employee_no'] ?? 'N/A';
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<?php include('../includes/header.php'); ?>

<style>
    .salary-card {
        margin-bottom: 25px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    .form-label {
        font-weight: 600;
        font-size: 14px;
        color: #344767;
    }

    .input-group-text {
        background: #f8f9fa;
        border-color: #d2d6da;
    }

    .form-control {
        border-color: #d2d6da;
        font-size: 14px;
        border-radius: 6px;
        height: 42px;
    }

    .form-control:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.15);
    }

    .form-control-lg {
        height: 48px;
        font-size: 16px;
    }

    .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 18px 24px;
    }

    .card-header h5 {
        font-weight: 600;
        color: #1a2332;
    }

    .btn-primary {
        background: #696cff;
        border-color: #696cff;
        padding: 10px 30px;
    }

    .btn-primary:hover {
        background: #5a5de0;
        border-color: #5a5de0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);
    }

    .btn-secondary {
        background: #6c757d;
        border-color: #6c757d;
        padding: 10px 30px;
    }

    .btn-secondary:hover {
        background: #5a6268;
        border-color: #5a6268;
    }

    .btn-sm {
        padding: 5px 15px;
        font-size: 12px;
    }

    .gross-display {
        background: linear-gradient(135deg, #f0f7ff 0%, #e8f0fe 100%);
        border: 2px solid #696cff;
        border-radius: 12px;
        padding: 20px 25px;
        text-align: center;
    }

    .gross-display .label {
        font-size: 13px;
        color: #6b7a8f;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .gross-display .amount {
        font-size: 32px;
        font-weight: 700;
        color: #696cff;
    }

    .gross-display .amount .currency {
        font-size: 20px;
        color: #6b7a8f;
    }

    .history-table {
        font-size: 14px;
    }

    .history-table th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 10px;
    }

    .history-table td {
        padding: 10px;
        vertical-align: middle;
    }

    .badge-current {
        background: #28a745;
        color: #fff;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-archived {
        background: #6c757d;
        color: #fff;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .info-text {
        font-size: 13px;
        color: #6b7a8f;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 4px solid #696cff;
        margin-bottom: 20px;
    }

    .info-text i {
        color: #696cff;
    }

    .text-muted {
        color: #6b7a8f !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-success {
        color: #28a745 !important;
    }

    .page-title {
        font-size: 22px;
        font-weight: 600;
    }

    .page-title .text-muted {
        font-weight: 400;
    }

    .employee-badge {
        background: #696cff;
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
    }

    .employee-badge .id-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 10px;
        border-radius: 4px;
        margin-left: 8px;
    }

    .statutory-section {
        background: #f8fafc;
        border-radius: 8px;
        padding: 20px;
        border: 1px solid #e9ecef;
        margin-top: 10px;
    }

    .statutory-section .section-title {
        font-size: 13px;
        font-weight: 600;
        color: #344767;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    .uan-esi-display {
        background: #e8f0fe;
        border: 1px solid #696cff;
        border-radius: 4px;
        padding: 4px 10px;
        display: inline-block;
        font-family: monospace;
        font-weight: 600;
        color: #696cff;
        font-size: 13px;
    }

    .no-data {
        color: #adb5bd;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .gross-display .amount {
            font-size: 24px;
        }

        .page-title {
            font-size: 18px;
        }

        .card-header {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start !important;
        }

        .employee-badge {
            font-size: 12px;
            padding: 6px 12px;
        }

        .history-table {
            font-size: 12px;
        }

        .history-table th,
        .history-table td {
            padding: 6px 4px;
        }
    }

    /* ============================================================
   BREADCRUMB BOX (ADD THIS ONLY)
   ============================================================ */
    .breadcrumb-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .breadcrumb-box a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 15px;
        font-weight: 500;
        color: #198754;
        text-decoration: underline;
        text-decoration-thickness: 1px;
        text-underline-offset: 4px;
        transition: all 0.2s ease;
    }

    .breadcrumb-box a i {
        font-size: 18px;
        color: #198754;
    }

    .breadcrumb-box a:hover {
        color: #0f5132;
    }

    .breadcrumb-box .separator {
        color: #9ca3af;
        font-size: 18px;
        font-weight: 600;
    }

    .breadcrumb-box .active {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 15px;
        font-weight: 600;
        color: #1a2332;
        text-decoration: none;
    }

    .breadcrumb-box .active i {
        font-size: 18px;
        color: #1a2332;
    }
</style>

<body>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <?php include('../includes/sideMenu.php'); ?>

            <div class="layout-page">

                <?php include('../includes/navbar.php'); ?>

                <div class="content-wrapper">

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <!-- Breadcrumb -->
                        <div class="breadcrumb-box">
                            <a href="../dashboard/superAdmin">
                                <i class="bx bx-home"></i> Dashboard
                            </a>
                            <span class="separator">›</span>
                            <a href="../users/list.php">
                                <i class="bx bx-briefcase"></i>Active EMP List
                            </a>
                            <span class="separator">›</span>
                            <span class="active">
                                <i class="bx bx-calculator"></i> Salary
                            </span>
                        </div>
                        <!-- Page Title -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">


                            <h4 class="fw-bold py-3 mb-0 page-title">
                                <span class="text-muted fw-light"></span>
                            </h4>
                            <div class="d-flex gap-2">
                                <span class="employee-badge">
                                    <?= $employee_name ?>
                                    <span class="id-badge"><?= $employee_code ?></span>
                                </span>
                                <a href="list.php" class="btn btn-secondary btn-sm">
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
                                        </div>

                                        <form action="" method="POST" id="salaryForm">
                                            <div class="row g-3">
                                                <!-- Basic Salary -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="basic_salary">Basic <span
                                                                class="text-danger">*</span></label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-rupee"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="basic_salary" id="basic_salary"
                                                                value="<?= $basic ?>" onkeyup="calculateGross()"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- DA -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="da">DA</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-rupee"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="da" id="da" value="<?= $da ?>"
                                                                onkeyup="calculateGross()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- HRA -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="hra">HRA</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-rupee"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="hra" id="hra" value="<?= $hra ?>"
                                                                onkeyup="calculateGross()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Other Allowance -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="other_allowance">Other
                                                            Allowance</label>
                                                        <div class="input-group input-group-merge">
                                                            <span class="input-group-text"><i
                                                                    class="bx bx-rupee"></i></span>
                                                            <input type="number" step="0.01" class="form-control"
                                                                name="other_allowance" id="other_allowance"
                                                                value="<?= $other ?>" onkeyup="calculateGross()">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Effective From -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
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

                                                <!-- Statutory Details with UAN and ESI -->
                                                <div class="col-md-12">
                                                    <div class="statutory-section">
                                                        <div class="section-title">
                                                            <i class="bx bx-shield me-2"></i>Statutory Details
                                                        </div>
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <div class="d-flex gap-4 pt-1 mb-3 flex-wrap">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="epf_applicable" id="epf_applicable"
                                                                            <?= $epf ? 'checked' : '' ?>>
                                                                        <label class="form-check-label"
                                                                            for="epf_applicable">
                                                                            <strong>EPF Applicable</strong>
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="esi_applicable" id="esi_applicable"
                                                                            <?= $esi ? 'checked' : '' ?>>
                                                                        <label class="form-check-label"
                                                                            for="esi_applicable">
                                                                            <strong>ESI Applicable</strong>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- UAN No -->
                                                            <div class="col-md-6">
                                                                <div class="mb-0">
                                                                    <label class="form-label" for="uan_no">
                                                                        <i class="bx bx-id-card me-1"></i> UAN No.
                                                                        <small class="text-muted">(Universal Account
                                                                            Number)</small>
                                                                    </label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text"><i
                                                                                class="bx bx-hash"></i></span>
                                                                        <input type="text" class="form-control"
                                                                            name="uan_no" id="uan_no"
                                                                            value="<?= htmlspecialchars($uan_no) ?>"
                                                                            placeholder="Enter UAN Number (e.g. 123456789012)"
                                                                            maxlength="50">
                                                                    </div>
                                                                    <small class="text-muted">Format: 12-digit number
                                                                        (e.g., 123456789012)</small>
                                                                </div>
                                                            </div>

                                                            <!-- ESI No -->
                                                            <div class="col-md-6">
                                                                <div class="mb-0">
                                                                    <label class="form-label" for="esi_no">
                                                                        <i class="bx bx-id-card me-1"></i> ESI No.
                                                                        <small class="text-muted">(Employee State
                                                                            Insurance)</small>
                                                                    </label>
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text"><i
                                                                                class="bx bx-hash"></i></span>
                                                                        <input type="text" class="form-control"
                                                                            name="esi_no" id="esi_no"
                                                                            value="<?= htmlspecialchars($esi_no) ?>"
                                                                            placeholder="Enter ESI Number (e.g. 123456789012345)"
                                                                            maxlength="50">
                                                                    </div>
                                                                    <small class="text-muted">Format: 17-digit number
                                                                        (e.g., 123456789012345)</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Gross Salary Display -->
                                            <div class="row mt-4">
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
                                                        <div class="mt-2">
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
                                            <div class="mt-4">
                                                <button type="submit" name="update_salary" class="btn btn-primary">
                                                    <i class="bx bx-save me-1"></i> Save Salary Structure
                                                </button>
                                                <a href="list.php" class="btn btn-secondary ms-2">
                                                    <i class="bx bx-arrow-back me-1"></i> Cancel
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- History Section -->
                        <div class="row mt-4">
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
                                                                <td><?= ($h['epf_applicable'] ?? 1) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
                                                                </td>
                                                                <td><?= ($h['esi_applicable'] ?? 1) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
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
                                                            <td colspan="10" class="text-center text-muted py-4">
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

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <?php include('../includes/script.php'); ?>

    <script>
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
        document.addEventListener('DOMContentLoaded', function () {
            calculateGross();
        });

        // Validate UAN Number (12 digits)
        document.getElementById('uan_no').addEventListener('blur', function () {
            const val = this.value.trim();
            if (val && val.length > 0 && !/^\d{12}$/.test(val)) {
                iziToast.warning({
                    title: "Warning",
                    message: "UAN Number should be 12 digits",
                    position: "topRight"
                });
            }
        });

        // Validate ESI Number (17 digits)
        document.getElementById('esi_no').addEventListener('blur', function () {
            const val = this.value.trim();
            if (val && val.length > 0 && !/^\d{17}$/.test(val)) {
                iziToast.warning({
                    title: "Warning",
                    message: "ESI Number should be 17 digits",
                    position: "topRight"
                });
            }
        });
    </script>
</body>

</html>