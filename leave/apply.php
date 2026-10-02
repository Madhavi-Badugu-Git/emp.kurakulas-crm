<?php
// Start output buffering
ob_start();

session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// ============================================================
// AUTH CHECK — NO DEMO FALLBACK
// ============================================================
if (!isset($_SESSION['loggedInUser']) && !isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Get logged-in user ID — NO fallback to 1
// $loggedInUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

// if ($loggedInUserId <= 0) {
//     $_SESSION['toast_error'] = "Session expired. Please login again.";
//     header('Location: ../login.php');
//     exit();
// }

// ============================================================
// HANDLE FORM SUBMISSION (PRG Pattern)
// ============================================================
if (isset($_POST['apply_leave_submit'])) {
    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['toast_error'] = 'Security token mismatch. Please try again.';
        header('Location: apply.php');
        exit;
    }

    $leaveTypeId = isset($_POST['leave_type_id']) ? (int) $_POST['leave_type_id'] : 0;
    $fromDate    = isset($_POST['from_date']) ? trim($_POST['from_date']) : '';
    $toDate      = isset($_POST['to_date']) ? trim($_POST['to_date']) : '';
    $reason      = isset($_POST['reason']) ? trim($_POST['reason']) : '';

    // Validation
    if ($leaveTypeId <= 0) {
        $_SESSION['toast_error'] = 'Please select a leave type.';
    } elseif (empty($fromDate) || empty($toDate)) {
        $_SESSION['toast_error'] = 'Please select both From and To dates.';
    } elseif (strtotime($fromDate) > strtotime($toDate)) {
        $_SESSION['toast_error'] = 'From Date cannot be greater than To Date.';
    } elseif (empty($reason)) {
        $_SESSION['toast_error'] = 'Please provide a reason for your leave.';
    } else {
        // Verify leave_type_id exists in tbl_leave_type
        $validType = false;
        $typeCheck = mysqli_query($conn, "SELECT id FROM tbl_leave_type WHERE id = '$leaveTypeId' LIMIT 1");
        if ($typeCheck && mysqli_num_rows($typeCheck) > 0) {
            $validType = true;
        }

        if (!$validType) {
            $_SESSION['toast_error'] = 'Invalid leave type selected. Please choose a valid type.';
            header('Location: apply.php');
            exit;
        }

        $fromDateEsc = mysqli_real_escape_string($conn, $fromDate);
        $toDateEsc   = mysqli_real_escape_string($conn, $toDate);
        $reasonEsc   = mysqli_real_escape_string($conn, $reason);

        $employeeId = (int) $loggedInUserId;

        // Check if leave_type_id column exists
        $colCheck = mysqli_query($conn, "SHOW COLUMNS FROM tbl_leave_requests LIKE 'leave_type_id'");
        $hasLeaveTypeId = ($colCheck && mysqli_num_rows($colCheck) > 0);

        if ($hasLeaveTypeId) {
            $insertSql = "INSERT INTO tbl_leave_requests 
                          (employee_id, leave_type_id, start_date, end_date, reason, status, created_at) 
                          VALUES 
                          ('$employeeId', '$leaveTypeId', '$fromDateEsc', '$toDateEsc', '$reasonEsc', 'pending', NOW())";
        } else {
            $insertSql = "INSERT INTO tbl_leave_requests 
                          (employee_id, start_date, end_date, reason, status, created_at) 
                          VALUES 
                          ('$employeeId', '$fromDateEsc', '$toDateEsc', '$reasonEsc', 'pending', NOW())";
        }

        if (mysqli_query($conn, $insertSql)) {
            $_SESSION['toast_success'] = 'Leave request submitted successfully. Waiting for approval.';
            header('Location: apply.php');
            exit;
        } else {
            $_SESSION['toast_error'] = 'Failed to submit leave request: ' . mysqli_error($conn);
        }
    }

    header('Location: apply.php');
    exit;
}

// ============================================================
// CSRF Token
// ============================================================
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

// ============================================================
// FETCH LOGGED-IN USER DETAILS
// ============================================================
$userDetails = [];
$userQuery = "SELECT firstName, lastName, username, employee_no, department, designation 
              FROM tbl_user WHERE id = '$loggedInUserId' LIMIT 1";
$userResult = mysqli_query($conn, $userQuery);
if ($userResult && mysqli_num_rows($userResult) > 0) {
    $userDetails = mysqli_fetch_assoc($userResult);
}

$displayName = trim(($userDetails['firstName'] ?? '') . ' ' . ($userDetails['lastName'] ?? ''));
if (empty($displayName)) {
    $displayName = $userDetails['username'] ?? 'Employee';
}

// ============================================================
// FETCH LEAVE TYPES FROM DATABASE (NO FALLBACK)
// ============================================================
$leaveTypes = [];
$leaveTypeTableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'tbl_leave_type'");
if ($leaveTypeTableCheck && mysqli_num_rows($leaveTypeTableCheck) > 0) {
    $statusColCheck = mysqli_query($conn, "SHOW COLUMNS FROM tbl_leave_type LIKE 'status'");
    $hasStatusCol = ($statusColCheck && mysqli_num_rows($statusColCheck) > 0);

    if ($hasStatusCol) {
        $ltQuery = "SELECT id, leave_type, days_per_year 
                    FROM tbl_leave_type 
                    WHERE status = 1 
                    ORDER BY leave_type ASC";
    } else {
        $ltQuery = "SELECT id, leave_type, days_per_year 
                    FROM tbl_leave_type 
                    ORDER BY leave_type ASC";
    }

    $ltResult = mysqli_query($conn, $ltQuery);
    if ($ltResult && mysqli_num_rows($ltResult) > 0) {
        while ($ltRow = mysqli_fetch_assoc($ltResult)) {
            $leaveTypes[] = $ltRow;
        }
    }
}

$pageTitle = 'Apply Leave';
include('../includes/header.php');
?>

<style>
    /* ============================================================
       LAYOUT
    ============================================================ */
    .layout-container { display: flex; min-height: 100vh; position: relative; }

    .layout-sidebar {
        position: sticky; top: 0; left: 0; height: 100vh;
        overflow-y: auto; overflow-x: hidden; flex-shrink: 0;
        width: 260px; background: #1a2332; color: #fff; z-index: 1000;
        transition: all .3s ease; border: none !important; box-shadow: none !important;
    }
    .layout-sidebar::-webkit-scrollbar { width: 4px; }
    .layout-sidebar::-webkit-scrollbar-thumb { background: #4a5568; border-radius: 4px; }
    .layout-page { flex: 1; min-height: 100vh; overflow-y: auto; }

    /* ============================================================
       BREADCRUMB
    ============================================================ */
    .breadcrumb-box {
        background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 10px;
        padding: 12px 20px; margin-bottom: 20px; display: flex;
        align-items: center; flex-wrap: wrap; gap: 8px;
    }
    .breadcrumb-box .breadcrumb-item {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 15px; font-weight: 500; color: #696cff;
        text-decoration: none; transition: all .2s;
    }
    .breadcrumb-box .breadcrumb-item i { font-size: 18px; color: #696cff; }
    .breadcrumb-box .breadcrumb-item:hover { color: #4a4dc9; text-decoration: underline; }
    .breadcrumb-box .breadcrumb-item.active { color: #1a2332; font-weight: 600; pointer-events: none; }
    .breadcrumb-box .breadcrumb-item.active i { color: #1a2332; }
    .breadcrumb-box .separator { color: #9ca3af; font-size: 16px; margin: 0 4px; font-weight: 600; }

    /* ============================================================
       PAGE HEADER
    ============================================================ */
    .page-header-box {
        background: #fff; border-radius: 12px; padding: 20px 24px;
        display: flex; align-items: center; gap: 14px;
        margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,.05);
        border-left: 4px solid #696cff;
    }
    .header-icon {
        width: 50px; height: 50px; border-radius: 12px;
        background: linear-gradient(135deg, #696cff, #5a5de0);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 26px;
        box-shadow: 0 4px 12px rgba(105,108,255,.3);
    }
    .header-text h4 { font-size: 20px; font-weight: 700; color: #1a2332; margin: 0 0 2px; }
    .header-text p { font-size: 12.5px; color: #6b7a8f; margin: 0; }

    /* ============================================================
       FORM CARD
    ============================================================ */
    .leave-form-card {
        background: #fff; border-radius: 14px; padding: 30px;
        max-width: 720px; margin: 0 auto;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
    }
    .leave-form-card .form-group { margin-bottom: 22px; }
    .leave-form-card label {
        display: block; font-size: 13.5px; font-weight: 600;
        color: #1a2332; margin-bottom: 8px;
    }
    .leave-form-card label .req { color: #dc2626; margin-left: 2px; }

    .leave-form-card .form-control {
        width: 100%; padding: 12px 14px;
        border: 1px solid #e4e6eb; border-radius: 10px;
        font-size: 14px; font-family: inherit; background: #fff;
        color: #050505; transition: all .2s ease;
    }
    .leave-form-card .form-control:focus {
        outline: none; border-color: #696cff;
        box-shadow: 0 0 0 3px rgba(105,108,255,.12);
    }
    .leave-form-card textarea.form-control { min-height: 110px; resize: vertical; }

    .leave-form-card .form-row {
        display: grid; grid-template-columns: 1fr 1fr; gap: 18px;
    }
    @media (max-width: 600px) {
        .leave-form-card .form-row { grid-template-columns: 1fr; }
    }

    .days-hint {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 12.5px; color: #65676b; background: #e7f3ff;
        padding: 7px 14px; border-radius: 20px; margin-top: 10px;
    }
    .days-hint strong { color: #696cff; font-size: 14px; }
    .days-hint i { color: #696cff; font-size: 16px; }

    .leave-form-card .form-actions {
        display: flex; gap: 12px; justify-content: flex-end;
        margin-top: 28px; padding-top: 24px;
        border-top: 1px solid #e4e6eb;
    }

    .btn-submit-leave {
        background: linear-gradient(135deg, #696cff, #5a5de0);
        border: none; padding: 12px 28px; color: #fff;
        font-size: 14px; font-weight: 600; border-radius: 10px;
        cursor: pointer; display: inline-flex; align-items: center;
        gap: 8px; transition: all .25s; font-family: inherit;
    }
    .btn-submit-leave:hover {
        background: linear-gradient(135deg, #5a5de0, #4a4dc9);
        transform: translateY(-1px);
    }
    .btn-cancel-leave {
        background: #f0f2f5; border: none; padding: 12px 24px;
        color: #65676b; font-size: 14px; font-weight: 600;
        border-radius: 10px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; font-family: inherit;
        transition: background .2s;
    }
    .btn-cancel-leave:hover { background: #e4e6eb; color: #1a2332; }

    .no-leave-types {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13.5px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 768px) {
        .layout-sidebar {
            position: fixed; left: -280px; width: 280px;
            transition: left .3s ease; z-index: 9999;
        }
        .layout-sidebar.open { left: 0; }
        .layout-sidebar-overlay {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,.5); z-index: 9998;
        }
        .layout-sidebar-overlay.active { display: block; }

        .leave-form-card { padding: 20px; }
        .leave-form-card .form-actions { flex-direction: column-reverse; }
        .btn-submit-leave, .btn-cancel-leave { width: 100%; justify-content: center; }
    }
</style>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <div class="layout-sidebar" id="sidebar">
                <?php include('../includes/sideMenu.php'); ?>
            </div>

            <div class="layout-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

            <div class="layout-page">
                <?php include('../includes/navbar.php'); ?>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Breadcrumb -->
                        <div class="breadcrumb-box">
                            <a href="../dashboard/superAdmin" class="breadcrumb-item">
                                <i class="bx bx-home"></i> Dashboard
                            </a>
                            <span class="separator">›</span>
                            <a href="javascript:void(0);" class="breadcrumb-item">
                                <i class="bx bx-calendar-check"></i> Leave Management
                            </a>
                            <span class="separator">›</span>
                            <span class="breadcrumb-item active">
                                <i class="bx bx-calendar-plus"></i> Apply Leave
                            </span>
                        </div>

                        <!-- Page Header -->
                        <div class="page-header-box">
                            <div class="header-icon"><i class="bx bx-calendar-plus"></i></div>
                            <div class="header-text">
                                <h4>Apply for Leave</h4>
                                <p>Hello <strong><?= htmlspecialchars($displayName) ?></strong>, submit a new leave request</p>
                            </div>
                        </div>

                        <!-- Form Card -->
                        <div class="leave-form-card">

                            <?php if (empty($leaveTypes)): ?>
                                <div class="no-leave-types">
                                    <i class="bx bx-error-circle"></i>
                                    <strong>No leave types configured.</strong>
                                    Please contact your administrator to add leave types in <code>tbl_leave_type</code>.
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="" id="applyLeaveForm">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                                <!-- Leave Type -->
                                <div class="form-group">
                                    <label>Leave Type <span class="req">*</span></label>
                                    <select name="leave_type_id" id="leave_type_id" class="form-control" required>
                                        <option value="">-- Select Leave Type --</option>
                                        <?php foreach ($leaveTypes as $lt): ?>
                                            <option value="<?= (int) $lt['id'] ?>">
                                                <?= htmlspecialchars($lt['leave_type']) ?>
                                                <?php if (!empty($lt['days_per_year']) && $lt['days_per_year'] > 0): ?>
                                                    (<?= (int) $lt['days_per_year'] ?> days/year)
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- From / To Dates -->
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>From Date <span class="req">*</span></label>
                                        <input type="date" name="from_date" id="from_date" class="form-control"
                                               value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label>To Date <span class="req">*</span></label>
                                        <input type="date" name="to_date" id="to_date" class="form-control"
                                               value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>

                                <!-- Days Hint -->
                                <div class="days-hint">
                                    <i class="bx bx-info-circle"></i>
                                    Total: <strong id="totalDays">1</strong> day(s)
                                </div>

                                <!-- Reason -->
                                <div class="form-group" style="margin-top:22px;">
                                    <label>Reason <span class="req">*</span></label>
                                    <textarea name="reason" class="form-control" required
                                              placeholder="Briefly explain the reason for your leave..."></textarea>
                                </div>

                                <!-- Actions -->
                                <div class="form-actions">
                                    <a href="apply.php" class="btn-cancel-leave">
                                        <i class="bx bx-x"></i> Cancel
                                    </a>
                                    <button type="submit" name="apply_leave_submit" class="btn-submit-leave"
                                            <?= empty($leaveTypes) ? 'disabled' : '' ?>>
                                        <i class="bx bx-send"></i> Submit Request
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>

                    <?php include('../includes/footer.php'); ?>
                </div>
            </div>
        </div>
    </div>

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

        document.addEventListener('DOMContentLoaded', function () {
            const fromDateInput = document.getElementById('from_date');
            const toDateInput   = document.getElementById('to_date');
            const totalDaysEl   = document.getElementById('totalDays');

            function calculateDays() {
                const from = fromDateInput.value;
                const to   = toDateInput.value;

                if (!from || !to) {
                    totalDaysEl.textContent = '0';
                    return;
                }

                const fromDate = new Date(from);
                const toDate   = new Date(to);

                if (toDate < fromDate) {
                    totalDaysEl.textContent = '0';
                    totalDaysEl.style.color = '#dc2626';
                    return;
                }

                const diffTime = Math.abs(toDate - fromDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                totalDaysEl.textContent = diffDays;
                totalDaysEl.style.color = '#696cff';
            }

            fromDateInput.addEventListener('change', calculateDays);
            toDateInput.addEventListener('change', calculateDays);
            calculateDays();

            // Show toast messages from session
            <?php if (isset($_SESSION['toast_success'])): ?>
                iziToast.success({
                    title: 'Success',
                    message: '<?= addslashes($_SESSION['toast_success']) ?>',
                    position: 'topRight'
                });
                <?php unset($_SESSION['toast_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['toast_error'])): ?>
                iziToast.error({
                    title: 'Error',
                    message: '<?= addslashes($_SESSION['toast_error']) ?>',
                    position: 'topRight'
                });
                <?php unset($_SESSION['toast_error']); ?>
            <?php endif; ?>
        });
    </script>

</body>
</html>