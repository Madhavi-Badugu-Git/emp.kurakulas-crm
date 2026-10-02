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

// $loggedInUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

// if ($loggedInUserId <= 0) {
//     $_SESSION['toast_error'] = "Session expired. Please login again.";
//     header('Location: ../login.php');
//     exit();
// }

// ============================================================
// FETCH LOGGED-IN USER with department & designation names
// ============================================================
$loggedUser = [];
$luSql = "SELECT 
            u.id,
            u.rank,
            u.firstName,
            u.lastName,
            u.username,
            u.is_teamlead,
            u.reportingTo,
            u.department_id,
            u.designation_id,
            dept.department_name,
            d.designation_name
          FROM tbl_user u
          LEFT JOIN tbl_department dept ON u.department_id = dept.id
          LEFT JOIN tbl_designation d   ON u.designation_id = d.id
          WHERE u.id = '$loggedInUserId'
          LIMIT 1";
$luRes = mysqli_query($conn, $luSql);
if ($luRes && mysqli_num_rows($luRes) > 0) {
    $loggedUser = mysqli_fetch_assoc($luRes);
}

$loggedRank = $loggedUser['rank'] ?? 'User';

// Role flags
$isSuperAdmin = ($loggedRank === 'superAdmin');
$isAdmin      = ($loggedRank === 'Admin');
$isTeamLead   = isset($loggedUser['is_teamlead']) && (int)$loggedUser['is_teamlead'] === 1;

// HR detection — from DB
$isHR = false;
$deptLower  = strtolower($loggedUser['department_name'] ?? '');
$desigLower = strtolower($loggedUser['designation_name'] ?? '');
if (strpos($deptLower, 'hr') !== false || strpos($deptLower, 'human resource') !== false) {
    $isHR = true;
}
if (strpos($desigLower, 'hr') !== false || strpos($desigLower, 'human resource') !== false) {
    $isHR = true;
}

$isAdminOrSuper = $isSuperAdmin || $isAdmin;

// ============================================================
// HANDLE APPROVE / REJECT ACTION
// ============================================================
if (isset($_POST['leave_action'])) {
    $leaveAction = $_POST['leave_action'];
    $leaveId     = isset($_POST['leave_id']) ? (int)$_POST['leave_id'] : 0;
    $approvedBy  = $loggedInUserId;

    if ($leaveId > 0) {
        $canAct = false;

        if ($isAdminOrSuper) {
            $canAct = true;
        } elseif ($isHR || $isTeamLead) {
            // HR can act on TLs reporting to HR + their team's leaves
            // TL can act on employees reporting to them
            $checkSql = "SELECT lr.id
                         FROM tbl_leave_requests lr
                         JOIN tbl_user u ON lr.employee_id = u.id
                         WHERE lr.id = '$leaveId'
                           AND (
                               u.reportingTo = '$loggedInUserId'
                               OR u.reportingTo IN (
                                   SELECT id FROM tbl_user WHERE reportingTo = '$loggedInUserId'
                               )
                           )
                         LIMIT 1";
            $checkRes = mysqli_query($conn, $checkSql);
            if ($checkRes && mysqli_num_rows($checkRes) > 0) {
                $canAct = true;
            }
        }

        if (!$canAct) {
            $_SESSION['toast_error'] = "You don't have permission to act on this leave request.";
            header("Location: approve.php");
            exit;
        }

        $newStatus = ($leaveAction === 'approve') ? 'approved' : 'rejected';

        $colCheck1 = mysqli_query($conn, "SHOW COLUMNS FROM tbl_leave_requests LIKE 'approved_by'");
        $hasApprovedBy = ($colCheck1 && mysqli_num_rows($colCheck1) > 0);

        $colCheck2 = mysqli_query($conn, "SHOW COLUMNS FROM tbl_leave_requests LIKE 'updated_at'");
        $hasUpdatedAt = ($colCheck2 && mysqli_num_rows($colCheck2) > 0);

        if ($hasApprovedBy && $hasUpdatedAt) {
            $updateSql = "UPDATE tbl_leave_requests 
                          SET status = '$newStatus', approved_by = '$approvedBy', updated_at = NOW() 
                          WHERE id = '$leaveId'";
        } elseif ($hasApprovedBy) {
            $updateSql = "UPDATE tbl_leave_requests 
                          SET status = '$newStatus', approved_by = '$approvedBy' 
                          WHERE id = '$leaveId'";
        } else {
            $updateSql = "UPDATE tbl_leave_requests 
                          SET status = '$newStatus' 
                          WHERE id = '$leaveId'";
        }

        if (mysqli_query($conn, $updateSql)) {
            $_SESSION['toast_success'] = "Leave request " . ucfirst($newStatus) . " successfully!";
        } else {
            $_SESSION['toast_error'] = "Failed to update: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['toast_error'] = "Invalid leave request ID.";
    }

    header("Location: approve.php");
    exit;
}

// ============================================================
// DETECT LEAVE TABLE STRUCTURE
// ============================================================
$pendingLeaves  = [];
$approvedLeaves = [];
$rejectedLeaves = [];

$hasLeaveType = false;
$ltCheck = mysqli_query($conn, "SHOW TABLES LIKE 'tbl_leave_type'");
if ($ltCheck && mysqli_num_rows($ltCheck) > 0) {
    $hasLeaveType = true;
}

$colCheckLT = mysqli_query($conn, "SHOW COLUMNS FROM tbl_leave_requests LIKE 'leave_type_id'");
$hasLeaveTypeId = ($colCheckLT && mysqli_num_rows($colCheckLT) > 0);

$colCheckLTName = mysqli_query($conn, "SHOW COLUMNS FROM tbl_leave_requests LIKE 'leave_type'");
$hasDirectLeaveType = ($colCheckLTName && mysqli_num_rows($colCheckLTName) > 0);

$extraSelect = "";
if ($hasLeaveType && $hasLeaveTypeId) {
    $extraSelect .= ", lt.leave_type AS leave_type_joined";
}
if ($hasDirectLeaveType) {
    $extraSelect .= ", lr.leave_type AS leave_type_direct";
}

$baseQuery = "SELECT lr.*, 
                     u.firstName, 
                     u.lastName, 
                     u.username,
                     u.employee_no,
                     u.reportingTo
                     $extraSelect
              FROM tbl_leave_requests lr
              LEFT JOIN tbl_user u ON lr.employee_id = u.id";

if ($hasLeaveType && $hasLeaveTypeId) {
    $baseQuery .= " LEFT JOIN tbl_leave_type lt ON lr.leave_type_id = lt.id";
}

// ============================================================
// HIERARCHY SCOPE:
// superAdmin/Admin : ALL
// HR               : TLs reporting to HR + their team's employees
// TL               : employees reporting to TL
// Employee         : own leaves only
// ============================================================
if ($isAdminOrSuper) {
    $whereScope = "1=1";
} elseif ($isHR) {
    $whereScope = "(
        u.reportingTo = '$loggedInUserId'
        OR u.reportingTo IN (
            SELECT id FROM tbl_user WHERE reportingTo = '$loggedInUserId'
        )
    )";
} elseif ($isTeamLead) {
    $whereScope = "u.reportingTo = '$loggedInUserId'";
} else {
    $whereScope = "u.id = '$loggedInUserId'";
}

// PENDING
$pendingResult = mysqli_query($conn, $baseQuery . " WHERE lr.status = 'pending' AND ($whereScope) ORDER BY lr.created_at DESC");
if ($pendingResult) {
    while ($row = mysqli_fetch_assoc($pendingResult)) {
        $pendingLeaves[] = $row;
    }
}

// APPROVED
$approvedResult = mysqli_query($conn, $baseQuery . " WHERE lr.status = 'approved' AND ($whereScope) ORDER BY lr.id DESC LIMIT 20");
if ($approvedResult) {
    while ($row = mysqli_fetch_assoc($approvedResult)) {
        $approvedLeaves[] = $row;
    }
}

// REJECTED
$rejectedResult = mysqli_query($conn, $baseQuery . " WHERE lr.status = 'rejected' AND ($whereScope) ORDER BY lr.id DESC LIMIT 20");
if ($rejectedResult) {
    while ($row = mysqli_fetch_assoc($rejectedResult)) {
        $rejectedLeaves[] = $row;
    }
}

$pendingCount  = count($pendingLeaves);
$approvedCount = count($approvedLeaves);
$rejectedCount = count($rejectedLeaves);

// ============================================================
// HELPERS
// ============================================================
function getEmployeeName($leave) {
    $name = trim(($leave['firstName'] ?? '') . ' ' . ($leave['lastName'] ?? ''));
    return $name !== '' ? $name : ($leave['username'] ?? 'Unknown');
}

function getEmployeeId($leave) {
    if (!empty($leave['employee_no'])) return $leave['employee_no'];
    if (!empty($leave['username']))    return $leave['username'];
    return 'N/A';
}

function getLeaveTypeName($leave) {
    if (!empty($leave['leave_type_joined'])) return $leave['leave_type_joined'];
    if (!empty($leave['leave_type_direct'])) return $leave['leave_type_direct'];
    if (array_key_exists('leave_type_id', $leave)) {
        $ltid = $leave['leave_type_id'];
        if ($ltid === null || $ltid === '' || $ltid == 0) return 'Not Specified';
        return 'Type #' . $ltid;
    }
    return 'Leave';
}

function isLeaveTypeMissing($typeName) {
    return in_array($typeName, ['Not Specified', 'Leave']) || strpos($typeName, 'Type #') === 0;
}

// Scope label
if ($isAdminOrSuper) {
    $scopeLabel = 'You are viewing ALL leave requests (Admin access).';
} elseif ($isHR) {
    $scopeLabel = 'You are viewing leave requests from your Team Leads and their team members.';
} elseif ($isTeamLead) {
    $scopeLabel = 'You are viewing leave requests from your team members.';
} else {
    $scopeLabel = 'You are viewing your own leave requests.';
}

$pageTitle = 'Leave Approvals';
include('../includes/header.php');
?>

<style>
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
    .breadcrumb-box .breadcrumb-item.active { color: #1a2332; font-weight: 600; pointer-events: none; }
    .breadcrumb-box .breadcrumb-item.active i { color: #1a2332; }
    .breadcrumb-box .separator { color: #9ca3af; font-size: 16px; margin: 0 4px; font-weight: 600; }

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

    .scope-banner {
        background: #eef2ff; border: 1px solid #c7d2fe;
        color: #3730a3; border-radius: 10px;
        padding: 10px 16px; margin-bottom: 18px;
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 500;
    }
    .scope-banner i { font-size: 18px; }

    .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
    @media (max-width: 768px) { .stats-row { grid-template-columns: 1fr; } }

    .stat-card {
        background: #fff; border-radius: 12px; padding: 18px 22px;
        display: flex; align-items: center; gap: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
        border-left: 4px solid #696cff;
    }
    .stat-card.pending  { border-left-color: #eab308; }
    .stat-card.approved { border-left-color: #22c55e; }
    .stat-card.rejected { border-left-color: #ef4444; }

    .stat-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
    }
    .stat-card.pending .stat-icon  { background: #fef9c3; color: #713f12; }
    .stat-card.approved .stat-icon { background: #dcfce7; color: #166534; }
    .stat-card.rejected .stat-icon { background: #fee2e2; color: #991b1b; }

    .stat-info h3 { font-size: 22px; font-weight: 700; color: #1a2332; margin: 0; }
    .stat-info p  { font-size: 12px; color: #6b7a8f; margin: 2px 0 0; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }

    .tabs-nav { display: flex; gap: 8px; margin-bottom: 18px; flex-wrap: wrap; }

    .tab-btn {
        padding: 10px 20px; border-radius: 8px;
        background: #f0f2f5; color: #65676b;
        font-size: 13.5px; font-weight: 600; border: none;
        cursor: pointer; transition: all .2s;
        display: inline-flex; align-items: center; gap: 8px;
        font-family: inherit;
    }
    .tab-btn:hover { background: #e4e6eb; }
    .tab-btn.active { background: #696cff; color: #fff; }
    .tab-btn .badge-count {
        background: rgba(255,255,255,.3);
        padding: 1px 8px; border-radius: 10px; font-size: 11px;
    }
    .tab-btn.active .badge-count { background: rgba(255,255,255,.35); }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

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

    .emp-name { font-weight: 700; color: #1a2332; font-size: 14px; }
    .emp-id   { font-size: 12px; color: #6b7a8f; margin-top: 2px; }
    .emp-id strong { color: #4a5568; }

    .leave-type-badge {
        display: inline-block; background: #eef2ff; color: #4a4dc9;
        padding: 4px 12px; border-radius: 20px;
        font-size: 12px; font-weight: 600;
    }
    .leave-type-badge.missing {
        background: #f1f3f5; color: #6b7280;
        font-style: italic;
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

    .action-cell { display: flex; gap: 6px; flex-wrap: wrap; }

    .btn-approve-sm {
        background: #22c55e; color: #fff; border: none;
        padding: 7px 14px; border-radius: 6px;
        font-size: 12px; font-weight: 600;
        cursor: pointer; display: inline-flex;
        align-items: center; gap: 5px;
        transition: all .2s; font-family: inherit;
    }
    .btn-approve-sm:hover { background: #16a34a; transform: translateY(-1px); }

    .btn-reject-sm {
        background: #ef4444; color: #fff; border: none;
        padding: 7px 14px; border-radius: 6px;
        font-size: 12px; font-weight: 600;
        cursor: pointer; display: inline-flex;
        align-items: center; gap: 5px;
        transition: all .2s; font-family: inherit;
    }
    .btn-reject-sm:hover { background: #dc2626; transform: translateY(-1px); }

    .empty-state { text-align: center; padding: 50px 20px; color: #6b7a8f; }
    .empty-state i { font-size: 50px; color: #cbd5e1; display: block; margin-bottom: 12px; }
    .empty-state p { font-size: 14px; margin: 0; }

    .modal-content { border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,.15); }
    .modal-header { border-bottom: 1px solid #e9ecef; padding: 16px 22px; }
    .modal-title { font-weight: 700; color: #1a2332; font-size: 16px; }
    .modal-body { padding: 22px; font-size: 14px; color: #4a5568; }
    .modal-footer { border-top: 1px solid #e9ecef; padding: 14px 22px; }

    .btn-modal-approve {
        background: #22c55e; color: #fff; border: none;
        padding: 9px 22px; border-radius: 8px; font-weight: 600; font-size: 14px;
        cursor: pointer; transition: all .2s; font-family: inherit;
    }
    .btn-modal-approve:hover { background: #16a34a; }

    .btn-modal-reject {
        background: #ef4444; color: #fff; border: none;
        padding: 9px 22px; border-radius: 8px; font-weight: 600; font-size: 14px;
        cursor: pointer; transition: all .2s; font-family: inherit;
    }
    .btn-modal-reject:hover { background: #dc2626; }

    .btn-modal-cancel {
        background: #f0f2f5; color: #65676b; border: none;
        padding: 9px 22px; border-radius: 8px; font-weight: 600; font-size: 14px;
        cursor: pointer; transition: all .2s; font-family: inherit;
    }
    .btn-modal-cancel:hover { background: #e4e6eb; }

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

                        <div class="breadcrumb-box">
                            <a href="../dashboard/user" class="breadcrumb-item">
                                <i class="bx bx-home"></i> Dashboard
                            </a>
                            <span class="separator">›</span>
                            <a href="javascript:void(0);" class="breadcrumb-item">
                                <i class="bx bx-calendar-check"></i> Leave Management
                            </a>
                            <span class="separator">›</span>
                            <span class="breadcrumb-item active">
                                <i class="bx bx-check-shield"></i> Leave Approvals
                            </span>
                        </div>

                        <div class="page-header-box">
                            <div class="header-icon"><i class="bx bx-check-shield"></i></div>
                            <div class="header-text">
                                <h4>Leave Approvals</h4>
                                <p>Review and manage employee leave requests</p>
                            </div>
                        </div>

                        <div class="scope-banner">
                            <i class="bx bx-info-circle"></i>
                            <?= htmlspecialchars($scopeLabel) ?>
                        </div>

                        <div class="stats-row">
                            <div class="stat-card pending">
                                <div class="stat-icon"><i class="bx bx-time-five"></i></div>
                                <div class="stat-info"><h3><?= $pendingCount ?></h3><p>Pending</p></div>
                            </div>
                            <div class="stat-card approved">
                                <div class="stat-icon"><i class="bx bx-check-circle"></i></div>
                                <div class="stat-info"><h3><?= $approvedCount ?></h3><p>Approved</p></div>
                            </div>
                            <div class="stat-card rejected">
                                <div class="stat-icon"><i class="bx bx-x-circle"></i></div>
                                <div class="stat-info"><h3><?= $rejectedCount ?></h3><p>Rejected</p></div>
                            </div>
                        </div>

                        <div class="tabs-nav">
                            <button type="button" class="tab-btn active" onclick="switchTab('pending', this)">
                                <i class="bx bx-time-five"></i> Pending
                                <span class="badge-count"><?= $pendingCount ?></span>
                            </button>
                            <button type="button" class="tab-btn" onclick="switchTab('approved', this)">
                                <i class="bx bx-check-circle"></i> Approved
                                <span class="badge-count"><?= $approvedCount ?></span>
                            </button>
                            <button type="button" class="tab-btn" onclick="switchTab('rejected', this)">
                                <i class="bx bx-x-circle"></i> Rejected
                                <span class="badge-count"><?= $rejectedCount ?></span>
                            </button>
                        </div>

                        <!-- PENDING -->
                        <div class="tab-content active" id="tab-pending">
                            <div class="leave-table-card">
                                <?php if (!empty($pendingLeaves)): ?>
                                    <div style="overflow-x:auto;">
                                        <table class="leave-table">
                                            <thead>
                                                <tr>
                                                    <th>Employee</th>
                                                    <th>Leave Type</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Days</th>
                                                    <th>Reason</th>
                                                    <th>Applied</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($pendingLeaves as $leave):
                                                    $empName   = getEmployeeName($leave);
                                                    $empId     = getEmployeeId($leave);
                                                    $leaveType = getLeaveTypeName($leave);
                                                    $isMissing = isLeaveTypeMissing($leaveType);
                                                    $from = new DateTime($leave['start_date']);
                                                    $to   = new DateTime($leave['end_date']);
                                                    $days = $from->diff($to)->days + 1;
                                                ?>
                                                <tr>
                                                    <td data-label="Employee">
                                                        <div class="emp-name"><?= htmlspecialchars($empName) ?></div>
                                                        <div class="emp-id">ID: <strong><?= htmlspecialchars($empId) ?></strong></div>
                                                    </td>
                                                    <td data-label="Leave Type">
                                                        <span class="leave-type-badge <?= $isMissing ? 'missing' : '' ?>"><?= htmlspecialchars($leaveType) ?></span>
                                                    </td>
                                                    <td data-label="From"><?= date('d M Y', strtotime($leave['start_date'])) ?></td>
                                                    <td data-label="To"><?= date('d M Y', strtotime($leave['end_date'])) ?></td>
                                                    <td data-label="Days"><strong><?= $days ?></strong></td>
                                                    <td data-label="Reason" class="reason-cell"><?= htmlspecialchars($leave['reason'] ?? '-') ?></td>
                                                    <td data-label="Applied">
                                                        <?= date('d M Y', strtotime($leave['created_at'])) ?><br>
                                                        <small style="color:#6b7a8f;"><?= date('h:i A', strtotime($leave['created_at'])) ?></small>
                                                    </td>
                                                    <td data-label="Action">
                                                        <div class="action-cell">
                                                            <button type="button" class="btn-approve-sm" onclick="confirmAction(<?= (int)$leave['id'] ?>, 'approve', '<?= htmlspecialchars(addslashes($empName)) ?>')">
                                                                <i class="bx bx-check"></i> Approve
                                                            </button>
                                                            <button type="button" class="btn-reject-sm" onclick="confirmAction(<?= (int)$leave['id'] ?>, 'reject', '<?= htmlspecialchars(addslashes($empName)) ?>')">
                                                                <i class="bx bx-x"></i> Reject
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="bx bx-check-double"></i>
                                        <p>No pending leave requests. All caught up!</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- APPROVED -->
                        <div class="tab-content" id="tab-approved">
                            <div class="leave-table-card">
                                <?php if (!empty($approvedLeaves)): ?>
                                    <div style="overflow-x:auto;">
                                        <table class="leave-table">
                                            <thead>
                                                <tr>
                                                    <th>Employee</th><th>Leave Type</th><th>From</th>
                                                    <th>To</th><th>Days</th><th>Reason</th><th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($approvedLeaves as $leave):
                                                    $empName   = getEmployeeName($leave);
                                                    $empId     = getEmployeeId($leave);
                                                    $leaveType = getLeaveTypeName($leave);
                                                    $isMissing = isLeaveTypeMissing($leaveType);
                                                    $from = new DateTime($leave['start_date']);
                                                    $to   = new DateTime($leave['end_date']);
                                                    $days = $from->diff($to)->days + 1;
                                                ?>
                                                <tr>
                                                    <td data-label="Employee">
                                                        <div class="emp-name"><?= htmlspecialchars($empName) ?></div>
                                                        <div class="emp-id">ID: <strong><?= htmlspecialchars($empId) ?></strong></div>
                                                    </td>
                                                    <td data-label="Leave Type">
                                                        <span class="leave-type-badge <?= $isMissing ? 'missing' : '' ?>"><?= htmlspecialchars($leaveType) ?></span>
                                                    </td>
                                                    <td data-label="From"><?= date('d M Y', strtotime($leave['start_date'])) ?></td>
                                                    <td data-label="To"><?= date('d M Y', strtotime($leave['end_date'])) ?></td>
                                                    <td data-label="Days"><strong><?= $days ?></strong></td>
                                                    <td data-label="Reason" class="reason-cell"><?= htmlspecialchars($leave['reason'] ?? '-') ?></td>
                                                    <td data-label="Status">
                                                        <span class="status-badge approved"><i class="bx bx-check-circle"></i> Approved</span>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="bx bx-history"></i>
                                        <p>No approved leave records yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- REJECTED -->
                        <div class="tab-content" id="tab-rejected">
                            <div class="leave-table-card">
                                <?php if (!empty($rejectedLeaves)): ?>
                                    <div style="overflow-x:auto;">
                                        <table class="leave-table">
                                            <thead>
                                                <tr>
                                                    <th>Employee</th><th>Leave Type</th><th>From</th>
                                                    <th>To</th><th>Days</th><th>Reason</th><th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($rejectedLeaves as $leave):
                                                    $empName   = getEmployeeName($leave);
                                                    $empId     = getEmployeeId($leave);
                                                    $leaveType = getLeaveTypeName($leave);
                                                    $isMissing = isLeaveTypeMissing($leaveType);
                                                    $from = new DateTime($leave['start_date']);
                                                    $to   = new DateTime($leave['end_date']);
                                                    $days = $from->diff($to)->days + 1;
                                                ?>
                                                <tr>
                                                    <td data-label="Employee">
                                                        <div class="emp-name"><?= htmlspecialchars($empName) ?></div>
                                                        <div class="emp-id">ID: <strong><?= htmlspecialchars($empId) ?></strong></div>
                                                    </td>
                                                    <td data-label="Leave Type">
                                                        <span class="leave-type-badge <?= $isMissing ? 'missing' : '' ?>"><?= htmlspecialchars($leaveType) ?></span>
                                                    </td>
                                                    <td data-label="From"><?= date('d M Y', strtotime($leave['start_date'])) ?></td>
                                                    <td data-label="To"><?= date('d M Y', strtotime($leave['end_date'])) ?></td>
                                                    <td data-label="Days"><strong><?= $days ?></strong></td>
                                                    <td data-label="Reason" class="reason-cell"><?= htmlspecialchars($leave['reason'] ?? '-') ?></td>
                                                    <td data-label="Status">
                                                        <span class="status-badge rejected"><i class="bx bx-x-circle"></i> Rejected</span>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="bx bx-history"></i>
                                        <p>No rejected leave records yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>

                    <?php include('../includes/footer.php'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalTitle">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmModalMessage">Are you sure?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="" id="confirmActionForm" style="display:inline;">
                        <input type="hidden" name="leave_action" id="confirmActionInput" value="">
                        <input type="hidden" name="leave_id" id="confirmLeaveIdInput" value="">
                        <button type="submit" class="btn-modal-approve" id="confirmSubmitBtn">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/script.php'); ?>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('active');
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

        function switchTab(tabName, btn) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + tabName).classList.add('active');
            btn.classList.add('active');
        }

        function confirmAction(leaveId, action, empName) {
            document.getElementById('confirmLeaveIdInput').value = leaveId;
            document.getElementById('confirmActionInput').value = action;

            const title   = document.getElementById('confirmModalTitle');
            const message = document.getElementById('confirmModalMessage');
            const btn     = document.getElementById('confirmSubmitBtn');

            if (action === 'approve') {
                title.textContent = 'Approve Leave Request';
                message.innerHTML = 'Are you sure you want to <strong>approve</strong> the leave request for <strong>' + empName + '</strong>?';
                btn.className = 'btn-modal-approve';
                btn.textContent = 'Approve';
            } else {
                title.textContent = 'Reject Leave Request';
                message.innerHTML = 'Are you sure you want to <strong>reject</strong> the leave request for <strong>' + empName + '</strong>?';
                btn.className = 'btn-modal-reject';
                btn.textContent = 'Reject';
            }

            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            modal.show();
        }

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