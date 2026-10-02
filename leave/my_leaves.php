<?php
// Start output buffering
ob_start();

session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

// ============================================================
// AUTH CHECK
// ============================================================
if (!isset($_SESSION['loggedInUser']) && !isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}


// ============================================================
// FETCH USER DETAILS
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
// FETCH THIS USER'S LEAVE REQUESTS ONLY
// ============================================================
$myLeaves = [];

// Check if tbl_leave_type exists
$hasLeaveType = false;
$ltCheck = mysqli_query($conn, "SHOW TABLES LIKE 'tbl_leave_type'");
if ($ltCheck && mysqli_num_rows($ltCheck) > 0) {
    $hasLeaveType = true;
}

// Check if leave_type_id exists
$colCheckLT = mysqli_query($conn, "SHOW COLUMNS FROM tbl_leave_requests LIKE 'leave_type_id'");
$hasLeaveTypeId = ($colCheckLT && mysqli_num_rows($colCheckLT) > 0);

// Build query
if ($hasLeaveType && $hasLeaveTypeId) {
    $query = "SELECT lr.*, 
                     lt.leave_type AS leave_type_name
              FROM tbl_leave_requests lr
              LEFT JOIN tbl_leave_type lt ON lr.leave_type_id = lt.id
              WHERE lr.employee_id = '$loggedInUserId'
              ORDER BY lr.created_at DESC";
} else {
    $query = "SELECT lr.*, 
                     'Leave' AS leave_type_name
              FROM tbl_leave_requests lr
              WHERE lr.employee_id = '$loggedInUserId'
              ORDER BY lr.created_at DESC";
}

$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $myLeaves[] = $row;
    }
}

// Count by status
$totalCount    = count($myLeaves);
$pendingCount  = 0;
$approvedCount = 0;
$rejectedCount = 0;

foreach ($myLeaves as $leave) {
    if ($leave['status'] === 'pending')       $pendingCount++;
    elseif ($leave['status'] === 'approved')  $approvedCount++;
    elseif ($leave['status'] === 'rejected')  $rejectedCount++;
}

// Helper
function getLeaveTypeDisplay($leave) {
    if (!empty($leave['leave_type_name'])) {
        return $leave['leave_type_name'];
    }
    if (!empty($leave['leave_type_id'])) {
        return 'Type #' . $leave['leave_type_id'];
    }
    return 'Not Specified';
}

$pageTitle = 'My Leaves';
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
        display: flex; align-items: center; justify-content: space-between;
        gap: 14px; margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
        border-left: 4px solid #696cff;
        flex-wrap: wrap;
    }
    .header-left { display: flex; align-items: center; gap: 14px; }

    .header-icon {
        width: 50px; height: 50px; border-radius: 12px;
        background: linear-gradient(135deg, #696cff, #5a5de0);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 26px;
        box-shadow: 0 4px 12px rgba(105,108,255,.3);
    }
    .header-text h4 { font-size: 20px; font-weight: 700; color: #1a2332; margin: 0 0 2px; }
    .header-text p { font-size: 12.5px; color: #6b7a8f; margin: 0; }

    .btn-apply-leave {
        background: linear-gradient(135deg, #696cff, #5a5de0);
        color: #fff; text-decoration: none;
        padding: 10px 20px; border-radius: 10px;
        font-size: 14px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px;
        transition: all .2s; border: none; cursor: pointer;
        font-family: inherit;
    }
    .btn-apply-leave:hover {
        background: linear-gradient(135deg, #5a5de0, #4a4dc9);
        transform: translateY(-1px);
        color: #fff;
        text-decoration: none;
    }

    /* ============================================================
       STATS CARDS
    ============================================================ */
    .stats-row {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 16px; margin-bottom: 24px;
    }
    @media (max-width: 992px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .stats-row { grid-template-columns: 1fr; } }

    .stat-card {
        background: #fff; border-radius: 12px; padding: 18px 22px;
        display: flex; align-items: center; gap: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
        border-left: 4px solid #696cff;
    }
    .stat-card.total    { border-left-color: #696cff; }
    .stat-card.pending  { border-left-color: #eab308; }
    .stat-card.approved { border-left-color: #22c55e; }
    .stat-card.rejected { border-left-color: #ef4444; }

    .stat-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
    }
    .stat-card.total .stat-icon    { background: #eef2ff; color: #4a4dc9; }
    .stat-card.pending .stat-icon  { background: #fef9c3; color: #713f12; }
    .stat-card.approved .stat-icon { background: #dcfce7; color: #166534; }
    .stat-card.rejected .stat-icon { background: #fee2e2; color: #991b1b; }

    .stat-info h3 { font-size: 22px; font-weight: 700; color: #1a2332; margin: 0; }
    .stat-info p  { font-size: 12px; color: #6b7a8f; margin: 2px 0 0; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }

    /* ============================================================
       LEAVE TABLE
    ============================================================ */
    .leave-table-card {
        background: #fff; border-radius: 12px; overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
    }
    .leave-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
    .leave-table thead th {
        background: #f8f9fc; color: #1a2332;
        font-weight: 700; font-size: 12px;
        text-transform: uppercase; letter-spacing: .4px;
        padding: 14px 16px; text-align: left;
        border-bottom: 2px solid #e9ecef; white-space: nowrap;
    }
    .leave-table tbody td {
        padding: 14px 16px; border-bottom: 1px solid #f1f3f5;
        color: #1a2332; vertical-align: middle;
    }
    .leave-table tbody tr:hover { background: #f8fafc; }
    .leave-table tbody tr:last-child td { border-bottom: none; }

    .leave-type-badge {
        display: inline-block; background: #eef2ff; color: #4a4dc9;
        padding: 4px 12px; border-radius: 20px;
        font-size: 12px; font-weight: 600;
    }
    .leave-type-badge.missing {
        background: #f1f3f5; color: #6b7280; font-style: italic;
    }

    .reason-cell { max-width: 260px; font-size: 13px; color: #4a5568; line-height: 1.5; }

    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 20px;
        font-size: 12px; font-weight: 600;
    }
    .status-badge.pending  { background: #fef9c3; color: #713f12; }
    .status-badge.approved { background: #dcfce7; color: #166534; }
    .status-badge.rejected { background: #fee2e2; color: #991b1b; }

    .empty-state {
        text-align: center; padding: 60px 20px; color: #6b7a8f;
    }
    .empty-state i { font-size: 60px; color: #cbd5e1; display: block; margin-bottom: 14px; }
    .empty-state p { font-size: 15px; margin: 0 0 18px; }
    .empty-state .btn-apply-leave { display: inline-flex; }

    /* ============================================================
       MOBILE
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

        .leave-table { font-size: 12px; }
        .leave-table thead { display: none; }
        .leave-table tbody tr {
            display: block; border-bottom: 1px solid #e9ecef; padding: 12px;
        }
        .leave-table tbody td { display: block; padding: 4px 0; border: none; }
        .leave-table tbody td::before {
            content: attr(data-label); font-weight: 700;
            color: #6b7a8f; font-size: 11px;
            text-transform: uppercase; display: block; margin-bottom: 2px;
        }
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
                                <i class="bx bx-list-ul"></i> My Leaves
                            </span>
                        </div>

                        <!-- Page Header -->
                        <div class="page-header-box">
                            <div class="header-left">
                                <div class="header-icon"><i class="bx bx-list-ul"></i></div>
                                <div class="header-text">
                                    <h4>My Leaves</h4>
                                    <p>Hello <strong><?= htmlspecialchars($displayName) ?></strong>, here are all your leave requests</p>
                                </div>
                            </div>
                            <a href="apply.php" class="btn-apply-leave">
                                <i class="bx bx-plus-circle"></i> Apply New Leave
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="stats-row">
                            <div class="stat-card total">
                                <div class="stat-icon"><i class="bx bx-calendar"></i></div>
                                <div class="stat-info">
                                    <h3><?= $totalCount ?></h3>
                                    <p>Total</p>
                                </div>
                            </div>
                            <div class="stat-card pending">
                                <div class="stat-icon"><i class="bx bx-time-five"></i></div>
                                <div class="stat-info">
                                    <h3><?= $pendingCount ?></h3>
                                    <p>Pending</p>
                                </div>
                            </div>
                            <div class="stat-card approved">
                                <div class="stat-icon"><i class="bx bx-check-circle"></i></div>
                                <div class="stat-info">
                                    <h3><?= $approvedCount ?></h3>
                                    <p>Approved</p>
                                </div>
                            </div>
                            <div class="stat-card rejected">
                                <div class="stat-icon"><i class="bx bx-x-circle"></i></div>
                                <div class="stat-info">
                                    <h3><?= $rejectedCount ?></h3>
                                    <p>Rejected</p>
                                </div>
                            </div>
                        </div>

                        <!-- My Leaves Table -->
                        <div class="leave-table-card">
                            <?php if (!empty($myLeaves)): ?>
                                <div style="overflow-x:auto;">
                                    <table class="leave-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Leave Type</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Days</th>
                                                <th>Reason</th>
                                                <th>Applied On</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $sr = 1;
                                            foreach ($myLeaves as $leave): 
                                                $leaveType = getLeaveTypeDisplay($leave);
                                                $isMissing = (strpos($leaveType, 'Type #') === 0 || $leaveType === 'Not Specified');

                                                $from = new DateTime($leave['start_date']);
                                                $to   = new DateTime($leave['end_date']);
                                                $days = $from->diff($to)->days + 1;
                                            ?>
                                            <tr>
                                                <td data-label="#"><?= $sr++ ?></td>
                                                <td data-label="Leave Type">
                                                    <span class="leave-type-badge <?= $isMissing ? 'missing' : '' ?>">
                                                        <?= htmlspecialchars($leaveType) ?>
                                                    </span>
                                                </td>
                                                <td data-label="From"><?= date('d M Y', strtotime($leave['start_date'])) ?></td>
                                                <td data-label="To"><?= date('d M Y', strtotime($leave['end_date'])) ?></td>
                                                <td data-label="Days"><strong><?= $days ?></strong></td>
                                                <td data-label="Reason" class="reason-cell">
                                                    <?= htmlspecialchars($leave['reason'] ?? '-') ?>
                                                </td>
                                                <td data-label="Applied On">
                                                    <?= date('d M Y', strtotime($leave['created_at'])) ?><br>
                                                    <small style="color:#6b7a8f;"><?= date('h:i A', strtotime($leave['created_at'])) ?></small>
                                                </td>
                                                <td data-label="Status">
                                                    <?php if ($leave['status'] === 'pending'): ?>
                                                        <span class="status-badge pending">
                                                            <i class="bx bx-time-five"></i> Pending
                                                        </span>
                                                    <?php elseif ($leave['status'] === 'approved'): ?>
                                                        <span class="status-badge approved">
                                                            <i class="bx bx-check-circle"></i> Approved
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="status-badge rejected">
                                                            <i class="bx bx-x-circle"></i> Rejected
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="bx bx-calendar-x"></i>
                                    <p>You haven't applied for any leave yet.</p>
                                    <a href="apply.php" class="btn-apply-leave">
                                        <i class="bx bx-plus-circle"></i> Apply for Leave
                                    </a>
                                </div>
                            <?php endif; ?>
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