<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// ============================================================
// CHECK LOGIN & GET USER DETAILS
// ============================================================

// Ensure user is logged in
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}


include('../includes/header.php');
?>

<style>
/* ============================================================
   RESET & BASE
============================================================ */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    overflow: hidden;  /* ← PREVENTS BODY SCROLLING */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f0f2f5;
}

/* ============================================================
   LAYOUT WRAPPER
============================================================ */
.layout-wrapper {
    display: flex;
    flex-direction: column;
    height: 100vh;  /* ← FULL VIEWPORT HEIGHT */
    overflow: hidden;  /* ← PREVENTS SCROLLING */
}

.layout-container {
    display: flex;
    flex: 1;
    height: 100vh;  /* ← FULL VIEWPORT HEIGHT */
    overflow: hidden;  /* ← PREVENTS SCROLLING */
    position: relative;
}

/* ============================================================
   SIDEBAR - FIXED, NO SCROLL (OR SCROLL IF CONTENT OVERFLOWS)
============================================================ */
.layout-sidebar,
.layout-menu {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 260px;
    background: #1a2332;
    color: #ffffff;
    overflow-y: auto;  /* ← SIDEBAR SCROLLS IF CONTENT OVERFLOWS */
    overflow-x: hidden;
    flex-shrink: 0;
    z-index: 1000;
    transition: transform 0.3s ease-in-out;
    transform: translateX(0);
    border: none !important;
    border-right: none !important;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    outline: none !important;
}

/* Sidebar scrollbar styling */
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

/* ============================================================
   MAIN PAGE CONTENT - THIS SHOULD SCROLL
============================================================ */
.layout-page {
    flex: 1;
    height: 100vh;  /* ← FULL VIEWPORT HEIGHT */
    margin-left: 260px;
    background: #f0f2f5;
    display: flex;
    flex-direction: column;
    overflow-y: auto;  /* ← ONLY PAGE SCROLLS */
    overflow-x: hidden;
}

/* ============================================================
   CONTENT WRAPPER
============================================================ */
.content-wrapper {
    flex: 1;
    padding: 20px 30px;
}

/* ============================================================
   MOBILE TOGGLE BUTTON
============================================================ */
.sidebar-toggle {
    display: none;
    position: fixed;
    top: 12px;
    left: 12px;
    z-index: 1001;
    background: #1a2332;
    color: #ffffff;
    border: none;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 22px;
    cursor: pointer;
}

/* ============================================================
   SIDEBAR OVERLAY
============================================================ */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

.sidebar-overlay.active {
    display: block;
}

/* ============================================================
   RESPONSIVE - TABLET & MOBILE
============================================================ */
@media (max-width: 992px) {
    .layout-sidebar,
    .layout-menu {
        transform: translateX(-100%);
    }

    .layout-sidebar.open,
    .layout-menu.open {
        transform: translateX(0);
    }

    .layout-page {
        margin-left: 0;
    }

    .sidebar-toggle {
        display: block;
    }
}

/* ============================================================
   RESPONSIVE - SMALL SCREENS
============================================================ */
@media (max-width: 768px) {
    .content-wrapper {
        padding: 15px;
    }
}

@media (max-width: 480px) {
    .content-wrapper {
        padding: 10px;
    }
}
    .attendance-card {
        margin-top: 25px;
    }

    .attendance-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 18px 24px;
    }

    .attendance-card .card-header h5 {
        font-size: 16px;
        font-weight: 600;
        color: #1a2332;
        margin: 0;
    }

    .attendance-card .card-body {
        padding: 20px 24px;
    }

    /* ============================================================
   FILTER SECTION - WITH LABELS ABOVE DROPDOWNS
============================================================ */
.filter-section {
    background: #f8fafc;
    padding: 16px 18px;
    border-radius: 10px;
    margin-bottom: 18px;
    border: 1px solid #e9ecef;
    display: flex;
    flex-wrap: wrap;
    gap: 25px;
    align-items: flex-end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.filter-group label {
    font-weight: 600;
    color: #4a5568;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-group .form-select {
    border-color: #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    padding: 8px 14px;
    background-color: #ffffff;
    min-width: 150px;
    height: 42px;
    cursor: pointer;
}

.filter-group .form-select:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.1);
    outline: none;
}

.filter-group .form-select-sm {
    min-width: 120px;
}

.btn-reset {
    background: #e2e8f0;
    color: #4a5568;
    border: none;
    padding: 8px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 42px;
}

.btn-reset:hover {
    background: #cbd5e0;
    color: #1a2332;
}

.btn-reset i {
    font-size: 16px;
}

/* Responsive */
@media (max-width: 768px) {
    .filter-section {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }

    .filter-group .form-select {
        width: 100%;
    }

    .filter-group .form-select-sm {
        width: 100%;
    }

    .btn-reset {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .filter-section {
        padding: 12px 14px;
    }
    
    .filter-group label {
        font-size: 10px;
    }
    
    .filter-group .form-select {
        font-size: 12px;
        padding: 6px 10px;
        height: 36px;
        min-width: 100px;
    }
    
    .btn-reset {
        font-size: 12px;
        padding: 6px 16px;
        height: 36px;
    }
}
    .attendance-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .attendance-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .attendance-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .attendance-wrapper::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 10px;
    }

    .attendance-wrapper::-webkit-scrollbar-thumb:hover {
        background: #a0a7ae;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .attendance-table thead th {
        height: 45px;
        background: #f8f9fc;
        color: #1a2332;
        font-weight: 600;
        text-align: left;
        vertical-align: middle;
        white-space: nowrap;
        border-bottom: 2px solid #e9ecef;
        padding: 10px 16px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .attendance-table thead th:last-child {
        text-align: left;
    }

    .attendance-table tbody td {
        vertical-align: middle;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f3f5;
        color: #1a2332;
        font-size: 14px;
    }

    .attendance-table tbody tr:hover td {
        background: #f8fafc;
    }

    .attendance-table tbody tr:last-child td {
        border-bottom: none;
    }

    .attendance-badge {
        padding: 5px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
        display: inline-block;
        text-align: center;
        min-width: 80px;
    }

    .attendance-badge.present {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .attendance-badge.absent {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .attendance-badge.half {
        background: #fef9c3;
        color: #713f12;
        border: 1px solid #fde68a;
    }

    .attendance-badge.leave {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .attendance-badge.holiday {
        background: #ede9fe;
        color: #5b21b6;
        border: 1px solid #ddd6fe;
    }

    .attendance-badge.weekoff {
        background: #f1f3f5;
        color: #4b5563;
        border: 1px solid #e5e7eb;
    }

    .attendance-badge.not-marked {
        background: #f8f9fa;
        color: #6b7a8f;
        border: 1px dashed #d1d5db;
    }

    .employee-info-card {
        background: linear-gradient(135deg, #696cff 0%, #5a5de0 100%);
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 20px;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .employee-info-card .name {
        font-size: 20px;
        font-weight: 700;
    }

    .employee-info-card .rank {
        font-size: 14px;
        opacity: 0.9;
        margin-top: 4px;
    }

    .employee-info-card .badge-info {
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
    }

    .stats-summary {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        padding: 15px 20px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        margin-bottom: 20px;
    }

    .stats-summary .stat-item {
        font-size: 14px;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .stats-summary .stat-item .count {
        font-weight: 700;
        font-size: 18px;
    }

    .stats-summary .stat-item .count.green {
        color: #28a745;
    }

    .stats-summary .stat-item .count.red {
        color: #dc3545;
    }

    .stats-summary .stat-item .count.orange {
        color: #ed8936;
    }

    .stats-summary .stat-item .count.blue {
        color: #696cff;
    }

    .stats-summary .stat-item .count.purple {
        color: #8b5cf6;
    }

    .stats-summary .stat-item .count.gray {
        color: #6b7a8f;
    }

    .breadcrumb-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .breadcrumb-box .breadcrumb-item {
        display: inline-flex;
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

    .remark-text {
        color: #6b7a8f;
        font-size: 13px;
    }

    .remark-text .no-remark {
        color: #9ca3af;
        font-style: italic;
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title .subtitle {
        font-size: 16px;
        font-weight: 400;
        color: #6b7a8f;
    }

    .payslip-link-card {
        background: #f8fafc;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        margin-top: 20px;
    }

    .payslip-link-card .btn-payslip {
        background: #696cff;
        color: #ffffff;
        border: none;
        padding: 10px 30px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .payslip-link-card .btn-payslip:hover {
        background: #5a5de0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.3);
        color: #ffffff;
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

        .attendance-table thead th,
        .attendance-table tbody td {
            padding: 8px 10px;
            font-size: 12px;
        }

        .attendance-badge {
            font-size: 10px;
            padding: 3px 10px;
            min-width: 60px;
        }

        .stats-summary {
            gap: 10px;
            padding: 12px 14px;
        }

        .stats-summary .stat-item {
            font-size: 12px;
        }

        .stats-summary .stat-item .count {
            font-size: 15px;
        }

        .employee-info-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-section .form-select {
            width: 100%;
        }

        .page-title {
            font-size: 20px;
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 480px) {

        .attendance-table thead th,
        .attendance-table tbody td {
            padding: 6px 8px;
            font-size: 11px;
        }

        .attendance-badge {
            font-size: 9px;
            padding: 2px 8px;
            min-width: 50px;
        }

        .stats-summary {
            gap: 8px;
            padding: 10px 12px;
        }

        .stats-summary .stat-item {
            font-size: 11px;
        }

        .stats-summary .stat-item .count {
            font-size: 13px;
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

            <!-- Page Content - Scrollable -->
            <div class="layout-page">

                <!-- Navbar -->
                <?php include('../includes/navbar.php'); ?>

                <?php
                // Get logged in user details from session
                $loggedInUserId = $_SESSION['user_id'] ?? 0;
                $loggedInUsername = $_SESSION['loggedInUser'] ?? '';
                $loggedInUserRank = $_SESSION['user_rank'] ?? 'employee';

                // If user_id is not set in session, try to get it from database
                if ($loggedInUserId == 0 && !empty($loggedInUsername)) {
                    $userCheck = "SELECT id FROM tbl_user WHERE username = '" . mysqli_real_escape_string($conn, $loggedInUsername) . "' AND status = '1'";
                    $userResult = mysqli_query($conn, $userCheck);
                    if ($userRow = mysqli_fetch_assoc($userResult)) {
                        $loggedInUserId = $userRow['id'];
                        $_SESSION['user_id'] = $loggedInUserId;
                    }
                }

                // If still no user ID, show error
                if ($loggedInUserId == 0) {
                    echo '<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h4><i class="bx bx-error-circle me-2"></i> Error</h4>
            <p>Unable to identify user. Please <a href="../login.php">login again</a>.</p>
        </div>
    </div>
</body>
</html>';
                    exit();
                }


                // Get employee details from database
                $empQuery = "SELECT id, username, firstName, lastName, rank, basic_salary, gross_salary 
            FROM tbl_user 
            WHERE id = '" . (int) $loggedInUserId . "' AND status = '1'";
                $empResult = mysqli_query($conn, $empQuery);

                if (!$empResult) {
                    echo '<!DOCTYPE html>
<html>
<head>
    <title>Database Error</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h4><i class="bx bx-error-circle me-2"></i> Database Error</h4>
            <p>' . mysqli_error($conn) . '</p>
        </div>
    </div>
</body>
</html>';
                    exit();
                }

                $employee = mysqli_fetch_assoc($empResult);

                if (!$employee) {
                    echo '<!DOCTYPE html>
<html>
<head>
    <title>Employee Not Found</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-danger">
            <h4><i class="bx bx-error-circle me-2"></i> Employee Not Found!</h4>
            <p>Your account may be inactive or not properly configured.</p>
            <p><small>User ID: ' . $loggedInUserId . '</small></p>
            <a href="../logout.php" class="btn btn-danger mt-2">Logout</a>
        </div>
    </div>
</body>
</html>';
                    exit();
                }

                // CSRF Token
                if (empty($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }
                $csrfToken = $_SESSION['csrf_token'];

                // Month/Year for attendance
                $register_month = isset($_GET['register_month']) ? (int) $_GET['register_month'] : (int) date('n');
                $register_year = isset($_GET['register_year']) ? (int) $_GET['register_year'] : (int) date('Y');

                if ($register_month < 1 || $register_month > 12)
                    $register_month = (int) date('n');
                if ($register_year < 2000 || $register_year > 2100)
                    $register_year = (int) date('Y');

                $register_month_name = date('F', mktime(0, 0, 0, $register_month, 1, $register_year));
                $register_total_days = cal_days_in_month(CAL_GREGORIAN, $register_month, $register_year);

                // Get attendance types
                $attendanceTypes = [];
                $attendanceTypeTableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'tbl_attendance_type'");
                if ($attendanceTypeTableCheck && mysqli_num_rows($attendanceTypeTableCheck) > 0) {
                    $attendanceTypeQuery = "SELECT id, attendance_type, symbol FROM tbl_attendance_type ORDER BY attendance_type ASC";
                    $attendanceTypeResult = mysqli_query($conn, $attendanceTypeQuery);
                    if ($attendanceTypeResult && mysqli_num_rows($attendanceTypeResult) > 0) {
                        while ($attendanceTypeRow = mysqli_fetch_assoc($attendanceTypeResult)) {
                            $attendanceTypes[] = $attendanceTypeRow;
                        }
                    }
                }

                // If no attendance types found, create default ones
                if (empty($attendanceTypes)) {
                    $defaultTypes = [
                        ['id' => 1, 'attendance_type' => 'Present', 'symbol' => 'P'],
                        ['id' => 2, 'attendance_type' => 'Absent', 'symbol' => 'A'],
                        ['id' => 3, 'attendance_type' => 'Half Day', 'symbol' => 'H'],
                        ['id' => 4, 'attendance_type' => 'Leave', 'symbol' => 'L'],
                        ['id' => 5, 'attendance_type' => 'Holiday', 'symbol' => 'HD'],
                        ['id' => 6, 'attendance_type' => 'Week Off', 'symbol' => 'WO']
                    ];
                    $attendanceTypes = $defaultTypes;
                }

                // Get existing attendance for logged in employee
                $attendanceData = [];
                $startDate = sprintf('%04d-%02d-01', $register_year, $register_month);
                $endDate = date('Y-m-t', strtotime($startDate));

                $attendanceQuery = "SELECT a.id, a.employee_id, a.date, a.attendance_type_id, at.attendance_type, at.symbol 
                    FROM tbl_user_attendance a
                    LEFT JOIN tbl_attendance_type at ON a.attendance_type_id = at.id
                    WHERE a.employee_id = '$loggedInUserId' 
                    AND a.date BETWEEN '$startDate' AND '$endDate' 
                    AND a.status = '1'
                    ORDER BY a.date ASC";
                $attendanceResult = mysqli_query($conn, $attendanceQuery);

                // Store attendance data with type details
                while ($row = mysqli_fetch_assoc($attendanceResult)) {
                    $attendanceData[$row['date']] = [
                        'id' => $row['id'],
                        'type_id' => $row['attendance_type_id'],
                        'type_name' => $row['attendance_type'],
                        'symbol' => $row['symbol']
                    ];
                }

                // Calculate statistics
                $presentCount = 0;
                $absentCount = 0;
                $halfCount = 0;
                $leaveCount = 0;
                $holidayCount = 0;
                $weekoffCount = 0;
                $notMarkedCount = 0;

                for ($day = 1; $day <= $register_total_days; $day++) {
                    $attendanceDate = sprintf('%04d-%02d-%02d', $register_year, $register_month, $day);
                    if (isset($attendanceData[$attendanceDate])) {
                        $type = (int) $attendanceData[$attendanceDate]['type_id'];
                        if ($type == 1)
                            $presentCount++;
                        elseif ($type == 2)
                            $absentCount++;
                        elseif ($type == 3)
                            $halfCount++;
                        elseif ($type == 4)
                            $leaveCount++;
                        elseif ($type == 5)
                            $holidayCount++;
                        elseif ($type == 6)
                            $weekoffCount++;
                    } else {
                        $notMarkedCount++;
                    }
                }

                $totalMarked = $presentCount + $absentCount + $halfCount + $leaveCount + $holidayCount + $weekoffCount;
                $attendancePercent = $register_total_days > 0 ? round(($totalMarked / $register_total_days) * 100, 1) : 0;
                $presentPercent = $register_total_days > 0 ? round(($presentCount / $register_total_days) * 100, 1) : 0;

                // Determine status
                if ($attendancePercent >= 90) {
                    $statusText = 'Excellent';
                    $statusColor = 'success';
                } elseif ($attendancePercent >= 75) {
                    $statusText = 'Good';
                    $statusColor = 'info';
                } elseif ($attendancePercent >= 60) {
                    $statusText = 'Average';
                    $statusColor = 'warning';
                } elseif ($attendancePercent >= 40) {
                    $statusText = 'Poor';
                    $statusColor = 'danger';
                } else {
                    $statusText = 'Critical';
                    $statusColor = 'danger';
                }

                $pageTitle = 'My Attendance';
                ?>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Breadcrumb -->
                        <div class="breadcrumb-box">
                            <a href="../dashboard/employee-dashboard.php" class="breadcrumb-item">
                                <i class="bx bx-home"></i> Dashboard
                            </a>
                            <span class="separator">›</span>
                            <a href="#" class="breadcrumb-item">
                                <i class="bx bx-briefcase"></i> Payroll
                            </a>
                            <span class="separator">›</span>
                            <span class="breadcrumb-item active">
                                <i class="bx bx-calendar-check"></i> My Attendance
                            </span>
                        </div>

                        <!-- Page Title -->
                        <div class="page-title">
                            <span>📋 My Attendance</span>
                            <span class="subtitle">— <?= htmlspecialchars($register_month_name) ?>
                                <?= $register_year ?></span>
                        </div>

                        <!-- Employee Info Card -->
                        <div class="employee-info-card">
                            <div>
                                <div class="name">👤
                                    <?= htmlspecialchars($employee['firstName'] . ' ' . $employee['lastName']) ?></div>
                                <div class="rank">
                                    <i class="bx bx-briefcase me-1"></i>
                                    <?= htmlspecialchars($employee['rank'] ?? 'Employee') ?>
                                    &nbsp;|&nbsp;
                                    <i class="bx bx-user me-1"></i>
                                    ID: <?= htmlspecialchars($employee['username'] ?? 'N/A') ?>
                                    &nbsp;|&nbsp;
                                    
                                </div>
                            </div>
                            <div>
                                <span class="badge-info">
                                    <i class="bx bx-calendar me-1"></i>
                                    <?= $register_month_name ?> <?= $register_year ?>
                                </span>
                                <span class="badge-info ms-2" style="background: rgba(255,255,255,0.3);">
                                    <i class="bx bx-star me-1"></i>
                                    <?= $statusText ?>
                                </span>
                            </div>
                        </div>

                        <!-- Stats Summary -->
                        <div class="stats-summary">
                            <span class="stat-item">
                                ✅ Present: <span class="count green"><?= $presentCount ?></span>
                                <small>(<?= $presentPercent ?>%)</small>
                            </span>
                            <span class="stat-item">
                                ❌ Absent: <span class="count red"><?= $absentCount ?></span>
                            </span>
                            <span class="stat-item">
                                ⏳ Half Day: <span class="count orange"><?= $halfCount ?></span>
                            </span>
                            <span class="stat-item">
                                📋 Leave: <span class="count blue"><?= $leaveCount ?></span>
                            </span>
                            <span class="stat-item">
                                🎉 Holiday: <span class="count purple"><?= $holidayCount ?></span>
                            </span>
                            <span class="stat-item">
                                📅 Week Off: <span class="count gray"><?= $weekoffCount ?></span>
                            </span>
                            <span class="stat-item">
                                ⏳ Not Marked: <span class="count gray"><?= $notMarkedCount ?></span>
                            </span>
                            <span class="stat-item">
                                📊 Total: <strong><?= $attendancePercent ?>%</strong>
                            </span>
                        </div>

                        <!-- Attendance Register -->
                        <div class="row attendance-card">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="bx bx-calendar-check me-2"></i>Attendance Details</h5>
                                    </div>
                                    <div class="card-body">

                                        <!-- Month/Year Filter - WITH LABELS ABOVE -->
                                        <div class="filter-section">
                                            <form method="GET" action="" id="filterForm"
                                                style="display: flex; flex-wrap: wrap; gap: 25px; align-items: flex-end; width: 100%;">

                                                <!-- Month Dropdown with Label -->
                                                <div class="filter-group">
                                                    <label for="register_month">MONTH</label>
                                                    <select name="register_month" id="register_month"
                                                        class="form-select"
                                                        onchange="document.getElementById('filterForm').submit()">
                                                        <?php for ($month = 1; $month <= 12; $month++): ?>
                                                            <option value="<?= $month ?>" <?= ($month == $register_month) ? 'selected' : '' ?>>
                                                                <?= date('F', mktime(0, 0, 0, $month, 1, $register_year)) ?>
                                                            </option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>

                                                <!-- Year Dropdown with Label -->
                                                <div class="filter-group">
                                                    <label for="register_year">YEAR</label>
                                                    <select name="register_year" id="register_year"
                                                        class="form-select form-select-sm"
                                                        onchange="document.getElementById('filterForm').submit()">
                                                        <?php $currentYear = (int) date('Y'); ?>
                                                        <?php for ($year = $currentYear - 5; $year <= $currentYear + 5; $year++): ?>
                                                            <option value="<?= $year ?>" <?= ($year == $register_year) ? 'selected' : '' ?>>
                                                                <?= $year ?>
                                                            </option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>

                                                <!-- Reset Button -->
                                                <button type="button" class="btn-reset"
                                                    onclick="window.location.href='employee-attendance.php'">
                                                    <i class="bx bx-refresh me-1"></i> Reset
                                                </button>

                                            </form>
                                        </div>
                                        <!-- Attendance Table -->
                                        <div class="attendance-wrapper">
                                            <table class="attendance-table">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Day</th>
                                                        <th>Status</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $attendanceStatuses = [
                                                        1 => ['label' => 'Present', 'class' => 'present'],
                                                        2 => ['label' => 'Absent', 'class' => 'absent'],
                                                        3 => ['label' => 'Half Day', 'class' => 'half'],
                                                        4 => ['label' => 'Leave', 'class' => 'leave'],
                                                        5 => ['label' => 'Holiday', 'class' => 'holiday'],
                                                        6 => ['label' => 'Week Off', 'class' => 'weekoff']
                                                    ];

                                                    for ($day = 1; $day <= $register_total_days; $day++):
                                                        $attendanceDate = sprintf('%04d-%02d-%02d', $register_year, $register_month, $day);
                                                        $dateObj = DateTime::createFromFormat('Y-m-d', $attendanceDate);
                                                        $dayName = $dateObj->format('D');
                                                        $formattedDate = $dateObj->format('d-M-Y');

                                                        $hasAttendance = isset($attendanceData[$attendanceDate]);
                                                        $statusLabel = 'Not Marked';
                                                        $statusClass = 'not-marked';
                                                        $remark = '-';

                                                        if ($hasAttendance) {
                                                            $typeId = (int) $attendanceData[$attendanceDate]['type_id'];
                                                            $statusLabel = $attendanceStatuses[$typeId]['label'] ?? 'Unknown';
                                                            $statusClass = $attendanceStatuses[$typeId]['class'] ?? 'not-marked';
                                                            $remark = $statusLabel;
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?= $formattedDate ?></td>
                                                            <td><?= $dayName ?></td>
                                                            <td>
                                                                <span class="attendance-badge <?= $statusClass ?>">
                                                                    <?= $statusLabel ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="remark-text">
                                                                    <?php if ($remark != '-'): ?>
                                                                        <?= $remark ?>
                                                                    <?php else: ?>
                                                                        <span class="no-remark">-</span>
                                                                    <?php endif; ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endfor; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <?php if (empty($attendanceData) && $register_total_days > 0): ?>
                                            <div class="text-center py-4">
                                                <div style="font-size: 48px; color: #d1d5db; margin-bottom: 15px;">📋</div>
                                                <h6 style="color: #6b7a8f; font-weight: 500;">No Attendance Records</h6>
                                                <p style="color: #9ca3af; font-size: 14px;">Your attendance for
                                                    <?= $register_month_name ?>     <?= $register_year ?> is not available.</p>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payslip Link -->
                        <div class="payslip-link-card">
                            <a href="../payroll/employee-payslip.php" class="btn-payslip">
                                <i class="bx bx-receipt"></i> View My Payslip
                            </a>
                            <p style="color: #6b7a8f; font-size: 13px; margin-top: 10px; margin-bottom: 0;">
                                <i class="bx bx-info-circle me-1"></i> Click to view your latest payslip
                            </p>
                        </div>

                    </div>

                    <!-- Footer -->
                    <?php include('../includes/footer.php'); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php include('../includes/script.php'); ?>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        document.addEventListener('click', function (event) {
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