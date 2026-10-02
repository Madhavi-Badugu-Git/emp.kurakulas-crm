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
// HANDLE FORM SUBMIT
// ============================================================
$errors = [];

if (isset($_POST['save_notification'])) {

    $heading = trim($_POST['heading'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($heading === '') $errors[] = "Heading is required!";
    if ($content === '') $errors[] = "Content is required!";

    // -------- File Upload --------
    $file_name = null;
    $file_type = null;

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

                $ext       = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $file_name = 'notif_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $file_type = ($mime === 'application/pdf') ? 'pdf' : 'image';

                if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $file_name)) {
                    $errors[]  = "Failed to upload file! Check folder permissions.";
                    $file_name = null;
                    $file_type = null;
                }
            }
        }
    }

    if (empty($errors)) {

        $stmt = $conn->prepare("
            INSERT INTO tbl_notification
                (heading, content, file_name, file_type, created_by)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssi",
            $heading, $content, $file_name, $file_type, $user_id
        );

        if ($stmt->execute()) {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    iziToast.success({
                        title: "Success",
                        message: "Notification created successfully!",
                        position: "topRight",
                        timeout: 2000
                    });
                    setTimeout(function() {
                        window.location.href = "add.php";
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

    } else {
        echo '<script>document.addEventListener("DOMContentLoaded", function(){';
        foreach ($errors as $e) {
            echo 'iziToast.error({title:"Validation", message:"'.addslashes($e).'", position:"topRight"});';
        }
        echo '});</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr"
      data-theme="theme-default" data-assets-path="../assets/"
      data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Notification - CRM</title>
    <?php include('../includes/header.php'); ?>
</head>

<style>
    /* ============================================ */
    /* LAYOUT                                       */
    /* ============================================ */
    .layout-container { display:flex; min-height:100vh; position:relative; }
    .layout-sidebar {
        position:fixed; top:0; left:0; height:100vh; overflow-y:auto;
        width:260px; background:#1a2332; color:#fff; z-index:1000;
    }
    .layout-page { flex:1; min-height:100vh; overflow-y:auto; }

    /* ============================================ */
    /* PAGE HEADER                                  */
    /* ============================================ */
    .breadcrumb-box {
        background:#fff; border:1px solid #e9ecef; border-radius:10px;
        padding:12px 18px; margin-bottom:22px;
        display:flex; align-items:center; gap:8px; flex-wrap:wrap;
        box-shadow:0 1px 3px rgba(0,0,0,.04);
    }
    .breadcrumb-box a {
        display:inline-flex; align-items:center; gap:5px;
        font-size:13px; font-weight:500; color:#696cff; text-decoration:none;
        transition:all .2s;
    }
    .breadcrumb-box a:hover { color:#4a4dcf; }
    .breadcrumb-box .separator { color:#cbd5e1; font-size:14px; }
    .breadcrumb-box .active {
        display:inline-flex; align-items:center; gap:5px;
        font-size:13px; font-weight:600; color:#1a2332;
    }

    .page-header {
        display:flex; flex-wrap:wrap; justify-content:space-between;
        align-items:center; margin-bottom:24px;
        background:#fff; padding:20px 24px; border-radius:12px;
        box-shadow:0 2px 8px rgba(0,0,0,.05);
        border-left:4px solid #696cff;
    }
    .page-header .header-left {
        display:flex; align-items:center; gap:14px;
    }
    .page-header .header-icon {
        width:50px; height:50px; border-radius:12px;
        background:linear-gradient(135deg,#696cff,#8b8eff);
        display:flex; align-items:center; justify-content:center;
        color:#fff; font-size:26px;
        box-shadow:0 4px 12px rgba(105,108,255,.3);
    }
    .page-header h4 {
        font-weight:700; color:#1a2332; font-size:20px;
        margin:0;
    }
    .page-header p {
        font-size:12px; color:#6b7a8f; margin:2px 0 0;
    }

    /* ============================================ */
    /* CARD                                         */
    /* ============================================ */
    .notif-card {
        border:none; box-shadow:0 4px 20px rgba(0,0,0,.06);
        margin-bottom:25px; border-radius:12px; overflow:hidden;
    }
    .notif-card .card-header {
        background:linear-gradient(135deg,#f8fafc,#fff);
        border-bottom:1px solid #e9ecef;
        padding:18px 26px;
    }
    .notif-card .card-header h5 {
        font-weight:600; color:#1a2332; font-size:16px; margin:0;
        display:flex; align-items:center; gap:8px;
    }
    .notif-card .card-header h5 i {
        color:#696cff; font-size:20px;
    }
    .notif-card .card-body { padding:26px; }

    /* ============================================ */
    /* INFO TEXT                                    */
    /* ============================================ */
    .info-text {
        font-size:13px; color:#4a5568; padding:14px 18px;
        background:linear-gradient(135deg,#eff6ff,#f0f4ff);
        border-radius:10px; border-left:4px solid #696cff;
        margin-bottom:22px; display:flex; align-items:flex-start; gap:10px;
        line-height:1.6;
    }
    .info-text i { color:#696cff; font-size:18px; flex-shrink:0; margin-top:1px; }

    /* ============================================ */
    /* FORM CONTROLS                                */
    /* ============================================ */
    .form-label {
        font-weight:600; font-size:13px; color:#344767;
        margin-bottom:6px; display:block;
    }
    .form-label .req { color:#dc3545; margin-left:2px; }

    .input-group-text {
        background:#f8fafc; border:1px solid #d2d6da;
        border-right:none; color:#696cff; font-size:15px;
        padding:0 14px; border-radius:8px 0 0 8px;
    }
    .form-control {
        border:1px solid #d2d6da; font-size:13px;
        padding:10px 14px; border-radius:8px;
        transition:all .2s; background:#fff;
        color:#344767;
    }
    .form-control:hover { border-color:#b8bfc9; }
    .form-control:focus {
        border-color:#696cff;
        box-shadow:0 0 0 3px rgba(105,108,255,.12);
        background:#fff;
    }
    .form-control::placeholder { color:#adb5bd; font-size:12.5px; }

    .input-group-merge .form-control {
        border-radius:0 8px 8px 0;
    }

    textarea.form-control {
        min-height:120px; resize:vertical;
        line-height:1.6;
    }

    .form-text {
        font-size:11.5px; color:#8794a5; margin-top:6px;
        display:flex; align-items:center; gap:5px;
    }
    .form-text i { font-size:14px; }

    /* ============================================ */
    /* FILE PREVIEW                                 */
    /* ============================================ */
    .file-preview {
        margin-top:10px; padding:12px 16px;
        background:linear-gradient(135deg,#f0fdf4,#ecfdf5);
        border:1px solid #a7f3d0; border-radius:8px;
        font-size:12.5px; color:#065f46;
        display:none; align-items:center; gap:8px;
    }
    .file-preview.show { display:flex; }
    .file-preview b { color:#047857; }

    /* ============================================ */
    /* BUTTONS                                      */
    /* ============================================ */
    .btn-primary {
        background:linear-gradient(135deg,#696cff,#5a5de0);
        border:none; padding:11px 30px;
        color:#fff; font-size:14px; font-weight:600;
        border-radius:8px; transition:all .25s;
        display:inline-flex; align-items:center; gap:6px;
        box-shadow:0 4px 12px rgba(105,108,255,.25);
    }
    .btn-primary:hover {
        background:linear-gradient(135deg,#5a5de0,#4a4dcf);
        color:#fff;
        transform:translateY(-2px);
        box-shadow:0 6px 18px rgba(105,108,255,.35);
    }

    .btn-secondary {
        background:#fff; border:1px solid #d2d6da;
        padding:11px 26px; color:#4a5568;
        font-size:14px; font-weight:600; border-radius:8px;
        transition:all .2s;
        display:inline-flex; align-items:center; gap:6px;
        text-decoration:none;
    }
    .btn-secondary:hover {
        background:#f8fafc; border-color:#b8bfc9;
        color:#1a2332; transform:translateY(-1px);
    }

    .btn-sm {
        padding:8px 18px; font-size:13px;
    }

    /* ============================================ */
    /* FORM BUTTON GROUP                            */
    /* ============================================ */
    .form-actions {
        margin-top:28px; padding-top:22px;
        border-top:1px solid #f1f3f5;
        display:flex; flex-wrap:wrap; gap:12px;
    }

    /* ============================================ */
    /* RESPONSIVE                                   */
    /* ============================================ */
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
        .page-header { padding:16px; }
        .page-header h4 { font-size:17px; }
        .page-header .header-icon { width:42px; height:42px; font-size:20px; }
        .notif-card .card-body { padding:18px; }
        .notif-card .card-header { padding:14px 18px; }
        .breadcrumb-box { padding:10px 14px; }
        .breadcrumb-box a, .breadcrumb-box .active { font-size:12px; }
        .form-actions { flex-direction:column; }
        .form-actions .btn-primary,
        .form-actions .btn-secondary { width:100%; justify-content:center; }
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
                            <i class="bx bx-plus-circle"></i> Add
                        </span>
                    </div>

                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="header-left">
                            <div class="header-icon">
                                <i class="bx bx-bell-plus"></i>
                            </div>
                            <div>
                                <h4>Create Notification</h4>
                                <p>Fill the details below to publish a new notification</p>
                            </div>
                        </div>
                        <a href="list.php" class="btn btn-secondary btn-sm">
                            <i class="bx bx-list-ul"></i> View All
                        </a>
                    </div>

                    <!-- FORM CARD -->
                    <div class="row">
                        <div class="col-xl">
                            <div class="card notif-card">
                                <div class="card-header">
                                    <h5><i class="bx bx-edit"></i> Notification Details</h5>
                                </div>
                                <div class="card-body">

                                    <div class="info-text">
                                        <i class="bx bx-info-circle"></i>
                                        <span>Fill the heading and content below. You can optionally attach a PDF or image file (maximum 5 MB).</span>
                                    </div>

                                    <form action="" method="POST" enctype="multipart/form-data"
                                          id="notifForm" novalidate>

                                        <div class="row g-3">

                                            <!-- Heading -->
                                            <div class="col-12">
                                                <label class="form-label" for="heading">
                                                    Heading <span class="req">*</span>
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-text"></i></span>
                                                    <input type="text" class="form-control" name="heading"
                                                           id="heading" placeholder="e.g. Diwali Festival Leave"
                                                           maxlength="255" required>
                                                </div>
                                            </div>

                                            <!-- Content -->
                                            <div class="col-12">
                                                <label class="form-label" for="content">
                                                    Content <span class="req">*</span>
                                                </label>
                                                <textarea class="form-control" name="content" id="content"
                                                          rows="5" placeholder="Enter notification details here..."
                                                          required></textarea>
                                            </div>

                                            <!-- File Upload -->
                                            <div class="col-12">
                                                <label class="form-label" for="file">
                                                    Upload Document
                                                    <small style="color:#8794a5;font-weight:400;">
                                                        (optional)
                                                    </small>
                                                </label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">
                                                        <i class="bx bx-upload"></i>
                                                    </span>
                                                    <input type="file" class="form-control" name="file"
                                                           id="file"
                                                           accept=".pdf,image/jpeg,image/png,image/jpg">
                                                </div>
                                                <div class="form-text">
                                                    <i class="bx bx-check-circle"></i>
                                                    Allowed: <b>.pdf, .jpg, .jpeg, .png</b> — max 5 MB
                                                </div>
                                                <div class="file-preview" id="filePreview"></div>
                                            </div>

                                        </div>

                                        <!-- Buttons -->
                                        <div class="form-actions">
                                            <button type="submit" name="save_notification"
                                                    class="btn btn-primary">
                                                <i class="bx bx-save"></i> Save & Notify
                                            </button>
                                            <button type="reset" class="btn btn-secondary">
                                                <i class="bx bx-reset"></i> Reset
                                            </button>
                                            <a href="list.php" class="btn btn-secondary">
                                                <i class="bx bx-x"></i> Cancel
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
        preview.innerHTML = `<i class="bx bx-paperclip"></i> <b>${file.name}</b> — ${sizeMB} MB`;
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