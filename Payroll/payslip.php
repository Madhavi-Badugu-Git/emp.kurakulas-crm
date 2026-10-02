<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

if (!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

$payroll_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($payroll_id > 0) {
    // Updated query to include uan_no and esi_no from tbl_user
    $query = "SELECT p.*, 
              u.firstName, u.lastName, u.mobile, u.email_id, 
              u.username, u.rank, u.department,
              u.department_id, u.designation_id,
              u.uan_no, u.esi_no,
              d.department_name,
              des.designation_name
              FROM tbl_payroll p 
              LEFT JOIN tbl_user u ON p.employee_id = u.id 
              LEFT JOIN tbl_department d ON u.department_id = d.id
              LEFT JOIN tbl_designation des ON u.designation_id = des.id
              WHERE p.id = $payroll_id AND p.status='1'";
    $result = $conn->query($query);
    $payroll = $result->fetch_assoc();

    if (!$payroll) {
        echo '<script>alert("Payroll record not found!"); window.location.href="list.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("Invalid Payroll ID!"); window.location.href="list.php";</script>';
    exit();
}

// Calculate total working days for the month
$month = $payroll['pay_month'];
$year = date('Y', strtotime($month));
$monthNum = date('m', strtotime($month));
$totalWorkingDays = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);

// Get attendance for payable days
$attQuery = "SELECT 
                COUNT(*) as total_days,
                SUM(CASE WHEN attendance_type_id = 1 THEN 1 ELSE 0 END) as present_days,
                SUM(CASE WHEN attendance_type_id = 3 THEN 1 ELSE 0 END) as half_days,
                SUM(CASE WHEN attendance_type_id = 4 THEN 1 ELSE 0 END) as leave_days,
                SUM(CASE WHEN attendance_type_id = 5 THEN 1 ELSE 0 END) as holidays,
                SUM(CASE WHEN attendance_type_id = 6 THEN 1 ELSE 0 END) as week_offs
             FROM tbl_user_attendance 
             WHERE employee_id = " . $payroll['employee_id'] . " 
             AND DATE_FORMAT(date, '%Y-%m') = '$month'
             AND status = '1'";
$attResult = $conn->query($attQuery);
$attData = $attResult->fetch_assoc();

if ($attData && ($attData['total_days'] ?? 0) > 0) {
    $present_days = $attData['present_days'] ?? 0;
    $half_days = $attData['half_days'] ?? 0;
    $leave_days = $attData['leave_days'] ?? 0;
    $holidays = $attData['holidays'] ?? 0;
    $week_offs = $attData['week_offs'] ?? 0;
    $payableDays = $present_days + $leave_days + $holidays + $week_offs + ($half_days * 0.5);
} else {
    $payableDays = $payroll['payable_days'] ?? 0;
}

// Determine display values for department and designation
$displayDepartment = !empty($payroll['department_name']) ? $payroll['department_name'] :
    (!empty($payroll['department']) ? $payroll['department'] : 'N/A');
$displayDesignation = !empty($payroll['designation_name']) ? $payroll['designation_name'] :
    (!empty($payroll['rank']) ? $payroll['rank'] : 'N/A');

// Get UAN and ESI numbers from payroll or user table
$uan_no = !empty($payroll['uan_no']) ? $payroll['uan_no'] : 'N/A';
$esi_no = !empty($payroll['esi_no']) ? $payroll['esi_no'] : 'N/A';

$pageTitle = 'Payslip';
include('../includes/header.php');
?>

<style>
    /* ============================================================
       SIDE MENU SCROLLING STYLES
    ============================================================ */
    .layout-menu {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1000;
        background: #1a2332;
        width: 260px;
        transition: all 0.3s ease;
        scrollbar-width: thin;
        scrollbar-color: #2d3748 transparent;
    }

    .layout-menu::-webkit-scrollbar {
        width: 4px;
    }

    .layout-menu::-webkit-scrollbar-track {
        background: transparent;
    }

    .layout-menu::-webkit-scrollbar-thumb {
        background: #2d3748;
        border-radius: 10px;
    }

    .layout-menu::-webkit-scrollbar-thumb:hover {
        background: #4a5568;
    }

    .menu-inner {
        padding: 16px 0 30px;
        overflow: visible;
    }

    .menu-item {
        position: relative;
        margin: 2px 0;
    }

    .menu-link {
        display: flex;
        align-items: center;
        padding: 10px 20px;
        color: #a0aec0;
        text-decoration: none;
        font-size: 14px;
        border-radius: 0;
        transition: all 0.2s ease;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .menu-link:hover {
        background: #2d3748;
        color: #ffffff;
    }

    .menu-link.active {
        background: #2d3748;
        color: #ffffff;
    }

    .menu-link .menu-icon {
        margin-right: 12px;
        font-size: 20px;
        min-width: 24px;
        text-align: center;
        flex-shrink: 0;
    }

    .menu-link .menu-title {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .menu-link .menu-arrow {
        margin-left: auto;
        font-size: 12px;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .menu-item.open>.menu-link .menu-arrow {
        transform: rotate(180deg);
    }

    .submenu {
        list-style: none;
        padding: 0;
        margin: 0;
        background: #141c28;
        display: none;
    }

    .menu-item.open>.submenu {
        display: block;
    }

    .submenu .menu-link {
        padding-left: 56px;
        font-size: 13px;
        color: #8896a8;
    }

    .submenu .menu-link:hover {
        background: #1e2a36;
        color: #ffffff;
    }

    .submenu .menu-link.active {
        background: #1e2a36;
        color: #ffffff;
    }

    .menu-header {
        padding: 20px 20px 16px;
        border-bottom: 1px solid #2d3748;
        margin-bottom: 8px;
        flex-shrink: 0;
    }

    .menu-header .brand-text {
        color: #ffffff;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .menu-header .brand-text i {
        font-size: 24px;
        color: #696cff;
    }

    .layout-menu::after {
        content: '';
        position: fixed;
        bottom: 0;
        left: 0;
        width: 260px;
        height: 30px;
        background: linear-gradient(to top, #1a2332, transparent);
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1001;
    }

    .layout-menu.scrolled::after {
        opacity: 1;
    }

    @media (max-width: 768px) {
        .layout-menu {
            width: 100%;
            height: 100vh;
            max-width: 280px;
            transform: translateX(-100%);
        }

        .layout-menu.show {
            transform: translateX(0);
        }

        .layout-menu::after {
            width: 280px;
        }

        .layout-page {
            margin-left: 0 !important;
        }
    }

    @media (min-width: 769px) {
        .layout-page {
            margin-left: 260px;
        }
    }

    .menu-toggle-btn {
        display: none;
        position: fixed;
        top: 12px;
        left: 12px;
        z-index: 1002;
        background: #1a2332;
        border: none;
        color: #ffffff;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 22px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .menu-toggle-btn:hover {
        background: #2d3748;
    }

    @media (max-width: 768px) {
        .menu-toggle-btn {
            display: block;
        }

        .menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .menu-overlay.show {
            display: block;
        }
    }

    /* ============================================================
       PAYSLIP STYLES - PROFESSIONAL CLEAN DESIGN
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

    /* ============================================================
       HEADER
    ============================================================ */
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

    /* ============================================================
       EMPLOYEE DETAILS TABLE
    ============================================================ */
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

    /* ============================================================
       DAYS ROW
    ============================================================ */
    .payslip-days-row {
        display: flex;
        justify-content: flex-start;
        gap: 50px;
        background: #f8f9fb;
        padding: 8px 20px;
        border: 1px solid #e8ecf1;
        border-radius: 8px;
        margin-bottom: 14px;
    }

    .payslip-days-row .day-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .payslip-days-row .day-label {
        font-size: 12px;
        color: #7f8c8d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .payslip-days-row .day-value {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        background: #ffffff;
        padding: 2px 14px;
        border-radius: 4px;
        border: 1px solid #e8ecf1;
    }

    /* ============================================================
       MAIN TABLE
    ============================================================ */
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

    .payslip-main-table .other-allowance-row td {
        font-weight: 500;
        color: #34495e;
    }

    .payslip-main-table .other-allowance-row .amount-cell {
        font-weight: 600;
        color: #2c3e50;
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

    /* ============================================================
       NET PAY
    ============================================================ */
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

    .payslip-net-pay .net-label {
        letter-spacing: 0.5px;
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

    /* ============================================================
       EMPLOYER CONTRIBUTIONS
    ============================================================ */
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

    .payslip-employer-contrib strong {
        color: #2c3e50;
        font-weight: 700;
    }

    .payslip-employer-contrib .epf-amount {
        color: #2c3e50;
        font-weight: 700;
        font-size: 14px;
    }

    .payslip-employer-contrib .esi-amount {
        color: #e74c3c;
        font-weight: 700;
        font-size: 14px;
    }

    .payslip-employer-contrib .contrib-divider {
        color: #bdc3c7;
    }

    /* ============================================================
       FOOTER
    ============================================================ */
    .payslip-footer {
        text-align: center;
        font-size: 11px;
        color: #bdc3c7;
        padding-top: 10px;
        border-top: 1px solid #e8ecf1;
        margin-top: 8px;
        letter-spacing: 0.3px;
    }

    /* ============================================================
       PRINT STYLES
    ============================================================ */
    @media print {
        .no-print {
            display: none !important;
        }

        @page {
            margin: 0.5cm;
        }

        body {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .layout-wrapper,
        .layout-container,
        .layout-page,
        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }

        .container-xxl {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .payslip-wrapper {
            border: 1px solid #e8ecf1 !important;
            padding: 25px 30px 20px !important;
            border-radius: 8px !important;
            box-shadow: none !important;
            max-width: 100% !important;
            margin: 0 !important;
            background: #ffffff !important;
        }

        .payslip-header {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            border-bottom: 3px solid #2c3e50 !important;
            padding-bottom: 14px !important;
            margin-bottom: 20px !important;
            flex-direction: row !important;
        }

        .payslip-header .logo-section {
            display: flex !important;
            align-items: center !important;
            gap: 15px !important;
            flex: 0 0 auto !important;
        }

        .payslip-header .logo-section img {
            max-height: 60px !important;
            width: auto !important;
            object-fit: contain !important;
            filter: grayscale(0%) !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .payslip-header .center-section {
            text-align: center !important;
            flex: 1 !important;
        }

        .payslip-header .company-name {
            color: #077BCC !important;
            font-size: 24px !important;
        }

        .payslip-header .company-location {
            color: #CC0607 !important;
        }

        .payslip-header .payslip-title {
            color: #2c3e50 !important;
        }

        .payslip-header .right-section {
            flex: 0 0 auto !important;
            width: 80px !important;
        }

        .payslip-employee-table {
            border: 1px solid #e8ecf1 !important;
        }

        .payslip-employee-table td {
            border: 1px solid #e8ecf1 !important;
        }

        .payslip-employee-table .label {
            background: #f8f9fb !important;
            color: #7f8c8d !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .payslip-employee-table .value {
            background: #ffffff !important;
            color: #2c3e50 !important;
        }

        .payslip-days-row {
            background: #f8f9fb !important;
            border: 1px solid #e8ecf1 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .payslip-days-row .day-label {
            color: #7f8c8d !important;
        }

        .payslip-days-row .day-value {
            background: #ffffff !important;
            color: #2c3e50 !important;
            border: 1px solid #e8ecf1 !important;
        }

        .payslip-main-table {
            border: 1px solid #e8ecf1 !important;
        }

        .payslip-main-table td {
            border: 1px solid #e8ecf1 !important;
            color: #2c3e50 !important;
        }

        .payslip-main-table .section-header {
            background: #2c3e50 !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .payslip-main-table .label-cell {
            color: #34495e !important;
        }

        .payslip-main-table .amount-cell {
            color: #2c3e50 !important;
        }

        .payslip-main-table .deduction-row .amount-cell {
            color: #e74c3c !important;
        }

        .payslip-main-table .deduction-row .label-cell {
            color: #e74c3c !important;
        }

        .payslip-main-table .other-allowance-row .amount-cell {
            color: #2c3e50 !important;
        }

        .payslip-main-table .total-row td {
            background: #f8f9fb !important;
            border-top: 3px solid #2c3e50 !important;
            color: #2c3e50 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .payslip-main-table .total-row .amount-cell {
            color: #2c3e50 !important;
        }

        .payslip-main-table .total-row.deduction-row td {
            background: #fdf2f2 !important;
            border-top: 3px solid #e74c3c !important;
        }

        .payslip-main-table .total-row.deduction-row .amount-cell {
            color: #e74c3c !important;
        }

        .payslip-main-table .empty-cell {
            background: #fafbfc !important;
        }

        .payslip-net-pay {
            color: #2c3e50 !important;
            border: 2px solid #2c3e50 !important;
            background: #f8f9fb !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .payslip-net-pay .amount {
            color: #2c3e50 !important;
            background: #ffffff !important;
            border: 1px solid #e8ecf1 !important;
        }

        .payslip-employer-contrib {
            background: #f8f9fb !important;
            border: 1px solid #e8ecf1 !important;
            color: #7f8c8d !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .payslip-employer-contrib strong {
            color: #2c3e50 !important;
        }

        .payslip-employer-contrib .epf-amount {
            color: #2c3e50 !important;
        }

        .payslip-employer-contrib .esi-amount {
            color: #e74c3c !important;
        }

        .payslip-footer {
            color: #bdc3c7 !important;
            border-top: 1px solid #e8ecf1 !important;
        }
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .payslip-wrapper {
            padding: 15px 15px;
        }

        .payslip-header {
            flex-direction: column;
            text-align: center;
        }

        .payslip-header .logo-section {
            flex-direction: column;
            text-align: center;
            justify-content: center;
        }

        .payslip-header .center-section {
            margin-top: 5px;
        }

        .payslip-header .logo-section img {
            max-height: 50px;
        }

        .payslip-header .company-name {
            font-size: 20px;
        }

        .payslip-header .payslip-title {
            font-size: 14px;
        }

        .payslip-header .right-section {
            display: none;
        }

        .payslip-employee-table td {
            padding: 5px 10px;
            font-size: 12px;
        }

        .payslip-employee-table .label {
            font-size: 10px;
            width: 80px;
        }

        .payslip-employee-table .value {
            font-size: 12px;
        }

        .payslip-days-row {
            gap: 20px;
            flex-wrap: wrap;
            padding: 6px 14px;
        }

        .payslip-main-table td {
            padding: 5px 10px;
            font-size: 11px;
        }

        .payslip-main-table .label-cell {
            padding-left: 10px;
        }

        .payslip-main-table .amount-cell {
            padding-right: 10px;
        }

        .payslip-main-table .section-header {
            font-size: 11px;
            padding: 6px 8px;
        }

        .payslip-main-table .total-row td {
            font-size: 12px;
        }

        .payslip-net-pay {
            font-size: 15px;
            flex-direction: column;
            gap: 8px;
            text-align: center;
            padding: 12px 16px;
        }

        .payslip-net-pay .amount {
            font-size: 18px;
        }

        .payslip-employer-contrib {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .payslip-wrapper {
            padding: 8px 10px;
        }

        .payslip-employee-table td {
            padding: 4px 6px;
            font-size: 10px;
        }

        .payslip-employee-table .label {
            font-size: 9px;
            width: 60px;
        }

        .payslip-employee-table .value {
            font-size: 10px;
        }

        .payslip-main-table td {
            padding: 4px 6px;
            font-size: 9px;
        }

        .payslip-main-table .label-cell {
            padding-left: 6px;
        }

        .payslip-main-table .amount-cell {
            padding-right: 6px;
        }

        .payslip-days-row {
            gap: 8px;
            padding: 4px 8px;
            flex-wrap: wrap;
        }

        .payslip-days-row .day-value {
            font-size: 11px;
            padding: 1px 8px;
        }

        .payslip-net-pay {
            font-size: 13px;
            padding: 10px 12px;
        }

        .payslip-net-pay .amount {
            font-size: 15px;
            padding: 3px 12px;
        }

        .payslip-header .company-name {
            font-size: 16px;
        }

        .payslip-header .payslip-title {
            font-size: 12px;
        }

        .payslip-header .logo-section img {
            max-height: 40px;
        }

        .payslip-employer-contrib {
            font-size: 11px;
            padding: 8px 12px;
        }
    }


    /* ============================================================
   BREADCRUMB BOX STYLES (EXACT MATCH TO IMAGE)
============================================================ */
    .breadcrumb-box {
        background: #f8f9fa;
        /* Light gray background */
        border: 1px solid #e9ecef;
        /* Subtle border */
        border-radius: 10px;
        /* Rounded corners */
        padding: 12px 20px;
        /* Padding inside box */
        margin-bottom: 20px;
        /* Space below box */
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .breadcrumb-box .breadcrumb-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 15px;
        font-weight: 500;
        color: #198754;
        /* Green text color */
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .breadcrumb-box .breadcrumb-item i {
        font-size: 18px;
        color: #198754;
    }

    .breadcrumb-box .breadcrumb-item:hover {
        color: #0f5132;
        /* Darker green on hover */
        text-decoration: underline;
    }

    .breadcrumb-box .breadcrumb-item.active {
        color: #1a2332;
        /* Dark text for active */
        font-weight: 600;
        pointer-events: none;
        /* Not clickable */
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

    /* Responsive for Mobile */
    @media (max-width: 768px) {
        .breadcrumb-box {
            flex-wrap: wrap;
            padding: 10px 15px;
            gap: 5px;
        }

        .breadcrumb-box .breadcrumb-item {
            font-size: 13px;
        }

        .breadcrumb-box .breadcrumb-item i {
            font-size: 15px;
        }
    }
</style>

<body>
    <!-- Mobile Menu Toggle Button -->
    <button class="menu-toggle-btn" id="menuToggleBtn" aria-label="Toggle menu">
        <i class="bx bx-menu"></i>
    </button>

    <!-- Mobile Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?php include('../includes/sideMenu.php'); ?>
            <div class="layout-page">
                <?php include('../includes/navbar.php'); ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                            <!-- Breadcrumb Box (Matches Image Exactly) -->
                            <div class="breadcrumb-box">
                                <!-- Dashboard -->
                                <a href="../dashboard/superAdmin" class="breadcrumb-item">
                                    <i class="bx bx-home"></i> Dashboard
                                </a>

                                <span class="separator">›</span>

                                <!-- payroll Master -->
                                <a href="../payroll/add.php" class="breadcrumb-item">
                                    <i class="bx bx-briefcase"></i> Payroll
                                </a>

                                <span class="separator">›</span>

                                <a href="../payroll/list.php" class="breadcrumb-item">
                                    <i class="bx bx-briefcase"></i> Payroll List
                                </a>

                                <span class="separator">›</span>


                                <span class="breadcrumb-item active">
                                    <i class="bx bx-payslip-ul"></i> Payslip
                                </span>
                            </div>
                            <div>
                                <button onclick="window.print()" class="btn btn-primary btn-sm"
                                    style="background:#2c3e50;border-color:#2c3e50;border-radius:6px;padding:8px 18px;">
                                    <i class="bx bx-printer me-1"></i> Print
                                </button>
                                <a href="list.php" class="btn btn-secondary btn-sm"
                                    style="border-radius:6px;padding:8px 18px;">
                                    <i class="bx bx-arrow-back me-1"></i> Back
                                </a>
                            </div>
                        </div>

                        <!-- ============================================================
                             PAYSLIP
                        ============================================================ -->
                        <div class="payslip-wrapper" id="payslip">

                            <!-- Header -->
                            <div class="payslip-header">
                                <div class="logo-section">
                                    <img src="../assets/img/logos/kurakulas.png" alt="KURUKULAS Logo">
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
                                    <td class="value"><?= htmlspecialchars($payroll['username'] ?? 'N/A') ?></td>
                                    <td class="label">Designation</td>
                                    <td class="value"><?= htmlspecialchars($displayDesignation) ?></td>
                                </tr>
                                <tr>
                                    <td class="label">Name</td>
                                    <td class="value">
                                        <?= htmlspecialchars(($payroll['firstName'] ?? '') . ' ' . ($payroll['lastName'] ?? '')) ?>
                                    </td>
                                    <td class="label">Department</td>
                                    <td class="value"><?= htmlspecialchars($displayDepartment) ?></td>
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
                                    <td class="amount-cell">₹ <?= number_format($payroll['basic_salary'], 2) ?></td>
                                    <td class="label-cell deduction-row">EPF (Employee)</td>
                                    <td class="amount-cell deduction-row">₹
                                        <?= number_format($payroll['pf_deduction'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td class="label-cell">DA</td>
                                    <td class="amount-cell">₹
                                        <?= number_format(($payroll['basic_salary'] ?? 0) * 0.08, 2) ?></td>
                                    <td class="label-cell deduction-row">ESI (Employee)</td>
                                    <td class="amount-cell deduction-row">₹
                                        <?= number_format($payroll['esi_deduction'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td class="label-cell">HRA</td>
                                    <td class="amount-cell">₹
                                        <?= number_format(($payroll['basic_salary'] ?? 0) * 0.0065, 2) ?></td>
                                    <td class="label-cell deduction-row">Other Deductions</td>
                                    <td class="amount-cell deduction-row">₹
                                        <?= number_format(($payroll['other_deduction'] ?? 0) + ($payroll['pt_deduction'] ?? 0), 2) ?>
                                    </td>
                                </tr>
                                <tr class="other-allowance-row">
                                    <td class="label-cell">Other Allowance</td>
                                    <td class="amount-cell">₹
                                        <?= number_format(($payroll['gross_salary'] ?? 0) - ($payroll['basic_salary'] ?? 0) - (($payroll['basic_salary'] ?? 0) * 0.0865), 2) ?>
                                    </td>
                                    <td class="empty-cell"></td>
                                    <td class="empty-cell"></td>
                                </tr>
                                <tr class="total-row">
                                    <td class="label-cell"><strong>Gross Earnings</strong></td>
                                    <td class="amount-cell"><strong>₹
                                            <?= number_format($payroll['gross_salary'], 2) ?></strong></td>
                                    <td class="label-cell deduction-row total-row"><strong>Total Deductions</strong>
                                    </td>
                                    <td class="amount-cell deduction-row total-row"><strong>₹
                                            <?= number_format(($payroll['pf_deduction'] ?? 0) + ($payroll['esi_deduction'] ?? 0) + ($payroll['other_deduction'] ?? 0) + ($payroll['pt_deduction'] ?? 0), 2) ?></strong>
                                    </td>
                                </tr>
                            </table>

                            <!-- Net Pay -->
                            <div class="payslip-net-pay">
                                <span class="net-label"><strong>Net Pay:</strong></span>
                                <span class="amount">₹ <?= number_format($payroll['net_salary'], 2) ?></span>
                            </div>

                            <!-- Employer Contributions -->
                            <div class="payslip-employer-contrib">
                                <span><strong>Employer EPF Contribution:</strong> <span class="epf-amount">₹
                                        <?= number_format($payroll['pf_deduction'] ?? 0, 2) ?></span></span>
                                <span class="contrib-divider">|</span>
                                <span><strong>Employer ESI Contribution:</strong> <span class="esi-amount">₹
                                        <?= number_format($payroll['esi_deduction'] ?? 0, 2) ?></span></span>
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
        document.addEventListener('DOMContentLoaded', function () {
            const sideMenu = document.querySelector('.layout-menu');
            const menuToggleBtn = document.getElementById('menuToggleBtn');
            const menuOverlay = document.getElementById('menuOverlay');

            if (sideMenu) {
                sideMenu.addEventListener('scroll', function () {
                    if (this.scrollTop > 20) {
                        this.classList.add('scrolled');
                    } else {
                        this.classList.remove('scrolled');
                    }
                });
            }

            if (menuToggleBtn) {
                menuToggleBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const isOpen = sideMenu.classList.contains('show');

                    if (isOpen) {
                        sideMenu.classList.remove('show');
                        menuOverlay.classList.remove('show');
                        this.innerHTML = '<i class="bx bx-menu"></i>';
                    } else {
                        sideMenu.classList.add('show');
                        menuOverlay.classList.add('show');
                        this.innerHTML = '<i class="bx bx-x"></i>';
                    }
                });
            }

            if (menuOverlay) {
                menuOverlay.addEventListener('click', function () {
                    sideMenu.classList.remove('show');
                    this.classList.remove('show');
                    if (menuToggleBtn) {
                        menuToggleBtn.innerHTML = '<i class="bx bx-menu"></i>';
                    }
                });
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sideMenu && sideMenu.classList.contains('show')) {
                    sideMenu.classList.remove('show');
                    if (menuOverlay) menuOverlay.classList.remove('show');
                    if (menuToggleBtn) menuToggleBtn.innerHTML = '<i class="bx bx-menu"></i>';
                }
            });

            if (window.innerWidth <= 768) {
                const menuLinks = document.querySelectorAll('.menu-link');
                menuLinks.forEach(function (link) {
                    link.addEventListener('click', function (e) {
                        const parent = this.closest('.menu-item');
                        if (parent && parent.querySelector('.submenu')) {
                            e.preventDefault();
                            parent.classList.toggle('open');
                        }
                    });
                });
            }
        });

        if (window.location.search.includes('print=1')) {
            window.onload = function () {
                setTimeout(function () { window.print(); }, 500);
            };
        }
    </script>
</body>

</html>