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
// GET NOTIFICATION ID
// ============================================================
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    echo '<script>alert("Invalid notification ID!"); window.location.href="list.php";</script>';
    exit();
}

// ============================================================
// FETCH EXISTING NOTIFICATION
// ============================================================
$stmt = $conn->prepare("SELECT * FROM tbl_notification WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$notification = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$notification) {
    echo '<script>alert("Notification not found!"); window.location.href="list.php";</script>';
    exit();
}

// ============================================================
// HANDLE FORM SUBMIT
// ============================================================
$errors = [];

if (isset($_POST['update_notification'])) {

    $heading = trim($_POST['heading'] ?? '');
    $content = trim($_POST['content'] ?? '');

    // -------- Validation --------
    if ($heading === '') $errors[] = "Heading is required!";
    if ($content === '') $errors[] = "Content is required!";

    // -------- File Handling --------
    $file_name = $notification['file_name']; // keep old by default
    $file_type = $notification['file_type'];

    // Check if user wants to remove existing file
    if (isset($_POST['remove_file']) && $_POST['remove_file'] == '1') {
        if (!empty($notification['file_name'])) {
            $oldPath = __DIR__ . '/../uploads/notifications/' . $notification['file_name'];
            if (file_exists($oldPath)) @unlink($oldPath);
        }
        $file_name = null;
        $file_type = null;
    }

    // Check if new file uploaded
    if (!empty($_FILES['file']['name'])) {

        $allowed = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 5 * 1024 * 1024;

        if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "File upload error (code: " . $_FILES['file']['error'] . ")";
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, $allowed)) {
                $errors[] = "Only PDF, JPG, JPEG, PNG files are allowed!";
            } elseif ($_FILES['file']['size'] > $maxSize) {
                $errors[] = "File size must be under 5 MB!";
            } else {
                $uploadDir = __DIR__ . '/../uploads/notifications/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Delete old file if exists
                if (!empty($notification['file_name'])) {
                    $oldPath = $uploadDir . $notification['file_name'];
                    if (file_exists($oldPath)) @unlink($oldPath);
                }

                $ext          = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $new_file     = 'notif_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $new_type     = ($mime === 'application/pdf') ? 'pdf' : 'image';

                if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $new_file)) {
                    $errors[] = "Failed to upload file! Check folder permissions.";
                } else {
                    $file_name = $new_file;
                    $file_type = $new_type;
                }
            }
        }
    }

    // -------- Update DB --------
    if (empty($errors)) {

        $stmt = $conn->prepare("
            UPDATE tbl_notification
            SET heading = ?, content = ?, file_name = ?, file_type = ?
            WHERE id = ?
        ");
        $stmt->bind_param(
            "ssssi",
            $heading, $content, $file_name, $file_type, $id
        );

        if ($stmt->execute()) {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    iziToast.success({
                        title: "Success",
                        message: "Notification updated successfully!",
                        position: "topRight",
                        timeout: 2000
                    });
                    setTimeout(function() {
                        window.location.href = "view.php?id=' . $id . '";
                    }, 1500);
                });
            </script>';
        } else {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    iziToast.error({
                        title: "DB Error",
                        message: "'.addslashes($stmt->error).'",
                        position: "topRight"
                    });
                });
            </script>';
        }
        $stmt->close();

        // Refresh notification data after update
        $stmt2 = $conn->prepare("SELECT * FROM tbl_notification WHERE id = ? LIMIT 1");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $notification = $stmt2->get_result()->fetch_assoc();
        $stmt2->close();

    } else {
        echo '<script>document.addEventListener("DOMContentLoaded", function(){';
        foreach ($errors as $e) {
            echo 'iziToast.error({title:"Validation", message:"'.addslashes($e).'", position:"topRight"});';
        }
        echo '});</script>';
    }
}

$fileUrl = !empty($notification['file_name'])
            ? '../uploads/notifications/' . htmlspecialchars($notification['file_name'])
            : null;
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr"
      data-theme="theme-default" data-assets-path="../assets/"
      data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Notification - CRM</title>
    <?php include('../includes/header.php'); ?>
</head>

<style>
    .layout-container { display:flex; min-height:100vh; position:relative; }
    .layout-sidebar {
        position:fixed; top:0; left:0; height:100vh; overflow-y:auto;
        width:260px; background:#1a2332; color:#fff; z-index:1000;
    }
    .layout-page { flex:1; min-height:100vh; overflow-y:auto; }

    .notif-card {
        border:none; box-shadow:0 2px 10px rgba(0,0,0,.08); margin-bottom:25px;
    }
    .card-header {
        background:#fff; border-bottom:1px solid #e9ecef; padding:15px 24px;
    }
    .card-header h5 {
        font-weight:600; color:#1a2332; font-size:16px; margin:0;
    }
    .form-label {
        font-weight:600; font-size:13px; color:#344767; margin-bottom:4px;
    }
    .form-control {
        border-color:#d2d6da; font-size:13px; border-radius:6px; min-height:38px;
    }
    .form-control:focus {
        border-color:#696cff; box-shadow:0 0 0 2px rgba(105,108,255,.15);
    }
    .input-group-text {
        background:#f8f9fa; border-color:#d2d6da; font-size:13px;
    }
    .form-text { font-size:11px; color:#6b7a8f; margin-top:2px; }

    .btn-primary {
        background:#696cff; border-color:#696cff; padding:8px 28px;
        color:#fff; font-size:14px;
    }
    .btn-primary:hover {
        background:#5a5de0; border-color:#5a5de0; color:#fff;
        transform:translateY(-1px);
        box-shadow:0 4px 12px rgba(105,108,255,.3);
    }
    .btn-secondary {
        background:#6c757d; border-color:#6c757d; padding:8px 28px;
        color:#fff; font-size:14px;
    }
    .btn-secondary:hover { background:#5a6268; border-color:#5a6268; color:#fff; }

    .info-text {
        font-size:13px; color:#6b7a8f; padding:10px 16px;
        background:#f8fafc; border-radius:8px;
        border-left:4px solid #696cff; margin-bottom:15px;
    }
    .info-text i { color:#696cff; }

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

    .file-preview {
        margin-top:8px; padding:8px 12px; background:#f8fafc;
        border-radius:6px; border:1px dashed #d2d6da; font-size:12px;
        color:#6b7a8f; display:none;
    }
    .file-preview.show { display:block; }

    .current-file-box {
        background:#e8f0fe; border:1px solid #696cff; border-radius:8px;
        padding:12px 16px; margin-bottom:10px;
        display:flex; justify-content:space-between;
        align-items:center; flex-wrap:wrap; gap:10px;
    }
    .current-file-box .info {
        display:flex; align-items:center; gap:8px;
        font-size:13px; color:#344767; font-weight:500;
    }
    .current-file-box .info i { color:#696cff; font-size:18px; }

    .remove-check {
        display:flex; align-items:center; gap:6px;
        font-size:12px; color:#b91c1c; font-weight:600;
        cursor:pointer;
    }
    .remove-check input { cursor:pointer; }

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
        .card-header { padding:12px 16px; }
        .breadcrumb-box { padding:6px 10px; }
        .breadcrumb-box a, .breadcrumb-box .active { font-size:12px; }
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
                            <i class="bx bx-edit"></i> Edit
                        </span>
                    </div>

                    <!-- Header -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold py-2 mb-0 page-title">
                            <i class="bx bx-edit me-2"></i>Edit Notification
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="view.php?id=<?= $id ?>" class="btn btn-secondary btn-sm">
                                <i class="bx bx-show me-1"></i> View
                            </a>
                            <a href="list.php" class="btn btn-secondary btn-sm">
                                <i class="bx bx-list-ul me-1"></i> Back
                            </a>
                        </div>
                    </div>

                    <!-- FORM CARD -->
                    <div class="row">
                        <div class="col-xl">
                            <div class="card notif-card">
                                <div class="card-header">
                                    <h5><i class="bx bx-edit me-2"></i>Notification Details</h5>
                                </div>
                                <div class="card-body">

                                    <div class="info-text">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Update the heading, content, or replace the attachment.
                                        Leaving the file field empty keeps the current file.
                                    </div>

                                    <form action="" method="POST" enctype="multipart/form-data"
                                          id="notifForm" novalidate>

                                        <div class="row g-2">

                                            <!-- Heading -->
                                            <div class="col-12">
                                                <label class="form-label" for="heading">
                                                    Heading <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-text"></i></span>
                                                    <input type="text" class="form-control" name="heading"
                                                           id="heading"
                                                           value="<?= htmlspecialchars($notification['heading']) ?>"
                                                           maxlength="255" required>
                                                </div>
                                            </div>

                                            <!-- Content -->
                                            <div class="col-12">
                                                <label class="form-label" for="content">
                                                    Content <span class="text-danger">*</span>
                                                </label>
                                                <textarea class="form-control" name="content" id="content"
                                                          rows="5" required><?= htmlspecialchars($notification['content']) ?></textarea>
                                            </div>

                                            <!-- Current File / File Upload -->
                                            <div class="col-12">
                                                <label class="form-label">Attachment</label>

                                                <?php if (!empty($notification['file_name'])): ?>
                                                    <div class="current-file-box">
                                                        <div class="info">
                                                            <?php if ($notification['file_type'] === 'pdf'): ?>
                                                                <i class="bx bxs-file-pdf"></i>
                                                            <?php else: ?>
                                                                <i class="bx bx-image"></i>
                                                            <?php endif; ?>
                                                            <span>
                                                                <?= htmlspecialchars($notification['file_name']) ?>
                                                            </span>
                                                            <a href="<?= $fileUrl ?>" target="_blank"
                                                               style="font-size:12px;color:#696cff;font-weight:600;text-decoration:none;">
                                                                <i class="bx bx-link-external"></i> Open
                                                            </a>
                                                        </div>
                                                        <label class="remove-check">
                                                            <input type="checkbox" name="remove_file" value="1">
                                                            Remove this file
                                                        </label>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class="bx bx-upload"></i>
                                                    </span>
                                                    <input type="file" class="form-control" name="file"
                                                           id="file"
                                                           accept=".pdf,image/jpeg,image/png,image/jpg">
                                                </div>
                                                <div class="form-text">
                                                    <?= !empty($notification['file_name'])
                                                        ? 'Upload a new file to replace the current one.'
                                                        : 'Allowed: <b>.pdf, .jpg, .jpeg, .png</b>' ?>
                                                </div>
                                                <div class="file-preview" id="filePreview"></div>
                                            </div>

                                        </div>

                                        <!-- Buttons -->
                                        <div class="mt-3">
                                            <button type="submit" name="update_notification"
                                                    class="btn btn-primary">
                                                <i class="bx bx-save me-1"></i> Update Notification
                                            </button>
                                            <a href="view.php?id=<?= $id ?>" class="btn btn-secondary ms-2">
                                                <i class="bx bx-x me-1"></i> Cancel
                                            </a>
                                        </div>

                                    </form>

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

    document.getElementById('file').addEventListener('change', function () {
        const preview = document.getElementById('filePreview');
        const file    = this.files[0];

        if (!file) {
            preview.classList.remove('show');
            preview.innerHTML = '';
            return;
        }

        const sizeMB = (file.size / 1024 / 1024).toFixed(2);
        preview.innerHTML = `📎 <b>${file.name}</b> — ${sizeMB} MB`;
        preview.classList.add('show');

        if (file.size > 5 * 1024 * 1024) {
            iziToast.error({
                title: "Too Large",
                message: "File must be under 5 MB",
                position: "topRight"
            });
            this.value = '';
            preview.classList.remove('show');
        }
    });

    document.getElementById('notifForm').addEventListener('submit', function (e) {
        const heading = document.getElementById('heading').value.trim();
        const content = document.getElementById('content').value.trim();

        if (!heading) {
            e.preventDefault();
            iziToast.error({ title:"Error", message:"Heading is required", position:"topRight" });
            document.getElementById('heading').focus();
            return false;
        }

        if (!content) {
            e.preventDefault();
            iziToast.error({ title:"Error", message:"Content is required", position:"topRight" });
            document.getElementById('content').focus();
            return false;
        }
    });
</script>
</body>
</html>