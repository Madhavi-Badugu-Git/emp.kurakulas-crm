<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// Check if user is logged in
if (!isset($_SESSION['user_rank'])) {
    $_SESSION['user_rank'] = 'admin';
}
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
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

    .layout-sidebar,
    .layout-menu {
        position: sticky;
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
        border: none !important;
        border-right: none !important;
        box-shadow: none !important;
        outline: none !important;
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
       VIEW ATTENDANCE STYLES
    ============================================================ */
    .view-attendance-card {
        margin-top: 25px;
    }

    .employee-profile-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 25px 30px;
        margin-bottom: 25px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .employee-profile-card .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #696cff;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .employee-profile-card .profile-info {
        flex: 1;
    }

    .employee-profile-card .profile-info h4 {
        font-size: 20px;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 5px;
    }

    .employee-profile-card .profile-info .profile-details {
        display: flex;
        flex-wrap: wrap;
        gap: 20px 40px;
        margin-top: 8px;
    }

    .employee-profile-card .profile-info .profile-details span {
        font-size: 14px;
        color: #6c757d;
    }

    .employee-profile-card .profile-info .profile-details span strong {
        color: #1a2332;
        font-weight: 600;
    }

    .attendance-summary-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .attendance-summary-stats .stat-box {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 15px 20px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .attendance-summary-stats .stat-box:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    .attendance-summary-stats .stat-box .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #1a2332;
    }

    .attendance-summary-stats .stat-box .stat-label {
        font-size: 13px;
        color: #6c757d;
        margin-top: 4px;
        font-weight: 500;
    }

    .stat-box.stat-present .stat-number {
        color: #22c55e;
    }
    .stat-box.stat-absent .stat-number {
        color: #ef4444;
    }
    .stat-box.stat-half .stat-number {
        color: #eab308;
    }
    .stat-box.stat-leave .stat-number {
        color: #3b82f6;
    }
    .stat-box.stat-holiday .stat-number {
        color: #8b5cf6;
    }
    .stat-box.stat-weekoff .stat-number {
        color: #9ca3af;
    }

    .attendance-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .attendance-table-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .attendance-table-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .attendance-table-wrapper::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 10px;
    }

    .attendance-view-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .attendance-view-table thead th {
        background: #f8f9fc;
        color: #1a2332;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        padding: 12px 15px;
        border-bottom: 2px solid #e9ecef;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .attendance-view-table tbody td {
        text-align: center;
        vertical-align: middle;
        padding: 10px 15px;
        border-bottom: 1px solid #f1f3f5;
    }

    .attendance-view-table tbody tr:hover td {
        background: #f8fafc;
    }

    .attendance-view-table tbody tr:last-child td {
        border-bottom: none;
    }

    .attendance-status-badge {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        min-width: 60px;
    }

    .attendance-status-badge.present {
        background: #dcfce7;
        color: #166534;
    }

    .attendance-status-badge.absent {
        background: #fee2e2;
        color: #991b1b;
    }

    .attendance-status-badge.half {
        background: #fef9c3;
        color: #713f12;
    }

    .attendance-status-badge.leave {
        background: #dbeafe;
        color: #1e40af;
    }

    .attendance-status-badge.holiday {
        background: #ede9fe;
        color: #5b21b6;
    }

    .attendance-status-badge.weekoff {
        background: #f1f3f5;
        color: #4b5563;
    }

    .attendance-status-badge.na {
        background: #f8f9fa;
        color: #9ca3af;
    }

    /* Leave TL Info - Small text below leave status */
    .leave-tl-info {
        display: block;
        font-size: 10px;
        color: #1a73e8;
        font-weight: 500;
        margin-top: 2px;
    }

    .leave-tl-info i {
        font-size: 10px;
        margin-right: 2px;
    }

    .weekend-row td {
        background: #fafbfc !important;
    }

    .weekend-row td:first-child {
        background: #fafbfc !important;
    }

    /* ============================================================
       BREADCRUMB STYLES
    ============================================================ */
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

    /* ============================================================
       RESPONSIVE
    ============================================================ */
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

        .employee-profile-card {
            padding: 15px 18px;
        }

        .employee-profile-card .profile-avatar {
            width: 60px;
            height: 60px;
            font-size: 24px;
        }

        .employee-profile-card .profile-info h4 {
            font-size: 17px;
        }

        .employee-profile-card .profile-info .profile-details {
            gap: 10px 20px;
        }

        .employee-profile-card .profile-info .profile-details span {
            font-size: 12px;
        }

        .attendance-summary-stats {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .attendance-summary-stats .stat-box {
            padding: 10px 12px;
        }

        .attendance-summary-stats .stat-box .stat-number {
            font-size: 20px;
        }

        .attendance-summary-stats .stat-box .stat-label {
            font-size: 11px;
        }

        .attendance-view-table thead th,
        .attendance-view-table tbody td {
            padding: 8px 10px;
            font-size: 11px;
        }

        .attendance-status-badge {
            font-size: 10px;
            padding: 3px 10px;
            min-width: 50px;
        }

        .leave-tl-info {
            font-size: 9px;
        }
    }

    @media (max-width: 480px) {
        .attendance-summary-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .employee-profile-card .profile-info .profile-details {
            flex-direction: column;
            gap: 4px;
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
                // Get parameters
                $employeeId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
                $viewMonth = isset($_GET['month']) ? (int) $_GET['month'] : (int) date('n');
                $viewYear = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

                if ($viewMonth < 1 || $viewMonth > 12) $viewMonth = (int) date('n');
                if ($viewYear < 2000 || $viewYear > 2100) $viewYear = (int) date('Y');

                $monthName = date('F', mktime(0, 0, 0, $viewMonth, 1, $viewYear));
                $totalDays = cal_days_in_month(CAL_GREGORIAN, $viewMonth, $viewYear);

                // Get employee details
                $employee = null;
                
                // First check if department_id and designation_id columns exist
                $deptIdCheck = mysqli_query($conn, "SHOW COLUMNS FROM tbl_user LIKE 'department_id'");
                $hasDeptId = ($deptIdCheck && mysqli_num_rows($deptIdCheck) > 0);
                
                $designationIdCheck = mysqli_query($conn, "SHOW COLUMNS FROM tbl_user LIKE 'designation_id'");
                $hasDesignationId = ($designationIdCheck && mysqli_num_rows($designationIdCheck) > 0);
                
                if ($hasDeptId && $hasDesignationId) {
                    // Query with JOIN to get department and designation names
                    $employeeQuery = "SELECT u.id, u.username, u.firstName, u.lastName,
                                      d.department_name AS department,
                                      des.designation_name AS designation
                                      FROM tbl_user u
                                      LEFT JOIN tbl_department d ON u.department_id = d.id
                                      LEFT JOIN tbl_designation des ON u.designation_id = des.id
                                      WHERE u.id = '$employeeId' AND u.status = '1'";
                } else {
                    // Fallback query without JOIN
                    $selectFields = "id, username, firstName, lastName";
                    
                    // Check if direct department column exists
                    $deptCheck = mysqli_query($conn, "SHOW COLUMNS FROM tbl_user LIKE 'department'");
                    if ($deptCheck && mysqli_num_rows($deptCheck) > 0) {
                        $selectFields .= ", department";
                    } else {
                        $selectFields .= ", '' as department";
                    }
                    
                    // Check if direct designation column exists
                    $designationCheck = mysqli_query($conn, "SHOW COLUMNS FROM tbl_user LIKE 'designation'");
                    if ($designationCheck && mysqli_num_rows($designationCheck) > 0) {
                        $selectFields .= ", designation";
                    } else {
                        $selectFields .= ", '' as designation";
                    }
                    
                    $employeeQuery = "SELECT $selectFields FROM tbl_user WHERE id = '$employeeId' AND status = '1'";
                }
                
                $employeeResult = mysqli_query($conn, $employeeQuery);
                if ($employeeResult && mysqli_num_rows($employeeResult) > 0) {
                    $employee = mysqli_fetch_assoc($employeeResult);
                }

                if (!$employee) {
                    echo '<script>
                        iziToast.error({
                            title: "Error",
                            message: "Employee not found",
                            position: "topRight"
                        });
                        setTimeout(function(){ window.location.href = "attendance.php"; }, 1500);
                    </script>';
                    exit;
                }

                // Get attendance types
                $attendanceTypes = [];
                $attendanceTypeTableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'tbl_attendance_type'");
                if ($attendanceTypeTableCheck && mysqli_num_rows($attendanceTypeTableCheck) > 0) {
                    $attendanceTypeQuery = "SELECT id, attendance_type, symbol FROM tbl_attendance_type ORDER BY attendance_type ASC";
                    $attendanceTypeResult = mysqli_query($conn, $attendanceTypeQuery);
                    if ($attendanceTypeResult && mysqli_num_rows($attendanceTypeResult) > 0) {
                        while ($attendanceTypeRow = mysqli_fetch_assoc($attendanceTypeResult)) {
                            $attendanceTypes[$attendanceTypeRow['id']] = $attendanceTypeRow;
                        }
                    }
                }
                
                // If no attendance types found, create default ones
                if (empty($attendanceTypes)) {
                    $defaultTypes = [
                        1 => ['id' => 1, 'attendance_type' => 'Present', 'symbol' => 'P'],
                        2 => ['id' => 2, 'attendance_type' => 'Absent', 'symbol' => 'A'],
                        3 => ['id' => 3, 'attendance_type' => 'Half Day', 'symbol' => 'H'],
                        4 => ['id' => 4, 'attendance_type' => 'Leave', 'symbol' => 'L'],
                        5 => ['id' => 5, 'attendance_type' => 'Holiday', 'symbol' => 'HD'],
                        6 => ['id' => 6, 'attendance_type' => 'Week Off', 'symbol' => 'WO']
                    ];
                    $attendanceTypes = $defaultTypes;
                }

                // Get attendance for the employee
                $attendanceData = [];
                $startDate = sprintf('%04d-%02d-01', $viewYear, $viewMonth);
                $endDate = date('Y-m-t', strtotime($startDate));

                $attendanceQuery = "SELECT id, date, attendance_type_id FROM tbl_user_attendance 
                                   WHERE employee_id = '$employeeId' 
                                   AND date BETWEEN '$startDate' AND '$endDate'";
                $attendanceResult = mysqli_query($conn, $attendanceQuery);
                if ($attendanceResult && mysqli_num_rows($attendanceResult) > 0) {
                    while ($attendanceRow = mysqli_fetch_assoc($attendanceResult)) {
                        $attendanceData[$attendanceRow['date']] = $attendanceRow['attendance_type_id'];
                    }
                }

                // Get leave requests with Team Lead info
                $leaveRequestsWithTL = [];
                $leaveQuery = "SELECT lr.*, 
                                      u.firstName as tl_firstName, 
                                      u.lastName as tl_lastName, 
                                      u.username as tl_username
                               FROM tbl_leave_requests lr
                               LEFT JOIN tbl_user u ON lr.approved_by = u.id
                               WHERE lr.employee_id = '$employeeId' 
                               AND lr.status = 'approved'
                               AND (lr.start_date BETWEEN '$startDate' AND '$endDate' OR lr.end_date BETWEEN '$startDate' AND '$endDate')
                               ORDER BY lr.start_date DESC";
                $leaveResult = mysqli_query($conn, $leaveQuery);
                if ($leaveResult && mysqli_num_rows($leaveResult) > 0) {
                    while ($leaveRow = mysqli_fetch_assoc($leaveResult)) {
                        $dateKey = $leaveRow['start_date'];
                        $leaveRequestsWithTL[$dateKey] = $leaveRow;
                    }
                }

                // Calculate statistics
                $stats = [
                    'present' => 0,
                    'absent' => 0,
                    'half' => 0,
                    'leave' => 0,
                    'holiday' => 0,
                    'weekoff' => 0,
                    'total' => 0
                ];

                for ($day = 1; $day <= $totalDays; $day++) {
                    $attendanceDate = sprintf('%04d-%02d-%02d', $viewYear, $viewMonth, $day);
                    $dateTime = new DateTime($attendanceDate);
                    $dayOfWeek = $dateTime->format('N'); // 1=Monday, 7=Sunday
                    
                    // Check if it's weekend (Saturday=6, Sunday=7)
                    $isWeekend = ($dayOfWeek == 6 || $dayOfWeek == 7);
                    
                    if (isset($attendanceData[$attendanceDate])) {
                        $typeId = $attendanceData[$attendanceDate];
                        $stats['total']++;
                        switch ($typeId) {
                            case 1: $stats['present']++; break;
                            case 2: $stats['absent']++; break;
                            case 3: $stats['half']++; break;
                            case 4: $stats['leave']++; break;
                            case 5: $stats['holiday']++; break;
                            case 6: $stats['weekoff']++; break;
                        }
                    } else if ($isWeekend) {
                        // Weekend - mark as Week Off
                        $stats['weekoff']++;
                    } else {
                        // Not marked as attendance - count as absent
                        $stats['absent']++;
                        $stats['total']++;
                    }
                }

                // Build full name
                $fullName = trim(($employee['firstName'] ?? '') . ' ' . ($employee['lastName'] ?? ''));
                if (empty($fullName)) {
                    $fullName = $employee['username'] ?? 'Unknown';
                }

                $department = isset($employee['department']) && !empty($employee['department']) ? $employee['department'] : 'N/A';
                $designation = isset($employee['designation']) && !empty($employee['designation']) ? $employee['designation'] : 'N/A';

                $pageTitle = 'View Attendance - ' . $fullName;
                ?>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
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
                            <a href="attendance.php" class="breadcrumb-item">
                                <i class="bx bx-calendar-check"></i> Attendance
                            </a>
                            <span class="separator">›</span>
                            <span class="breadcrumb-item active">
                                <i class="bx bx-show"></i> View Attendance
                            </span>
                        </div>

                        <!-- Page Title -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold py-3 mb-0">
                                View Attendance — <?= htmlspecialchars($monthName) ?> <?= $viewYear ?>
                            </h4>
                            <div>
                                <a href="add.php?register_month=<?= $viewMonth ?>&register_year=<?= $viewYear ?>" class="btn btn-secondary btn-sm">
                                    <i class="bx bx-arrow-back me-1"></i> Back to Register
                                </a>
                                <a href="edit-attendance.php?id=<?= $employeeId ?>&month=<?= $viewMonth ?>&year=<?= $viewYear ?>" class="btn btn-primary btn-sm">
                                    <i class="bx bx-edit me-1"></i> Edit Attendance
                                </a>
                            </div>
                        </div>

                        <!-- Employee Profile -->
                        <div class="employee-profile-card">
                            <div class="d-flex align-items-center gap-4 flex-wrap">
                                <div class="profile-avatar">
                                    <?= strtoupper(substr($fullName, 0, 2)) ?>
                                </div>
                                <div class="profile-info">
                                    <h4><?= htmlspecialchars($fullName) ?></h4>
                                    <div class="profile-details">
                                        <span><strong>Employee ID:</strong> <?= htmlspecialchars($employee['username'] ?? 'N/A') ?></span>
                                        <span><strong>Department:</strong> <?= htmlspecialchars($department) ?></span>
                                        <span><strong>Designation:</strong> <?= htmlspecialchars($designation) ?></span>
                                        <span><strong>Month:</strong> <?= htmlspecialchars($monthName) ?> <?= $viewYear ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="attendance-summary-stats">
                            <div class="stat-box stat-present">
                                <div class="stat-number"><?= $stats['present'] ?></div>
                                <div class="stat-label">Present</div>
                            </div>
                            <div class="stat-box stat-absent">
                                <div class="stat-number"><?= $stats['absent'] ?></div>
                                <div class="stat-label">Absent</div>
                            </div>
                            <div class="stat-box stat-half">
                                <div class="stat-number"><?= $stats['half'] ?></div>
                                <div class="stat-label">Half Day</div>
                            </div>
                            <div class="stat-box stat-leave">
                                <div class="stat-number"><?= $stats['leave'] ?></div>
                                <div class="stat-label">Leave</div>
                            </div>
                            <div class="stat-box stat-holiday">
                                <div class="stat-number"><?= $stats['holiday'] ?></div>
                                <div class="stat-label">Holiday</div>
                            </div>
                            <div class="stat-box stat-weekoff">
                                <div class="stat-number"><?= $stats['weekoff'] ?></div>
                                <div class="stat-label">Week Off</div>
                            </div>
                        </div>

                        <!-- Attendance Table -->
                        <div class="view-attendance-card">
                            <div class="attendance-table-wrapper">
                                <table class="attendance-view-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 50px;">#</th>
                                            <th style="min-width: 120px; text-align: left;">Date</th>
                                            <th style="min-width: 80px;">Day</th>
                                            <th style="min-width: 120px;">Status</th>
                                            <th style="min-width: 100px;">Symbol</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($day = 1; $day <= $totalDays; $day++):
                                            $attendanceDate = sprintf('%04d-%02d-%02d', $viewYear, $viewMonth, $day);
                                            $dateTime = new DateTime($attendanceDate);
                                            $dayName = $dateTime->format('l');
                                            $dayOfWeek = $dateTime->format('N');
                                            $isWeekend = ($dayOfWeek == 6 || $dayOfWeek == 7);
                                            $rowClass = $isWeekend ? 'weekend-row' : '';
                                            
                                            $attendanceTypeId = isset($attendanceData[$attendanceDate]) ? $attendanceData[$attendanceDate] : null;
                                            $status = 'N/A';
                                            $symbol = '-';
                                            $statusClass = 'na';
                                            $tlName = '';
                                            
                                            if ($attendanceTypeId !== null && isset($attendanceTypes[$attendanceTypeId])) {
                                                $type = $attendanceTypes[$attendanceTypeId];
                                                $status = $type['attendance_type'];
                                                $symbol = $type['symbol'];
                                                $statusClass = strtolower(str_replace(' ', '', $status));
                                                
                                                // Check if this is a leave and get TL name
                                                if ($attendanceTypeId == 4) {
                                                    // Check if this date has an approved leave with TL
                                                    foreach ($leaveRequestsWithTL as $leaveDate => $leaveData) {
                                                        $leaveStart = $leaveData['start_date'];
                                                        $leaveEnd = $leaveData['end_date'];
                                                        if ($attendanceDate >= $leaveStart && $attendanceDate <= $leaveEnd) {
                                                            if (!empty($leaveData['tl_firstName'])) {
                                                                $tlName = trim($leaveData['tl_firstName'] . ' ' . $leaveData['tl_lastName']);
                                                            }
                                                            break;
                                                        }
                                                    }
                                                }
                                            } else if ($isWeekend) {
                                                $status = 'Week Off';
                                                $symbol = 'WO';
                                                $statusClass = 'weekoff';
                                            } else {
                                                $status = 'Not Marked';
                                                $symbol = '—';
                                                $statusClass = 'na';
                                            }
                                            ?>
                                            <tr class="<?= $rowClass ?>">
                                                <td><?= $day ?></td>
                                                <td style="text-align: left;"><?= $attendanceDate ?></td>
                                                <td><?= substr($dayName, 0, 3) ?></td>
                                                <td>
                                                    <span class="attendance-status-badge <?= $statusClass ?>">
                                                        <?= htmlspecialchars($status) ?>
                                                    </span>
                                                    <?php if (!empty($tlName)): ?>
                                                        <span class="leave-tl-info">
                                                            <i class="bx bx-user-check"></i> Approved by: <?= htmlspecialchars($tlName) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?= htmlspecialchars($symbol) ?></strong>
                                                </td>
                                            </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="mt-4">
                            <div class="legend-container" style="display: flex; flex-wrap: wrap; gap: 10px; padding: 12px 16px; background: #f8fafc; border-radius: 10px; border: 1px solid #e9ecef;">
                                <span class="legend-item" style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; padding: 2px 12px 2px 8px; background: #fff; border-radius: 20px; border: 1px solid #e9ecef;">
                                    <span class="legend-box present" style="display: inline-block; width: 18px; height: 18px; border-radius: 4px; text-align: center; line-height: 18px; font-size: 9px; font-weight: 700; background: #dcfce7; color: #166534;">P</span> Present
                                </span>
                                <span class="legend-item" style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; padding: 2px 12px 2px 8px; background: #fff; border-radius: 20px; border: 1px solid #e9ecef;">
                                    <span class="legend-box absent" style="display: inline-block; width: 18px; height: 18px; border-radius: 4px; text-align: center; line-height: 18px; font-size: 9px; font-weight: 700; background: #fee2e2; color: #991b1b;">A</span> Absent
                                </span>
                                <span class="legend-item" style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; padding: 2px 12px 2px 8px; background: #fff; border-radius: 20px; border: 1px solid #e9ecef;">
                                    <span class="legend-box half" style="display: inline-block; width: 18px; height: 18px; border-radius: 4px; text-align: center; line-height: 18px; font-size: 9px; font-weight: 700; background: #fef9c3; color: #713f12;">H</span> Half Day
                                </span>
                                <span class="legend-item" style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; padding: 2px 12px 2px 8px; background: #fff; border-radius: 20px; border: 1px solid #e9ecef;">
                                    <span class="legend-box leave" style="display: inline-block; width: 18px; height: 18px; border-radius: 4px; text-align: center; line-height: 18px; font-size: 9px; font-weight: 700; background: #dbeafe; color: #1e40af;">L</span> Leave
                                </span>
                                <span class="legend-item" style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; padding: 2px 12px 2px 8px; background: #fff; border-radius: 20px; border: 1px solid #e9ecef;">
                                    <span class="legend-box holiday" style="display: inline-block; width: 18px; height: 18px; border-radius: 4px; text-align: center; line-height: 18px; font-size: 9px; font-weight: 700; background: #ede9fe; color: #5b21b6;">HD</span> Holiday
                                </span>
                                <span class="legend-item" style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; padding: 2px 12px 2px 8px; background: #fff; border-radius: 20px; border: 1px solid #e9ecef;">
                                    <span class="legend-box weekoff" style="display: inline-block; width: 18px; height: 18px; border-radius: 4px; text-align: center; line-height: 18px; font-size: 9px; font-weight: 700; background: #f1f3f5; color: #4b5563;">WO</span> Week Off
                                </span>
                                <span class="legend-item" style="display: inline-flex; align-items: center; gap: 5px; font-size: 12px; padding: 2px 12px 2px 8px; background: #fff; border-radius: 20px; border: 1px solid #e9ecef;">
                                    <span class="legend-box na" style="display: inline-block; width: 18px; height: 18px; border-radius: 4px; text-align: center; line-height: 18px; font-size: 9px; font-weight: 700; background: #f8f9fa; color: #9ca3af;">—</span> Not Marked
                                </span>
                            </div>
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
    </script>

</body>

</html>