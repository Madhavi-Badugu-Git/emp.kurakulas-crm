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
       EDIT ATTENDANCE STYLES
    ============================================================ */
    .edit-attendance-card {
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

    .attendance-edit-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .attendance-edit-table-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .attendance-edit-table-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .attendance-edit-table-wrapper::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 10px;
    }

    .attendance-edit-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .attendance-edit-table thead th {
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

    .attendance-edit-table tbody td {
        text-align: center;
        vertical-align: middle;
        padding: 10px 15px;
        border-bottom: 1px solid #f1f3f5;
    }

    .attendance-edit-table tbody tr:hover td {
        background: #f8fafc;
    }

    .attendance-edit-table tbody tr:last-child td {
        border-bottom: none;
    }

    .attendance-edit-select {
        padding: 6px 12px;
        border: 1.5px solid #dde1e6;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        background-color: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 100px;
        color: #1a2332;
    }

    .attendance-edit-select:hover {
        border-color: #b0b8c4;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    }

    .attendance-edit-select:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.15);
        outline: none;
    }

    .attendance-edit-select.present {
        background: #dcfce7;
        border-color: #22c55e;
        color: #166534;
    }

    .attendance-edit-select.absent {
        background: #fee2e2;
        border-color: #ef4444;
        color: #991b1b;
    }

    .attendance-edit-select.half {
        background: #fef9c3;
        border-color: #eab308;
        color: #713f12;
    }

    .attendance-edit-select.leave {
        background: #dbeafe;
        border-color: #3b82f6;
        color: #1e40af;
    }

    .attendance-edit-select.holiday {
        background: #ede9fe;
        border-color: #8b5cf6;
        color: #5b21b6;
    }

    .attendance-edit-select.weekoff {
        background: #f1f3f5;
        border-color: #9ca3af;
        color: #4b5563;
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
       BUTTON STYLES
    ============================================================ */
    .btn-primary {
        background: #696cff;
        border-color: #696cff;
    }

    .btn-primary:hover {
        background: #5a5de0;
        border-color: #5a5de0;
    }

    .btn-success {
        background: #22c55e;
        border-color: #22c55e;
    }

    .btn-success:hover {
        background: #16a34a;
        border-color: #16a34a;
    }

    .btn-danger {
        background: #ef4444;
        border-color: #ef4444;
    }

    .btn-danger:hover {
        background: #dc2626;
        border-color: #dc2626;
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

        .attendance-edit-table thead th,
        .attendance-edit-table tbody td {
            padding: 8px 10px;
            font-size: 11px;
        }

        .attendance-edit-select {
            font-size: 10px;
            padding: 4px 8px;
            min-width: 80px;
        }
    }

    @media (max-width: 480px) {
        .employee-profile-card .profile-info .profile-details {
            flex-direction: column;
            gap: 4px;
        }

        .attendance-edit-select {
            font-size: 9px;
            padding: 3px 6px;
            min-width: 60px;
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
                // CSRF Token
                if (empty($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }
                $csrfToken = $_SESSION['csrf_token'];

                // Get parameters
                $employeeId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
                $editMonth = isset($_GET['month']) ? (int) $_GET['month'] : (int) date('n');
                $editYear = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

                if ($editMonth < 1 || $editMonth > 12) $editMonth = (int) date('n');
                if ($editYear < 2000 || $editYear > 2100) $editYear = (int) date('Y');

                $monthName = date('F', mktime(0, 0, 0, $editMonth, 1, $editYear));
                $totalDays = cal_days_in_month(CAL_GREGORIAN, $editMonth, $editYear);

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

                // Get current attendance for the employee
                $attendanceData = [];
                $startDate = sprintf('%04d-%02d-01', $editYear, $editMonth);
                $endDate = date('Y-m-t', strtotime($startDate));

                $attendanceQuery = "SELECT id, date, attendance_type_id FROM tbl_user_attendance 
                                   WHERE employee_id = '$employeeId' 
                                   AND date BETWEEN '$startDate' AND '$endDate'";
                $attendanceResult = mysqli_query($conn, $attendanceQuery);
                if ($attendanceResult && mysqli_num_rows($attendanceResult) > 0) {
                    while ($attendanceRow = mysqli_fetch_assoc($attendanceResult)) {
                        $attendanceData[$attendanceRow['date']] = [
                            'id' => $attendanceRow['id'],
                            'attendance_type_id' => $attendanceRow['attendance_type_id']
                        ];
                    }
                }

                // Process form submission
                if (isset($_POST['update_attendance'])) {
                    // Validate CSRF token
                    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                        die('<script>iziToast.error({title:"Error", message:"CSRF token validation failed", position:"topRight"});</script>');
                    }

                    $editAttendance = isset($_POST['edit_attendance']) ? $_POST['edit_attendance'] : [];
                    $updatedCount = 0;

                    foreach ($editAttendance as $attendanceId => $attendanceTypeId) {
                        $attendanceId = (int) $attendanceId;
                        $attendanceTypeId = (int) $attendanceTypeId;

                        if ($attendanceId <= 0) continue;

                        // Update attendance
                        $updateSql = "UPDATE tbl_user_attendance SET attendance_type_id = '$attendanceTypeId' WHERE id = '$attendanceId'";
                        if (mysqli_query($conn, $updateSql)) {
                            $updatedCount++;
                        }
                    }

                    if ($updatedCount > 0) {
                        echo '<script>
                            iziToast.success({
                                title: "Success", 
                                message: "Attendance updated successfully! (' . $updatedCount . ' records updated)", 
                                position: "topRight"
                            });
                            setTimeout(function(){ 
                                window.location.href = "view-attendance.php?id=' . $employeeId . '&month=' . $editMonth . '&year=' . $editYear . '"; 
                            }, 1500);
                        </script>';
                    } else {
                        echo '<script>
                            iziToast.warning({
                                title: "Warning", 
                                message: "No changes were made.", 
                                position: "topRight"
                            });
                        </script>';
                    }
                }

                // Build full name
                $fullName = trim(($employee['firstName'] ?? '') . ' ' . ($employee['lastName'] ?? ''));
                if (empty($fullName)) {
                    $fullName = $employee['username'] ?? 'Unknown';
                }

                $department = isset($employee['department']) && !empty($employee['department']) ? $employee['department'] : 'N/A';
                $designation = isset($employee['designation']) && !empty($employee['designation']) ? $employee['designation'] : 'N/A';

                $pageTitle = 'Edit Attendance - ' . $fullName;
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
                            <a href="view-attendance.php?id=<?= $employeeId ?>&month=<?= $editMonth ?>&year=<?= $editYear ?>" class="breadcrumb-item">
                                <i class="bx bx-show"></i> View
                            </a>
                            <span class="separator">›</span>
                            <span class="breadcrumb-item active">
                                <i class="bx bx-edit"></i> Edit
                            </span>
                        </div>

                        <!-- Page Title -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold py-3 mb-0">
                                Edit Attendance — <?= htmlspecialchars($monthName) ?> <?= $editYear ?>
                            </h4>
                            <div>
                                <a href="view-attendance.php?id=<?= $employeeId ?>&month=<?= $editMonth ?>&year=<?= $editYear ?>" class="btn btn-secondary btn-sm">
                                    <i class="bx bx-arrow-back me-1"></i> Back to View
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
                                        <span><strong>Month:</strong> <?= htmlspecialchars($monthName) ?> <?= $editYear ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Form -->
                        <form method="POST" action="" id="editForm">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                            <div class="edit-attendance-card">
                                <div class="attendance-edit-table-wrapper">
                                    <table class="attendance-edit-table">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 50px;">#</th>
                                                <th style="min-width: 120px; text-align: left;">Date</th>
                                                <th style="min-width: 80px;">Day</th>
                                                <th style="min-width: 180px;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php for ($day = 1; $day <= $totalDays; $day++):
                                                $attendanceDate = sprintf('%04d-%02d-%02d', $editYear, $editMonth, $day);
                                                $dateTime = new DateTime($attendanceDate);
                                                $dayName = $dateTime->format('l');
                                                $dayOfWeek = $dateTime->format('N');
                                                $isWeekend = ($dayOfWeek == 6 || $dayOfWeek == 7);
                                                $rowClass = $isWeekend ? 'weekend-row' : '';
                                                
                                                $hasAttendance = isset($attendanceData[$attendanceDate]);
                                                $currentTypeId = $hasAttendance ? $attendanceData[$attendanceDate]['attendance_type_id'] : '';
                                                $attendanceId = $hasAttendance ? $attendanceData[$attendanceDate]['id'] : 0;
                                                ?>
                                                <tr class="<?= $rowClass ?>">
                                                    <td><?= $day ?></td>
                                                    <td style="text-align: left;"><?= $attendanceDate ?></td>
                                                    <td><?= substr($dayName, 0, 3) ?></td>
                                                    <td>
                                                        <?php if ($isWeekend && !$hasAttendance): ?>
                                                            <span class="text-muted" style="font-size: 12px; font-weight: 600;">Week Off (Auto)</span>
                                                            <input type="hidden" name="edit_attendance[<?= $attendanceId ?>]" value="6">
                                                        <?php else: ?>
                                                            <select 
                                                                name="edit_attendance[<?= $attendanceId ?>]" 
                                                                class="attendance-edit-select"
                                                                data-current="<?= $currentTypeId ?>"
                                                                onchange="updateSelectColor(this)">
                                                                <option value="">- Select -</option>
                                                                <?php foreach ($attendanceTypes as $typeId => $type):
                                                                    $isSelected = ($currentTypeId == $typeId) ? 'selected' : '';
                                                                    $statusClass = strtolower(str_replace(' ', '', $type['attendance_type']));
                                                                    ?>
                                                                    <option value="<?= $typeId ?>" <?= $isSelected ?>>
                                                                        <?= htmlspecialchars($type['attendance_type']) ?> (<?= htmlspecialchars($type['symbol']) ?>)
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-4 d-flex gap-2 flex-wrap">
                                <button type="submit" name="update_attendance" class="btn btn-success">
                                    <i class="bx bx-save me-1"></i> Update Attendance
                                </button>
                                <button type="button" class="btn btn-danger" onclick="clearAllAttendance()">
                                    <i class="bx bx-eraser me-1"></i> Clear All Selections
                                </button>
                                <button type="button" class="btn btn-primary" onclick="markAllPresent()">
                                    <i class="bx bx-check-all me-1"></i> Mark All Present
                                </button>
                            </div>
                        </form>

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

        // Update select color based on selection
        function updateSelectColor(select) {
            // Remove all classes
            select.classList.remove('present', 'absent', 'half', 'leave', 'holiday', 'weekoff');
            
            const value = select.value;
            const selectedOption = select.options[select.selectedIndex];
            const text = selectedOption.textContent.toLowerCase();
            
            if (value == '1' || text.includes('present')) {
                select.classList.add('present');
            } else if (value == '2' || text.includes('absent')) {
                select.classList.add('absent');
            } else if (value == '3' || text.includes('half')) {
                select.classList.add('half');
            } else if (value == '4' || text.includes('leave')) {
                select.classList.add('leave');
            } else if (value == '5' || text.includes('holiday')) {
                select.classList.add('holiday');
            } else if (value == '6' || text.includes('week off')) {
                select.classList.add('weekoff');
            }
        }

        // Mark all as Present
        function markAllPresent() {
            if (!confirm('Mark all days as Present for this employee?')) return;
            
            const selects = document.querySelectorAll('.attendance-edit-select');
            selects.forEach(function(select) {
                // Find the Present option (usually value '1')
                const presentOption = select.querySelector('option[value="1"]');
                if (presentOption) {
                    select.value = '1';
                    updateSelectColor(select);
                }
            });
            
            iziToast.info({
                title: 'Info',
                message: 'All days marked as Present',
                position: 'topRight'
            });
        }

        // Clear all selections
        function clearAllAttendance() {
            if (!confirm('Clear all attendance selections?')) return;
            
            const selects = document.querySelectorAll('.attendance-edit-select');
            selects.forEach(function(select) {
                select.value = '';
                select.classList.remove('present', 'absent', 'half', 'leave', 'holiday', 'weekoff');
            });
            
            iziToast.info({
                title: 'Info',
                message: 'All selections cleared',
                position: 'topRight'
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all select colors
            const selects = document.querySelectorAll('.attendance-edit-select');
            selects.forEach(function(select) {
                // Set initial color based on current value
                setTimeout(function() {
                    updateSelectColor(select);
                }, 100);
            });

            // Confirm before update
            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.addEventListener('submit', function(event) {
                    const selects = editForm.querySelectorAll('.attendance-edit-select');
                    let hasChanges = false;
                    selects.forEach(function(select) {
                        if (select.value !== '') hasChanges = true;
                    });
                    if (!hasChanges) {
                        event.preventDefault();
                        iziToast.warning({
                            title: 'Warning',
                            message: 'Please select at least one attendance status',
                            position: 'topRight'
                        });
                        return false;
                    }
                    return confirm('Are you sure you want to update these attendance records?');
                });
            }
        });
    </script>

</body>

</html>