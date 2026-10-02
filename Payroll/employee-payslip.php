<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// ============================================================
// CHECK LOGIN & GET USER DETAILS - FIXED
// ============================================================

// Check if user is logged in - FIXED: Check both session variables
if (!isset($_SESSION['user_id']) && !isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

include('../includes/header.php');
?>

<style>
    /* ============================================================
       LAYOUT - STICKY SIDEBAR
    ============================================================ */
    .layout-container {
        display: flex;
        min-height: 100vh;
        position: relative;
    }

    .layout-sidebar {
        position: sticky;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        flex-shrink: 0;
        width: 260px;
        
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

    /* ============================================================
       BREADCRUMB BOX STYLES
    ============================================================ */
    .breadcrumb-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .breadcrumb-box .breadcrumb-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 15px;
        font-weight: 500;
        color: #198754;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .breadcrumb-box .breadcrumb-item i {
        font-size: 18px;
        color: #198754;
    }

    .breadcrumb-box .breadcrumb-item:hover {
        color: #0f5132;
        text-decoration: underline;
    }

    .breadcrumb-box .breadcrumb-item.active {
        color: #1a2332;
        font-weight: 600;
        pointer-events: none;
    }

    .breadcrumb-box .breadcrumb-item.active i {
        color: #1a2332;
    }

    .breadcrumb-box .separator {
        color: #9ca3af;
        font-size: 16px;
        margin: 0 4px;
        font-weight: 600;
    }

    /* ============================================================
       PAYSLIP STYLES
    ============================================================ */
    .payslip-wrapper {
        max-width: 850px;
        margin: 0 auto;
        background: #ffffff;
        padding: 30px 35px 25px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        border: 1px solid #e8ecf1;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .payslip-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 3px solid #2c3e50;
        padding-bottom: 14px;
        margin-bottom: 20px;
    }

    .payslip-header .logo-section {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 0 0 auto;
    }

    .payslip-header .logo-section img {
        max-height: 60px;
        width: auto;
        object-fit: contain;
    }

    .payslip-header .center-section {
        text-align: center;
        flex: 1;
    }

    .payslip-header .company-name {
        font-size: 24px;
        font-weight: 800;
        color: #077BCC;
        letter-spacing: 3px;
        text-transform: uppercase;
        font-family: 'Arial Black', 'Segoe UI', sans-serif;
    }

    .payslip-header .company-location {
        font-size: 12px;
        color: #CC0607;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .payslip-header .payslip-title {
        font-size: 16px;
        font-weight: 700;
        color: #402B34;
        margin-top: 2px;
        letter-spacing: 0.5px;
    }

    .payslip-header .right-section {
        flex: 0 0 auto;
        width: 80px;
    }

    .payslip-employee-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #e8ecf1;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 14px;
    }

    .payslip-employee-table td {
        padding: 9px 16px;
        border: 1px solid #e8ecf1;
        font-size: 13px;
    }

    .payslip-employee-table .label {
        font-weight: 600;
        color: #7f8c8d;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #f8f9fb;
        width: 130px;
    }

    .payslip-employee-table .value {
        font-weight: 600;
        color: #2c3e50;
        font-size: 13px;
        background: #ffffff;
    }

    .payslip-main-table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0 14px;
        border: 1px solid #e8ecf1;
        border-radius: 8px;
        overflow: hidden;
    }

    .payslip-main-table td {
        padding: 8px 16px;
        border: 1px solid #e8ecf1;
        font-size: 13px;
        vertical-align: middle;
    }

    .payslip-main-table .section-header {
        background: #2c3e50;
        font-weight: 700;
        font-size: 13px;
        text-align: center;
        color: #ffffff;
        padding: 10px 14px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .payslip-main-table .label-cell {
        padding-left: 20px;
        font-weight: 500;
        color: #34495e;
        width: 35%;
    }

    .payslip-main-table .amount-cell {
        text-align: right;
        font-weight: 600;
        padding-right: 20px;
        color: #2c3e50;
        width: 15%;
    }

    .payslip-main-table .deduction-row .amount-cell {
        color: #e74c3c;
        font-weight: 700;
    }

    .payslip-main-table .deduction-row .label-cell {
        color: #e74c3c;
        font-weight: 600;
    }

    .payslip-main-table .empty-cell {
        background: #fafbfc;
    }

    .payslip-main-table .total-row td {
        font-weight: 700;
        border-top: 3px solid #2c3e50;
        padding-top: 10px;
        padding-bottom: 10px;
        font-size: 14px;
        background: #f8f9fb;
    }

    .payslip-main-table .total-row .amount-cell {
        font-size: 15px;
        color: #2c3e50;
        font-weight: 800;
    }

    .payslip-main-table .total-row.deduction-row td {
        background: #fdf2f2;
        border-top: 3px solid #e74c3c;
    }

    .payslip-main-table .total-row.deduction-row .amount-cell {
        color: #e74c3c;
        font-weight: 800;
    }

    .payslip-net-pay {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        padding: 14px 24px;
        border: 2px solid #2c3e50;
        border-radius: 8px;
        margin-bottom: 12px;
        background: #f8f9fb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .payslip-net-pay .amount {
        font-size: 22px;
        font-weight: 800;
        color: #2c3e50;
        padding: 4px 20px;
        background: #ffffff;
        border-radius: 6px;
        border: 1px solid #e8ecf1;
    }

    .payslip-employer-contrib {
        background: #f8f9fb;
        padding: 12px 20px;
        border: 1px solid #e8ecf1;
        border-radius: 8px;
        font-size: 13px;
        color: #7f8c8d;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 12px;
    }

    .payslip-footer {
        text-align: center;
        font-size: 11px;
        color: #bdc3c7;
        padding-top: 10px;
        border-top: 1px solid #e8ecf1;
        margin-top: 8px;
        letter-spacing: 0.3px;
    }

    /* Print Styles */
    @media print {
        .no-print {
            display: none !important;
        }
        @page {
            margin: 0.5cm;
        }
        body {
            background: #ffffff !important;
        }
        .layout-sidebar,
        .navbar,
        .breadcrumb-box,
        .footer {
            display: none !important;
        }
        .layout-page {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .payslip-wrapper {
            border: 1px solid #e8ecf1 !important;
            padding: 20px !important;
            box-shadow: none !important;
            max-width: 100% !important;
            margin: 0 !important;
        }
    }

    /* Responsive */
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
        .payslip-wrapper {
            padding: 15px;
        }
        .payslip-header {
            flex-direction: column;
            text-align: center;
        }
        .payslip-header .logo-section {
            flex-direction: column;
        }
        .payslip-header .company-name {
            font-size: 20px;
        }
        .payslip-net-pay {
            flex-direction: column;
            gap: 8px;
            text-align: center;
        }
        .payslip-employer-contrib {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .payslip-wrapper {
            padding: 10px;
        }
        .payslip-employee-table td {
            padding: 4px 6px;
            font-size: 10px;
        }
        .payslip-main-table td {
            padding: 4px 6px;
            font-size: 10px;
        }
        .payslip-header .company-name {
            font-size: 16px;
        }
        .payslip-net-pay .amount {
            font-size: 16px;
        }
    }
</style>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Side Menu -->
            <div class="layout-sidebar" id="sidebar">
                <?php include('../includes/sideMenu.php'); ?>
            </div>

            <!-- Mobile Sidebar Overlay -->
            <div class="layout-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

            <!-- Page Content -->
            <div class="layout-page">

                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>

                <?php 
                // ============================================================
                // GET USER DETAILS - FIXED
                // ============================================================
                $loggedInUserId = $_SESSION['user_id'] ?? 0;
                $loggedInUsername = $_SESSION['loggedInUser'] ?? '';
                $loggedInUserRank = $_SESSION['user_rank'] ?? 'employee';

                // If user_id is not set, get it from database
                if ($loggedInUserId == 0 && !empty($loggedInUsername)) {
                    $userCheck = "SELECT id, rank FROM tbl_user WHERE username = '" . mysqli_real_escape_string($conn, $loggedInUsername) . "' AND status = '1'";
                    $userResult = mysqli_query($conn, $userCheck);
                    if ($userRow = mysqli_fetch_assoc($userResult)) {
                        $loggedInUserId = $userRow['id'];
                        $_SESSION['user_id'] = $loggedInUserId;
                        $_SESSION['user_rank'] = $userRow['rank'];
                    }
                }

                // If still no user ID, show error
                if ($loggedInUserId == 0) {
                    echo '<div class="container mt-5">
                        <div class="alert alert-danger">
                            <h4>Error</h4>
                            <p>Unable to identify user. Please <a href="../login.php">login again</a>.</p>
                        </div>
                    </div>';
                    include('../includes/footer.php');
                    exit();
                }

                // Get employee details
                $empQuery = "SELECT id, username, firstName, lastName, rank, basic_salary, gross_salary, uan_no, esi_no,
                              department_id, designation_id, status
                            FROM tbl_user 
                            WHERE id = " . (int) $loggedInUserId;
                $empResult = mysqli_query($conn, $empQuery);
                $employee = mysqli_fetch_assoc($empResult);

                if (!$employee || $employee['status'] != '1') {
                    echo '<div class="container mt-5">
                        <div class="alert alert-danger">
                            <h4>Account Issue</h4>
                            <p>Your account could not be found or is inactive.</p>
                            <a href="../logout.php" class="btn btn-danger">Logout</a>
                        </div>
                    </div>';
                    include('../includes/footer.php');
                    exit();
                }

                // Get department and designation names
                $deptName = 'N/A';
                $desigName = 'N/A';

                if (!empty($employee['department_id'])) {
                    $deptQuery = "SELECT department_name FROM tbl_department WHERE id = " . $employee['department_id'];
                    $deptResult = mysqli_query($conn, $deptQuery);
                    if ($deptResult && mysqli_num_rows($deptResult) > 0) {
                        $deptRow = mysqli_fetch_assoc($deptResult);
                        $deptName = $deptRow['department_name'];
                    }
                }

                if (!empty($employee['designation_id'])) {
                    $desigQuery = "SELECT designation_name FROM tbl_designation WHERE id = " . $employee['designation_id'];
                    $desigResult = mysqli_query($conn, $desigQuery);
                    if ($desigResult && mysqli_num_rows($desigResult) > 0) {
                        $desigRow = mysqli_fetch_assoc($desigResult);
                        $desigName = $desigRow['designation_name'];
                    }
                }

                // Employee name
                $employeeName = trim(($employee['firstName'] ?? '') . ' ' . ($employee['lastName'] ?? ''));
                if (empty($employeeName)) {
                    $employeeName = $employee['username'] ?? 'Unknown';
                }

                // ============================================================
                // GET PAYROLL DATA - FIXED
                // ============================================================
                $payroll_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

                // If no payroll ID, get the latest
                if ($payroll_id == 0) {
                    $latestQuery = "SELECT id FROM tbl_payroll 
                                    WHERE employee_id = $loggedInUserId 
                                    AND status = '1' 
                                    ORDER BY pay_month DESC, id DESC 
                                    LIMIT 1";
                    $latestResult = mysqli_query($conn, $latestQuery);
                    if ($latestResult && mysqli_num_rows($latestResult) > 0) {
                        $latestRow = mysqli_fetch_assoc($latestResult);
                        $payroll_id = $latestRow['id'];
                    }
                }

                // Get payroll details - FIXED: Removed email_id
                $payroll = null;
                if ($payroll_id > 0) {
                    $query = "SELECT p.*, 
                              u.firstName, u.lastName, u.mobile, 
                              u.username, u.rank, u.department,
                              u.uan_no, u.esi_no
                              FROM tbl_payroll p 
                              LEFT JOIN tbl_user u ON p.employee_id = u.id 
                              WHERE p.id = $payroll_id AND p.status='1' AND p.employee_id = $loggedInUserId";
                    $result = mysqli_query($conn, $query);
                    $payroll = mysqli_fetch_assoc($result);
                }

                // If no payroll found
                if (!$payroll) {
                    ?>
                    <div class="content-wrapper">
                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="breadcrumb-box">
                                <a href="../dashboard/employee-dashboard.php" class="breadcrumb-item">
                                    <i class="bx bx-home"></i> Dashboard
                                </a>
                                <span class="separator">›</span>
                                <a href="../attendance/employee-attendance.php" class="breadcrumb-item">
                                    <i class="bx bx-calendar-check"></i> Attendance
                                </a>
                                <span class="separator">›</span>
                                <span class="breadcrumb-item active">
                                    <i class="bx bx-receipt"></i> My Payslip
                                </span>
                            </div>
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <div style="font-size: 64px; color: #d1d5db; margin-bottom: 20px;">📄</div>
                                    <h4 style="color: #6b7a8f;">No Payslip Found</h4>
                                    <p style="color: #9ca3af;">You don't have any payroll records yet.</p>
                                    <a href="../attendance/employee-attendance.php" class="btn btn-primary mt-3">
                                        <i class="bx bx-arrow-back me-1"></i> Back to Attendance
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    include('../includes/footer.php');
                    include('../includes/script.php');
                    exit();
                }

                // ============================================================
                // CALCULATE VALUES
                // ============================================================
                $month = $payroll['pay_month'];
                $year = date('Y', strtotime($month));
                $monthNum = date('m', strtotime($month));
                $totalWorkingDays = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);

                // Get attendance
                $attQuery = "SELECT 
                                COUNT(*) as total_days,
                                SUM(CASE WHEN attendance_type_id = 1 THEN 1 ELSE 0 END) as present_days,
                                SUM(CASE WHEN attendance_type_id = 3 THEN 1 ELSE 0 END) as half_days,
                                SUM(CASE WHEN attendance_type_id = 4 THEN 1 ELSE 0 END) as leave_days,
                                SUM(CASE WHEN attendance_type_id = 5 THEN 1 ELSE 0 END) as holidays,
                                SUM(CASE WHEN attendance_type_id = 6 THEN 1 ELSE 0 END) as week_offs
                             FROM tbl_user_attendance 
                             WHERE employee_id = $loggedInUserId 
                             AND DATE_FORMAT(date, '%Y-%m') = '$month'
                             AND status = '1'";
                $attResult = mysqli_query($conn, $attQuery);
                $attData = mysqli_fetch_assoc($attResult);

                if ($attData && ($attData['total_days'] ?? 0) > 0) {
                    $payableDays = ($attData['present_days'] ?? 0) + ($attData['leave_days'] ?? 0) + ($attData['holidays'] ?? 0) + ($attData['week_offs'] ?? 0) + (($attData['half_days'] ?? 0) * 0.5);
                } else {
                    $payableDays = $payroll['payable_days'] ?? 0;
                }

                // Calculate values
                $basicSalary = $payroll['basic_salary'] ?? 0;
                $da = $basicSalary * 0.08;
                $hra = $basicSalary * 0.0065;
                $otherAllowance = ($payroll['gross_salary'] ?? 0) - $basicSalary - $da - $hra;
                $totalDeductions = ($payroll['pf_deduction'] ?? 0) + ($payroll['esi_deduction'] ?? 0) + ($payroll['other_deduction'] ?? 0) + ($payroll['pt_deduction'] ?? 0);

                $uan_no = $employee['uan_no'] ?? 'N/A';
                $esi_no = $employee['esi_no'] ?? 'N/A';

                $pageTitle = 'My Payslip';
                ?>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                            <div class="breadcrumb-box">
                                <a href="../dashboard/employee-dashboard.php" class="breadcrumb-item">
                                    <i class="bx bx-home"></i> Dashboard
                                </a>
                                <span class="separator">›</span>
                                <a href="../attendance/employee-attendance.php" class="breadcrumb-item">
                                    <i class="bx bx-calendar-check"></i> Attendance
                                </a>
                                <span class="separator">›</span>
                                <span class="breadcrumb-item active">
                                    <i class="bx bx-receipt"></i> My Payslip
                                </span>
                            </div>
                            <div>
                                <button onclick="window.print()" class="btn btn-primary btn-sm"
                                    style="background:#2c3e50;border-color:#2c3e50;border-radius:6px;padding:8px 18px;">
                                    <i class="bx bx-printer me-1"></i> Print
                                </button>
                                <a href="../attendance/employee-attendance.php" class="btn btn-secondary btn-sm"
                                    style="border-radius:6px;padding:8px 18px;">
                                    <i class="bx bx-arrow-back me-1"></i> Back
                                </a>
                            </div>
                        </div>

                        <!-- Payslip -->
                        <div class="payslip-wrapper" id="payslip">

                            <!-- Header -->
                            <div class="payslip-header">
                                <div class="logo-section">
                                    <img src="../assets/img/logos/kurakulas.png" alt="KURAKULAS Logo" onerror="this.style.display='none'">
                                </div>
                                <div class="center-section">
                                    <div class="company-name">KURAKULA'S</div>
                                    <div class="company-location">HYDERABAD</div>
                                    <div class="payslip-title">Payslip &mdash;
                                        <?= date('F Y', strtotime($payroll['pay_month'] . '-01')) ?></div>
                                </div>
                                <div class="right-section"></div>
                            </div>

                            <!-- Employee Details -->
                            <table class="payslip-employee-table">
                                <tr>
                                    <td class="label">Employee Code</td>
                                    <td class="value"><?= htmlspecialchars($employee['username'] ?? 'N/A') ?></td>
                                    <td class="label">Designation</td>
                                    <td class="value"><?= htmlspecialchars($desigName) ?></td>
                                </tr>
                                <tr>
                                    <td class="label">Name</td>
                                    <td class="value"><?= htmlspecialchars($employeeName) ?></td>
                                    <td class="label">Department</td>
                                    <td class="value"><?= htmlspecialchars($deptName) ?></td>
                                </tr>
                                <tr>
                                    <td class="label">UAN No</td>
                                    <td class="value"><?= htmlspecialchars($uan_no) ?></td>
                                    <td class="label">ESI No</td>
                                    <td class="value"><?= htmlspecialchars($esi_no) ?></td>
                                </tr>
                                <tr>
                                    <td class="label">Payable Days</td>
                                    <td class="value"><?= number_format($payableDays, 1) ?></td>
                                    <td class="label">Total Days</td>
                                    <td class="value"><?= $totalWorkingDays ?></td>
                                </tr>
                            </table>

                            <!-- Main Table -->
                            <table class="payslip-main-table">
                                <tr>
                                    <td class="section-header" colspan="2">Earnings</td>
                                    <td class="section-header" colspan="2">Deductions</td>
                                </tr>
                                <tr>
                                    <td class="label-cell">Basic</td>
                                    <td class="amount-cell">₹ <?= number_format($basicSalary, 2) ?></td>
                                    <td class="label-cell deduction-row">EPF (Employee)</td>
                                    <td class="amount-cell deduction-row">₹ <?= number_format($payroll['pf_deduction'] ?? 0, 2) ?></td>
                                </tr>
                                <tr>
                                    <td class="label-cell">DA</td>
                                    <td class="amount-cell">₹ <?= number_format($da, 2) ?></td>
                                    <td class="label-cell deduction-row">ESI (Employee)</td>
                                    <td class="amount-cell deduction-row">₹ <?= number_format($payroll['esi_deduction'] ?? 0, 2) ?></td>
                                </tr>
                                <tr>
                                    <td class="label-cell">HRA</td>
                                    <td class="amount-cell">₹ <?= number_format($hra, 2) ?></td>
                                    <td class="label-cell deduction-row">Other Deductions</td>
                                    <td class="amount-cell deduction-row">₹ <?= number_format(($payroll['other_deduction'] ?? 0) + ($payroll['pt_deduction'] ?? 0), 2) ?></td>
                                </tr>
                                <tr>
                                    <td class="label-cell">Other Allowance</td>
                                    <td class="amount-cell">₹ <?= number_format($otherAllowance, 2) ?></td>
                                    <td class="empty-cell"></td>
                                    <td class="empty-cell"></td>
                                </tr>
                                <tr class="total-row">
                                    <td class="label-cell"><strong>Gross Earnings</strong></td>
                                    <td class="amount-cell"><strong>₹ <?= number_format($payroll['gross_salary'] ?? 0, 2) ?></strong></td>
                                    <td class="label-cell deduction-row"><strong>Total Deductions</strong></td>
                                    <td class="amount-cell deduction-row"><strong>₹ <?= number_format($totalDeductions, 2) ?></strong></td>
                                </tr>
                            </table>

                            <!-- Net Pay -->
                            <div class="payslip-net-pay">
                                <span class="net-label"><strong>Net Pay:</strong></span>
                                <span class="amount">₹ <?= number_format($payroll['net_salary'] ?? 0, 2) ?></span>
                            </div>

                            <!-- Employer Contributions -->
                            <div class="payslip-employer-contrib">
                                <span><strong>Employer EPF Contribution:</strong> <span class="epf-amount">₹ <?= number_format($payroll['pf_deduction'] ?? 0, 2) ?></span></span>
                                <span class="contrib-divider">|</span>
                                <span><strong>Employer ESI Contribution:</strong> <span class="esi-amount">₹ <?= number_format($payroll['esi_deduction'] ?? 0, 2) ?></span></span>
                            </div>

                            <!-- Footer -->
                            <div class="payslip-footer">
                                This is a system-generated payslip.
                            </div>

                        </div>
                        <!-- End Payslip -->

                    </div>
                    <?php include('../includes/footer.php'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php include('../includes/script.php'); ?>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        // Close sidebar on mobile
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
    </script>
</body>
</html>