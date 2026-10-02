<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

if (!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================================================
// GENERATE PAYROLL WITH DATE RANGE
// ============================================================
if (isset($_POST['generate_payroll'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    
    // Validate dates
    if (empty($from_date) || empty($to_date)) {
        echo '<script>
            iziToast.error({
                title: "Error",
                message: "Please select both From and To dates",
                position: "topRight"
            });
        </script>';
    } else {
        // Calculate month and year for display
        $month = date('n', strtotime($from_date));
        $year = date('Y', strtotime($from_date));
        $monthName = date('F', mktime(0, 0, 0, $month, 1, $year));
        
        // Get pay period for database
        $pay_month = sprintf('%04d-%02d', $year, $month);
        
        // Calculate total working days in the date range
        $from = new DateTime($from_date);
        $to = new DateTime($to_date);
        $to->modify('+1 day'); // Include end date
        $interval = $from->diff($to);
        $totalWorkingDays = $interval->days;
        
        $empQuery = "SELECT id, firstName, lastName, basic_salary, gross_salary, 
                     da, house_rent_allowance, other_allowances,
                     uan_no, esi_no,
                     epf_applicable, esi_applicable 
                     FROM tbl_user WHERE status='1' AND rank != 'superadmin'";
        $empResult = $conn->query($empQuery);
        $processedCount = 0;
        $debugMessages = [];

        while ($employee = $empResult->fetch_assoc()) {
            $empId = $employee['id'];
            $gross_salary = $employee['gross_salary'] ?? 0;
            $basic_salary = $employee['basic_salary'] ?? 0;
            $da = $employee['da'] ?? 0;
            $hra = $employee['house_rent_allowance'] ?? 0;
            $other_allowance = $employee['other_allowances'] ?? 0;
            $employeeName = $employee['firstName'] . ' ' . $employee['lastName'];

            $uan_no = $employee['uan_no'] ?? '';
            $esi_no = $employee['esi_no'] ?? '';

            // Employee-level statutory flags
            $emp_epf_applicable = (int) ($employee['epf_applicable'] ?? 0);
            $emp_esi_applicable = (int) ($employee['esi_applicable'] ?? 0);

            if ($gross_salary <= 0) {
                $debugMessages[] = "❌ $employeeName - No salary set in salary page";
                continue;
            }

            // Updated attendance query with date range
            $attQuery = "SELECT 
                            COUNT(*) as total_days,
                            SUM(CASE WHEN attendance_type_id = 1 THEN 1 ELSE 0 END) as present_days,
                            SUM(CASE WHEN attendance_type_id = 2 THEN 1 ELSE 0 END) as absent_days,
                            SUM(CASE WHEN attendance_type_id = 3 THEN 1 ELSE 0 END) as half_days,
                            SUM(CASE WHEN attendance_type_id = 4 THEN 1 ELSE 0 END) as leave_days,
                            SUM(CASE WHEN attendance_type_id = 5 THEN 1 ELSE 0 END) as holidays,
                            SUM(CASE WHEN attendance_type_id = 6 THEN 1 ELSE 0 END) as week_offs
                         FROM tbl_user_attendance 
                         WHERE employee_id = $empId 
                         AND date BETWEEN '$from_date' AND '$to_date'
                         AND status = '1'";

            $attResult = $conn->query($attQuery);
            $attData = $attResult->fetch_assoc();

            if (!$attData || ($attData['total_days'] ?? 0) == 0) {
                $debugMessages[] = "❌ $employeeName - No attendance marked in attendance page for selected date range";
                continue;
            }

            $present_days = $attData['present_days'] ?? 0;
            $half_days = $attData['half_days'] ?? 0;
            $leave_days = $attData['leave_days'] ?? 0;
            $holidays = $attData['holidays'] ?? 0;
            $week_offs = $attData['week_offs'] ?? 0;

            $effective_present = $present_days + $leave_days + $holidays + $week_offs + ($half_days * 0.5);
            $payableDays = $effective_present;

            $daily_salary = $gross_salary / $totalWorkingDays;
            $net_salary = $effective_present * $daily_salary;
            if ($net_salary <= 0)
                $net_salary = 0;

            // ============================================================
            // STATUTORY DEDUCTIONS - CORRECTED LOGIC
            // ============================================================
            // Rule:
            //   - PT is ALWAYS a FIXED amount (never percentage-based)
            //   - PF applies if: EPF Applicable flag = 1 AND gross > ₹15,000
            //   - ESI applies if: ESI Applicable flag = 1 AND gross <= ₹15,000
            // ============================================================
            $salaryThreshold = 15000;

            $pf = 0;
            $esi = 0;
            $pt = 0;
            $other_deduction = 0;

            $configQuery = mysqli_query($conn, "SELECT * FROM tbl_statutory_config WHERE status='1'");
            if ($configQuery) {
                while ($cfg = mysqli_fetch_assoc($configQuery)) {
                    
                    // ============================================================
                    // PT - ALWAYS FIXED AMOUNT (Never percentage)
                    // ============================================================
                    // This is the KEY FIX. PT is a flat deduction, not a %
                    // calculation. So we always use fixed_amount directly.
                    // ============================================================
                    if (stripos($cfg['config_name'], 'Professional Tax') !== false || stripos($cfg['config_name'], 'PT') !== false) {
                        $pt += (float) ($cfg['fixed_amount'] ?? 200);
                        continue;
                    }
                    
                    // ============================================================
                    // PF - PERCENTAGE OF BASIC SALARY
                    // ============================================================
                    if (stripos($cfg['config_name'], 'PF') !== false) {
                        $pfPercent = (float) $cfg['employee_contribution_pct'];
                        $pfAmount = $basic_salary * ($pfPercent / 100);
                        
                        if ($emp_epf_applicable && $gross_salary > $salaryThreshold) {
                            $pf += $pfAmount;
                        }
                        continue;
                    }
                    
                    // ============================================================
                    // ESI - PERCENTAGE OF GROSS SALARY
                    // ============================================================
                    if (stripos($cfg['config_name'], 'ESI') !== false) {
                        $esiPercent = (float) $cfg['employee_contribution_pct'];
                        $esiAmount = $gross_salary * ($esiPercent / 100);
                        
                        if ($emp_esi_applicable && $gross_salary <= $salaryThreshold) {
                            $esi += $esiAmount;
                        }
                        continue;
                    }
                    
                    // ============================================================
                    // OTHER DEDUCTIONS
                    // ============================================================
                    $base = $cfg['applicable_on'] === 'basic_salary' ? $basic_salary : $gross_salary;
                    $amount = $cfg['applicable_on'] === 'fixed'
                        ? (float) $cfg['fixed_amount']
                        : $base * ((float) $cfg['employee_contribution_pct'] / 100);
                    $other_deduction += $amount;
                }
            }

            $final_net = $net_salary - $pf - $esi - $pt - $other_deduction;
            if ($final_net < 0)
                $final_net = 0;

            $checkSql = "SELECT id FROM tbl_payroll WHERE employee_id = $empId AND pay_month = '$pay_month' AND status = '1'";
            $checkResult = $conn->query($checkSql);

            if ($checkResult && $checkResult->num_rows > 0) {
                $updateSql = "UPDATE tbl_payroll SET 
                                basic_salary = '$basic_salary',
                                gross_salary = '$gross_salary',
                                da = '$da',
                                hra = '$hra',
                                other_allowance = '$other_allowance',
                                pf_deduction = '$pf',
                                esi_deduction = '$esi',
                                pt_deduction = '$pt',
                                other_deduction = '$other_deduction',
                                payable_days = '$payableDays',
                                net_salary = '$final_net',
                                uan_no = '$uan_no',
                                esi_no = '$esi_no',
                                from_date = '$from_date',
                                to_date = '$to_date'
                              WHERE employee_id = $empId AND pay_month = '$pay_month'";
                $conn->query($updateSql);
                $debugMessages[] = "✅ $employeeName - UPDATED (PF: ₹$pf, ESI: ₹$esi, PT: ₹$pt, Net: ₹$final_net)";
            } else {
                $insertSql = "INSERT INTO tbl_payroll 
                              (employee_id, pay_month, basic_salary, gross_salary, 
                               da, hra, other_allowance,
                               pf_deduction, esi_deduction, pt_deduction, other_deduction, 
                               payable_days, net_salary, uan_no, esi_no,
                               from_date, to_date,
                               payment_status, status, created_at)
                              VALUES 
                              ('$empId', '$pay_month', '$basic_salary', '$gross_salary', 
                               '$da', '$hra', '$other_allowance',
                               '$pf', '$esi', '$pt', '$other_deduction', 
                               '$payableDays', '$final_net', '$uan_no', '$esi_no',
                               '$from_date', '$to_date',
                               'draft', '1', NOW())";
                $conn->query($insertSql);
                $debugMessages[] = "✅ $employeeName - INSERTED (PF: ₹$pf, ESI: ₹$esi, PT: ₹$pt, Net: ₹$final_net)";
            }
            $processedCount++;
        }

        if ($processedCount > 0) {
            echo '<script>
                iziToast.success({
                    title: "Success",
                    message: "Payroll generated for ' . date('d M Y', strtotime($from_date)) . ' to ' . date('d M Y', strtotime($to_date)) . ' for ' . $processedCount . ' employees!",
                    position: "topRight"
                });
                setTimeout(() => { window.location.href = "add.php"; }, 1500);
            </script>';
        } else {
            echo '<div style="background:#fff3cd;padding:20px;margin:20px;border:1px solid #ffc107;border-radius:8px;">';
            echo '<h5 style="color:#856404;">⚠️ No Payroll Generated</h5>';
            echo '<pre style="font-size:13px;background:#f8f9fa;padding:15px;border-radius:5px;overflow:auto;max-height:400px;">';
            print_r($debugMessages);
            echo '</pre>';
            echo '<p><strong>Possible Reasons:</strong></p>';
            echo '<ul>';
            echo '<li>No employees found with status="1"</li>';
            echo '<li>Employees have gross_salary = 0 (Set salary in Salary page)</li>';
            echo '<li>No attendance records for selected date range (Mark attendance in Attendance page)</li>';
            echo '</ul>';
            echo '</div>';

            echo '<script>
                iziToast.warning({
                    title: "Warning",
                    message: "No payroll generated. Check debug information below.",
                    position: "topRight"
                });
            </script>';
        }
    }
}

$pageTitle = 'Generate Payroll';
include('../includes/header.php');
?>

<style>
    /* ============================================================
       PROFESSIONAL LAYOUT - FIXED HEIGHT & SIDEBAR SCROLLING
    ============================================================ */
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .layout-wrapper {
        height: 100vh;
        overflow: hidden;
    }

    .layout-container {
        display: flex;
        height: 100vh;
        overflow: hidden;
    }

    .layout-container .menu-vertical,
    .layout-container .layout-sidebar,
    .layout-container .layout-menu {
        flex-shrink: 0;
        width: 260px;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        position: sticky;
        top: 0;
        background: #1a2332;
        z-index: 1000;

        /* REMOVE ANY BLACK LINE */
        border-right: none !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .layout-container .menu-vertical::-webkit-scrollbar,
    .layout-container .layout-sidebar::-webkit-scrollbar,
    .layout-container .layout-menu::-webkit-scrollbar {
        width: 5px;
    }

    .layout-container .menu-vertical::-webkit-scrollbar-track,
    .layout-container .layout-sidebar::-webkit-scrollbar-track,
    .layout-container .layout-menu::-webkit-scrollbar-track {
        background: #1a2332;
    }

    .layout-container .menu-vertical::-webkit-scrollbar-thumb,
    .layout-container .layout-sidebar::-webkit-scrollbar-thumb,
    .layout-container .layout-menu::-webkit-scrollbar-thumb {
        background: #4a5568;
        border-radius: 5px;
    }

    .layout-page {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: hidden;
        background: #f4f6f9;
    }

    .content-wrapper {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 0;
    }

    .content-wrapper::-webkit-scrollbar {
        width: 6px;
    }

    .content-wrapper::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 6px;
    }

    /* ============================================================
       PROFESSIONAL CARD DESIGN - CLEAN & MODERN
    ============================================================ */
    .payroll-wrapper {
        max-width: 900px;
        margin: 20px auto;
        padding: 0 15px;
    }

    .page-header {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px 25px;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-header .header-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #f0f0ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #696cff;
        flex-shrink: 0;
    }

    .page-header h4 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        color: #1a2332;
    }

    .page-header p {
        margin: 3px 0 0 0;
        font-size: 13px;
        color: #718096;
    }

    .payroll-card {
        border: none;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .payroll-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 18px 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .payroll-card .card-header h5 {
        font-weight: 600;
        color: #1a2332;
        font-size: 15px;
        margin: 0;
    }

    .payroll-card .card-header i {
        color: #696cff;
        font-size: 20px;
    }

    .payroll-card .card-body {
        padding: 25px;
    }

    /* Info Banner */
    .info-banner {
        background: #f8f9fc;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 14px 18px;
        font-size: 13px;
        color: #4a5568;
        margin-bottom: 25px;
    }

    .info-banner i {
        color: #696cff;
        margin-right: 8px;
    }

    .info-banner strong {
        color: #1a2332;
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        font-size: 13px;
        color: #4a5568;
        margin-bottom: 6px;
        display: block;
    }

    .form-select-modern {
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        padding: 11px 15px;
        color: #1a2332;
        background: #ffffff;
        transition: all 0.2s ease;
        width: 100%;
        height: 45px;
        appearance: none;
        -webkit-appearance: none;
    }

    .form-select-modern:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.08);
        outline: none;
    }

    /* Professional Button */
    .btn-generate-modern {
        background: #696cff;
        color: #ffffff;
        border: none;
        padding: 13px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        cursor: pointer;
        width: 100%;
        letter-spacing: 0.3px;
        margin-top: 10px;
    }

    .btn-generate-modern:hover {
        background: #5a5de0;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.2);
    }

    .btn-generate-modern i {
        margin-right: 8px;
    }

    /* Past Runs Table */
    .past-runs-card {
        margin-top: 5px;
    }

    .past-runs-card .card-header {
        justify-content: space-between;
    }

    .table-past-runs-modern {
        width: 100%;
        border-collapse: collapse;
    }

    .table-past-runs-modern thead th {
        background: #f8f9fc;
        color: #718096;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 15px;
        border-bottom: 1px solid #e9ecef;
        text-align: left;
    }

    .table-past-runs-modern tbody td {
        padding: 14px 15px;
        border-bottom: 1px solid #f1f3f5;
        vertical-align: middle;
        font-size: 14px;
        color: #1a2332;
    }

    .table-past-runs-modern tbody tr:hover td {
        background: #f8f9fc;
    }

    .table-past-runs-modern tbody tr:last-child td {
        border-bottom: none;
    }

    .badge-modern {
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 11px;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-modern.draft {
        background: #f1f3f5;
        color: #4a5568;
    }

    .badge-modern.paid {
        background: #dcfce7;
        color: #166534;
    }

    .badge-modern.pending {
        background: #fef9c3;
        color: #713f12;
    }

    .btn-view-modern {
        background: lightgreen;
        color: #1a2332;
        border: 1px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }

    .btn-view-modern:hover {
        background: green;
        border-color: #cbd5e0;
        color: #1a2332;
    }

    .btn-refresh-modern {
        background: #f8f9fc;
        color: #1a2332;
        border: 1px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-refresh-modern:hover {
        background: #edf2f7;
        border-color: #cbd5e0;
    }

    .empty-state-modern {
        padding: 40px 20px;
        text-align: center;
    }

    .empty-state-modern i {
        font-size: 48px;
        color: #cbd5e0;
        display: block;
        margin-bottom: 15px;
    }

    .empty-state-modern h6 {
        color: #4a5568;
        font-weight: 600;
        margin: 0 0 5px 0;
    }

    .empty-state-modern p {
        color: #9ca3af;
        font-size: 13px;
        margin: 0;
    }

    .employee-count-modern {
        font-size: 12px;
        color: #9ca3af;
        margin-left: 5px;
    }

    .date-range-summary {
        background: #e8f5e9;
        border: 1px solid #4caf50;
        border-radius: 8px;
        padding: 12px 18px;
        margin-top: 15px;
        font-size: 14px;
        color: #1a2332;
    }

    .date-range-summary i {
        color: #4caf50;
        margin-right: 8px;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 16px 18px;
        }

        .page-header .header-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }

        .page-header h4 {
            font-size: 17px;
        }

        .payroll-card .card-body {
            padding: 18px;
        }

        .btn-generate-modern {
            padding: 12px 20px;
            font-size: 13px;
        }

        .table-past-runs-modern thead th {
            font-size: 10px;
            padding: 10px;
        }

        .table-past-runs-modern tbody td {
            padding: 10px;
            font-size: 12px;
        }

        .layout-container .menu-vertical,
        .layout-container .layout-sidebar {
            width: 220px;
        }
    }

    /* Breadcrumb Styles */
    .breadcrumb-box {
        background: #f8f9fa !important;
        border: 1px solid #e9ecef !important;
        border-radius: 10px !important;
        padding: 12px 20px !important;
        margin-bottom: 20px !important;
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        gap: 8px !important;
    }

    .breadcrumb-box .breadcrumb-item {
        display: inline-flex !important;
        align-items: center !important;
        gap: 6px !important;
        font-size: 15px !important;
        font-weight: 500 !important;
        color: #198754 !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
    }

    .breadcrumb-box .breadcrumb-item i {
        font-size: 18px !important;
        color: #198754 !important;
    }

    .breadcrumb-box .breadcrumb-item:hover {
        color: #0f5132 !important;
        text-decoration: underline !important;
    }

    .breadcrumb-box .breadcrumb-item.active {
        color: #1a2332 !important;
        font-weight: 600 !important;
        pointer-events: none !important;
    }

    .breadcrumb-box .breadcrumb-item.active i {
        color: #1a2332 !important;
    }

    .breadcrumb-box .separator {
        color: #9ca3af !important;
        font-size: 16px !important;
        margin: 0 4px !important;
        font-weight: 600 !important;
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
                        <div class="payroll-wrapper">

                            <!-- Breadcrumb -->
                            <div class="breadcrumb-box">
                                <a href="../dashboard/superAdmin" class="breadcrumb-item">
                                    <i class="bx bx-home"></i> Dashboard
                                </a>
                                <span class="separator">›</span>
                                <a href="#" class="breadcrumb-item">
                                    <i class="bx bx-briefcase"></i> Payroll
                                </a>
                                <span class="separator">›</span>
                                <span class="breadcrumb-item active">
                                    <i class="bx bx-calculator"></i> Generate Payroll
                                </span>
                            </div>

                            <!-- Professional Page Header -->
                            <div class="page-header">
                                <div class="header-icon">
                                    <i class="bx bx-calculator"></i>
                                </div>
                                <div>
                                    <h4>Generate Payroll</h4>
                                    <p>Generate payroll from salary + attendance data for selected date range</p>
                                </div>
                            </div>

                            <!-- ============================================================
                                 GENERATE PAYROLL CARD
                            ============================================================ -->
                            <div class="card payroll-card">
                                <div class="card-header">
                                    <i class="bx bx-calendar-check"></i>
                                    <h5>Generate Payroll for Date Range</h5>
                                </div>
                                <div class="card-body">

                                    <!-- Info Banner -->
                                    <div class="info-banner">
                                        <i class="bx bx-info-circle"></i>
                                        <strong>Note:</strong> Pulls attendance from <strong>Attendance Register</strong> 
                                        for selected date range + current salary from <strong>Salary Structure</strong> 
                                        for all active employees and computes payslips.
                                        <br><br>
                                        <strong>Statutory Rule:</strong> <strong>PF is optional</strong> — deducted only if 
                                        EPF Applicable is enabled in Salary page AND gross salary &gt; ₹15,000. 
                                        <strong>ESI is optional</strong> — deducted only if ESI Applicable is enabled 
                                        AND gross salary ≤ ₹15,000. PT is a <strong>fixed amount</strong> (not percentage-based).
                                    </div>

                                    <form action="" method="POST" id="payrollForm">
                                        <div class="row g-3">
                                            <!-- From Date -->
                                            <div class="col-md-6">
                                                <label class="form-label" for="from_date">From Date <span style="color:red;">*</span></label>
                                                <input type="date" id="from_date" name="from_date" class="form-select-modern" 
                                                       value="<?= date('Y-m-01') ?>" required>
                                            </div>

                                            <!-- To Date -->
                                            <div class="col-md-6">
                                                <label class="form-label" for="to_date">To Date <span style="color:red;">*</span></label>
                                                <input type="date" id="to_date" name="to_date" class="form-select-modern" 
                                                       value="<?= date('Y-m-d') ?>" required>
                                            </div>
                                        </div>

                                        <!-- Selected Period Summary -->
                                        <div class="date-range-summary" id="dateRangeSummary">
                                            <i class="bx bx-calendar-range"></i>
                                            <strong>Selected Period:</strong> 
                                            <span id="periodDisplay"><?= date('d M Y', strtotime(date('Y-m-01'))) ?> to <?= date('d M Y') ?></span>
                                        </div>

                                        <!-- Generate Button -->
                                        <div class="mt-4">
                                            <button type="submit" name="generate_payroll" class="btn-generate-modern"
                                                    onclick="return confirm('This will generate payroll for all employees. Continue?');">
                                                <i class="bx bx-play-circle"></i> Generate Payroll
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>

                            <!-- ============================================================
                                 PAST RUNS SECTION
                            ============================================================ -->
                            <div class="card payroll-card past-runs-card">
                                <div class="card-header">
                                    <i class="bx bx-history"></i>
                                    <h5>Past Runs</h5>
                                    <button onclick="window.location.reload()" class="btn-refresh-modern"
                                            style="margin-left:auto;">
                                        <i class="bx bx-refresh"></i> Refresh
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table-past-runs-modern">
                                            <thead>
                                                <tr>
                                                    <th>Period</th>
                                                    <th>Date Range</th>
                                                    <th>Status</th>
                                                    <th>Generated</th>
                                                    <th style="text-align:right;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $payrollQuery = "SELECT pay_month, from_date, to_date, COUNT(*) as employee_count, MAX(created_at) as generated_at, MAX(payment_status) as status
                                                                FROM tbl_payroll WHERE status='1' GROUP BY pay_month, from_date, to_date ORDER BY pay_month DESC, created_at DESC LIMIT 20";
                                                $payrollResult = $conn->query($payrollQuery);

                                                if ($payrollResult && $payrollResult->num_rows > 0) {
                                                    while ($row = $payrollResult->fetch_assoc()) {
                                                        $monthYear = date('F Y', strtotime($row['pay_month'] . '-01'));
                                                        $dateRange = '';
                                                        if (!empty($row['from_date']) && !empty($row['to_date'])) {
                                                            $dateRange = date('d M', strtotime($row['from_date'])) . ' - ' . date('d M Y', strtotime($row['to_date']));
                                                        } else {
                                                            $dateRange = 'Full Month';
                                                        }
                                                        $status = $row['status'] ?? 'draft';
                                                        $generatedAt = date('d-M-Y H:i', strtotime($row['generated_at'] ?? date('Y-m-d H:i:s')));

                                                        if ($status == 'paid') {
                                                            $statusBadge = '<span class="badge-modern paid">Paid</span>';
                                                        } elseif ($status == 'pending') {
                                                            $statusBadge = '<span class="badge-modern pending">Pending</span>';
                                                        } else {
                                                            $statusBadge = '<span class="badge-modern draft">Draft</span>';
                                                        }

                                                        echo '<tr>';
                                                        echo '<td><strong>' . $monthYear . '</strong> <span class="employee-count-modern">(' . $row['employee_count'] . ' employees)</span></td>';
                                                        echo '<td>' . $dateRange . '</td>';
                                                        echo '<td>' . $statusBadge . '</td>';
                                                        echo '<td>' . $generatedAt . '</td>';
                                                        echo '<td style="text-align:right;">
                                                                <a href="list.php?month=' . $row['pay_month'] . '" class="btn-view-modern">
                                                                    <i class="bx bx-show"></i> View Payslips
                                                                </a>
                                                              </td>';
                                                        echo '</tr>';
                                                    }
                                                } else {
                                                    echo '<tr>
                                                            <td colspan="5" class="text-center">
                                                                <div class="empty-state-modern">
                                                                    <i class="bx bx-receipt"></i>
                                                                    <h6>No Payroll Runs Found</h6>
                                                                    <p>Generate your first payroll above.</p>
                                                                </div>
                                                            </td>
                                                          </tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    <?php if ($payrollResult && $payrollResult->num_rows > 0): ?>
                                        <div class="pagination-container"
                                            style="display:flex; justify-content:space-between; margin-top:15px; align-items:center;">
                                            <div style="font-size:13px; color:#718096;">
                                                Showing 1 to <?= $payrollResult->num_rows ?> entries
                                            </div>
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination pagination-sm mb-0">
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="#">Previous</a>
                                                    </li>
                                                    <li class="page-item active">
                                                        <a class="page-link" href="#">1</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link" href="#">2</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a class="page-link" href="#">Next</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <?php include('../includes/footer.php'); ?>
            </div>
        </div>
    </div>
    <?php include('../includes/script.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payrollForm = document.getElementById('payrollForm');
            const fromDateInput = document.getElementById('from_date');
            const toDateInput = document.getElementById('to_date');
            const periodDisplay = document.getElementById('periodDisplay');

            // Update period display function
            function updatePeriodDisplay() {
                const fromDate = fromDateInput.value;
                const toDate = toDateInput.value;
                
                if (fromDate && toDate) {
                    const from = new Date(fromDate);
                    const to = new Date(toDate);
                    const options = { day: '2-digit', month: 'short', year: 'numeric' };
                    
                    periodDisplay.textContent = 
                        from.toLocaleDateString('en-US', options) + ' to ' + 
                        to.toLocaleDateString('en-US', options);
                }
            }

            // Add event listeners for date changes
            fromDateInput.addEventListener('change', updatePeriodDisplay);
            toDateInput.addEventListener('change', updatePeriodDisplay);

            // Form validation
            if (payrollForm) {
                payrollForm.addEventListener('submit', function(event) {
                    const fromDate = fromDateInput.value;
                    const toDate = toDateInput.value;
                    
                    if (!fromDate || !toDate) {
                        event.preventDefault();
                        iziToast.warning({
                            title: 'Error',
                            message: 'Please select both From and To dates',
                            position: 'topRight'
                        });
                        return false;
                    }
                    
                    // Validate that From Date is not after To Date
                    if (new Date(fromDate) > new Date(toDate)) {
                        event.preventDefault();
                        iziToast.warning({
                            title: 'Error',
                            message: 'From date cannot be after To date',
                            position: 'topRight'
                        });
                        return false;
                    }

                    // Check if date range is not too large (optional)
                    const diffTime = Math.abs(new Date(toDate) - new Date(fromDate));
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays > 365) {
                        event.preventDefault();
                        iziToast.warning({
                            title: 'Warning',
                            message: 'Date range is too large (more than 365 days). Please select a smaller range.',
                            position: 'topRight'
                        });
                        return false;
                    }
                    
                    return true;
                });
            }

            // Initial display update
            updatePeriodDisplay();
        });
    </script>

</body>

</html>