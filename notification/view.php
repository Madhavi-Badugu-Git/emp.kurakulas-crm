<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================================================
// SAFE USER ID
// ============================================================
$user_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 1;

// ============================================================
// GET ID
// ============================================================
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    echo '<script>alert("Invalid notification ID!"); window.location.href="list.php";</script>';
    exit();
}

// ============================================================
// FETCH NOTIFICATION (u.email removed)
// ============================================================
$stmt = $conn->prepare("
    SELECT n.*, u.username, u.firstName, u.lastName
    FROM tbl_notification n
    LEFT JOIN tbl_user u ON n.created_by = u.id
    WHERE n.id = ?
    LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$notification = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$notification) {
    echo '<script>alert("Notification not found!"); window.location.href="list.php";</script>';
    exit();
}

// Creator name
$creator = trim(($notification['firstName'] ?? '') . ' ' . ($notification['lastName'] ?? ''));
if (empty($creator)) $creator = $notification['username'] ?? 'N/A';

$fileUrl  = !empty($notification['file_name'])
            ? '../uploads/notifications/' . htmlspecialchars($notification['file_name'])
            : null;
$fileType = $notification['file_type'] ?? null;
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr"
      data-theme="theme-default" data-assets-path="../assets/"
      data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Notification - CRM</title>
    <?php include('../includes/header.php'); ?>
</head>

<style>
    .layout-container { display:flex; min-height:100vh; position:relative; }
    .layout-sidebar {
        position:fixed; top:0; left:0; height:100vh; overflow-y:auto;
        width:260px; background:#1a2332; color:#fff; z-index:1000;
    }
    .layout-page { flex:1; min-height:100vh; overflow-y:auto; }

    .view-card {
        border:none; box-shadow:0 2px 10px rgba(0,0,0,.08); margin-bottom:25px;
    }
    .card-header {
        background:#fff; border-bottom:1px solid #e9ecef; padding:15px 24px;
    }
    .card-header h5 {
        font-weight:600; color:#1a2332; font-size:16px; margin:0;
    }

    .btn-primary {
        background:#696cff; border-color:#696cff; padding:8px 22px;
        color:#fff; font-size:14px;
    }
    .btn-primary:hover {
        background:#5a5de0; border-color:#5a5de0; color:#fff;
        transform:translateY(-1px);
        box-shadow:0 4px 12px rgba(105,108,255,.3);
    }
    .btn-secondary {
        background:#6c757d; border-color:#6c757d; padding:8px 22px;
        color:#fff; font-size:14px;
    }
    .btn-secondary:hover { background:#5a6268; border-color:#5a6268; color:#fff; }

    .btn-warning {
        background:#ffc107; border-color:#ffc107; padding:8px 22px;
        color:#1a2332; font-size:14px;
    }
    .btn-warning:hover { background:#e0a800; border-color:#e0a800; color:#1a2332; }

    .breadcrumb-box {
        background:#f8f9fa; border:1px solid #e9ecef; border-radius:8px;
        padding:10px 16px; margin-bottom:18px;
        display:flex; align-items:center; gap:6px; flex-wrap:wrap;
    }
    .breadcrumb-box a {
        display:inline-flex; align-items:center; gap:5px;
        font-size:14px; font-weight:500; color:#198754; text-decoration:none;
    }
    .breadcrumb-box a:hover { color:#0f5132; text-decoration:underline; }
    .breadcrumb-box .separator { color:#9ca3af; font-size:16px; font-weight:600; }
    .breadcrumb-box .active {
        display:inline-flex; align-items:center; gap:5px;
        font-size:14px; font-weight:600; color:#1a2332;
    }

    .page-title { font-size:20px; font-weight:600; }

    .notif-hero {
        background: linear-gradient(135deg, #696cff 0%, #8b8eff 100%);
        color:#fff; padding:28px 30px; border-radius:10px;
        margin-bottom:25px; box-shadow:0 4px 15px rgba(105,108,255,.25);
    }
    .notif-hero .icon {
        font-size:44px; opacity:.9; margin-bottom:10px;
    }
    .notif-hero h2 {
        font-size:24px; font-weight:700; margin:0 0 8px;
        color:#fff; word-break:break-word;
    }
    .notif-hero .meta {
        font-size:13px; opacity:.9;
        display:flex; flex-wrap:wrap; gap:16px;
    }
    .notif-hero .meta span {
        display:inline-flex; align-items:center; gap:5px;
    }

    .info-grid {
        display:grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap:15px; margin-bottom:25px;
    }
    .info-box {
        background:#f8fafc; border:1px solid #e9ecef;
        border-radius:8px; padding:15px 18px;
    }
    .info-box .label {
        font-size:11px; font-weight:600; color:#6b7a8f;
        text-transform:uppercase; letter-spacing:.5px;
        margin-bottom:4px;
    }
    .info-box .value {
        font-size:14px; font-weight:600; color:#1a2332;
        display:flex; align-items:center; gap:6px;
    }
    .info-box .value i { color:#696cff; font-size:16px; }

    .content-box {
        background:#fafbfc; border:1px solid #e9ecef;
        border-radius:8px; padding:18px 20px;
        font-size:14px; color:#344767;
        line-height:1.7; white-space:pre-wrap;
        word-break:break-word;
    }

    .file-preview-box {
        margin-top:20px; border:1px solid #e9ecef;
        border-radius:8px; overflow:hidden; background:#fff;
    }
    .file-preview-header {
        background:#f8fafc; padding:10px 16px;
        border-bottom:1px solid #e9ecef;
        font-size:13px; font-weight:600; color:#344767;
        display:flex; justify-content:space-between;
        align-items:center; flex-wrap:wrap; gap:8px;
    }
    .file-preview-body {
        padding:20px; text-align:center;
        background:#fafbfc;
    }
    .file-preview-body img {
        max-width:100%; max-height:500px;
        border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,.1);
    }
    .file-preview-body iframe {
        width:100%; height:600px;
        border:none; border-radius:6px;
    }

    .btn-download {
        display:inline-flex; align-items:center; gap:5px;
        background:#696cff; color:#fff; padding:5px 12px;
        border-radius:4px; font-size:12px;
        font-weight:600; text-decoration:none;
    }
    .btn-download:hover { background:#5a5de0; color:#fff; }

    .no-file-box {
        text-align:center; padding:30px;
        color:#adb5bd; font-size:13px;
    }
    .no-file-box i {
        font-size:36px; color:#d1d5db;
        display:block; margin-bottom:8px;
    }

    .layout-sidebar-overlay {
        display:none; position:fixed; top:0; left:0;
        width:100%; height:100%; background:rgba(0,0,0,.5); z-index:9998;
    }
    .layout-sidebar-overlay.active { display:block; }

    @media (max-width: 768px) {
        .layout-sidebar {
            position:fixed; left:-280px; width:280px;
            transition:left .3s ease; z-index:9999;
        }
        .layout-sidebar.open { left:0; }
        .page-title { font-size:17px; }
        .notif-hero { padding:20px; }
        .notif-hero h2 { font-size:18px; }
        .notif-hero .icon { font-size:34px; }
        .file-preview-body iframe { height:400px; }
    }
</style>

<body>
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        <!-- SIDEBAR -->
        <div class="layout-sidebar" id="sidebar">
            <?php include('../includes/sideMenu.php'); ?>
        </div>
        <div class="layout-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- PAGE -->
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
                        <a href="list.php">
                            <i class="bx bx-bell"></i> Notifications
                        </a>
                        <span class="separator">›</span>
                        <span class="active">
                            <i class="bx bx-show"></i> View
                        </span>
                    </div>

                    <!-- Header -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold py-2 mb-0 page-title">
                            <i class="bx bx-show me-2"></i>Notification Details
                        </h4>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="list.php" class="btn btn-secondary btn-sm">
                                <i class="bx bx-arrow-back me-1"></i> Back
                            </a>
                        </div>
                    </div>

                    <!-- HERO HEADER -->
                    <div class="notif-hero">
                        <div class="icon">
                            <i class="bx bx-bell"></i>
                        </div>
                        <h2><?= htmlspecialchars($notification['heading']) ?></h2>
                        <div class="meta">
                            <span>
                                <i class="bx bx-user"></i>
                                <?= htmlspecialchars($creator) ?>
                            </span>
                            <span>
                                <i class="bx bx-time"></i>
                                Created: <?= date('d M Y, h:i A', strtotime($notification['created_at'])) ?>
                            </span>
                        </div>
                    </div>

                    <!-- INFO GRID -->
                    <div class="info-grid">
                        <div class="info-box">
                            <div class="label">Attachment</div>
                            <div class="value">
                                <i class="bx bx-paperclip"></i>
                                <?= !empty($notification['file_name']) ? 'Yes' : 'None' ?>
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="label">Notification ID</div>
                            <div class="value">
                                <i class="bx bx-hash"></i>
                                #<?= $notification['id'] ?>
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="label">Created By</div>
                            <div class="value">
                                <i class="bx bx-user"></i>
                                <?= htmlspecialchars($creator) ?>
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="label">Created At</div>
                            <div class="value">
                                <i class="bx bx-calendar"></i>
                                <?= date('d M Y', strtotime($notification['created_at'])) ?>
                            </div>
                        </div>
                    </div>

                    <!-- CONTENT -->
                    <div class="row">
                        <div class="col-xl">
                            <div class="card view-card">
                                <div class="card-header">
                                    <h5><i class="bx bx-text me-2"></i>Content</h5>
                                </div>
                                <div class="card-body">
                                    <div class="content-box">
                                        <?= nl2br(htmlspecialchars($notification['content'])) ?>
                                    </div>

                                    <!-- FILE PREVIEW -->
                                    <?php if ($fileUrl): ?>
                                        <div class="file-preview-box">
                                            <div class="file-preview-header">
                                                <span>
                                                    <i class="bx bx-paperclip me-1"></i>
                                                    Attachment — <?= htmlspecialchars($notification['file_name']) ?>
                                                </span>
                                                <a class="btn-download"
                                                   href="<?= $fileUrl ?>" download>
                                                    <i class="bx bx-download"></i> Download
                                                </a>
                                            </div>
                                            <div class="file-preview-body">
                                                <?php if ($fileType === 'pdf'): ?>
                                                    <iframe src="<?= $fileUrl ?>#toolbar=1&navpanes=0" title="PDF"></iframe>
                                                <?php else: ?>
                                                    <img src="<?= $fileUrl ?>" alt="attachment">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="file-preview-box">
                                            <div class="no-file-box">
                                                <i class="bx bx-file-blank"></i>
                                                No attachment for this notification
                                            </div>
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
</div>

<?php include('../includes/script.php'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }
</script>
</body>
</html>