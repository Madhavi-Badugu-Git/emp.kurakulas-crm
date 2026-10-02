<?php
session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 1;

// ============================================================
// FETCH — LATEST FIRST
// ============================================================
$notifications = [];
$sql = "SELECT n.* FROM tbl_notification n
        ORDER BY n.created_at DESC, n.id DESC";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $notifications[] = $row;
    }
}

$total = count($notifications);
$latestId = $total > 0 ? (int) $notifications[0]['id'] : 0;

// ===== HELPER: TIME AGO =====
if (!function_exists('timeAgo')) {
    function timeAgo($datetime) {
        if (empty($datetime)) return 'Just now';
        $timestamp = strtotime($datetime);
        if (!$timestamp) return 'Just now';
        $diff = time() - $timestamp;

        if ($diff < 60)      return 'Just now';
        if ($diff < 3600)    return floor($diff / 60) . 'm ago';
        if ($diff < 86400)   return floor($diff / 3600) . 'h ago';
        if ($diff < 604800)  return floor($diff / 86400) . 'd ago';
        if ($diff < 2592000) return floor($diff / 604800) . 'w ago';
        return date('M d, Y', $timestamp);
    }
}

// ============================================================
// AJAX HANDLER — LIKE / UNLIKE
// ============================================================
if (isset($_POST['action']) && $_POST['action'] === 'like') {
    header('Content-Type: application/json');
    $notif_id = (int)($_POST['notification_id'] ?? 0);
    $delta    = (int)($_POST['delta'] ?? 1);   // +1 = like, -1 = unlike

    if ($notif_id <= 0) {
        echo json_encode(['ok' => false]); exit;
    }

    // Update count safely (never goes below 0)
    $sql = "UPDATE tbl_notification
            SET likes_count = GREATEST(0, likes_count + ?)
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $delta, $notif_id);
    $stmt->execute();

    // Return fresh count
    $r = $conn->prepare("SELECT likes_count FROM tbl_notification WHERE id = ?");
    $r->bind_param('i', $notif_id);
    $r->execute();
    $newCount = (int)($r->get_result()->fetch_assoc()['likes_count'] ?? 0);

    echo json_encode(['ok' => true, 'count' => $newCount]);
    exit;
}

// ============================================================
// AJAX HANDLER — ADD COMMENT
// ============================================================
if (isset($_POST['action']) && $_POST['action'] === 'comment') {
    header('Content-Type: application/json');
    $notif_id = (int)($_POST['notification_id'] ?? 0);
    $text     = trim($_POST['comment_text'] ?? '');

    if ($notif_id <= 0 || $text === '') {
        echo json_encode(['ok' => false, 'msg' => 'Invalid input']); exit;
    }

    // Bump comment count
    $sql = "UPDATE tbl_notification
            SET comments_count = comments_count + 1
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $notif_id);
    $stmt->execute();

    // Return fresh count
    $r = $conn->prepare("SELECT comments_count FROM tbl_notification WHERE id = ?");
    $r->bind_param('i', $notif_id);
    $r->execute();
    $newCount = (int)($r->get_result()->fetch_assoc()['comments_count'] ?? 0);

    echo json_encode([
        'ok'    => true,
        'count' => $newCount,
        'text'  => htmlspecialchars($text),
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr"
      data-theme="theme-default" data-assets-path="../assets/"
      data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Feed - CRM</title>
    <?php include('../includes/header.php'); ?>
</head>

<style>
    /* ============================================================
       LAYOUT
       ============================================================ */
    .layout-page {
        margin-left: 260px !important;
        width: calc(100% - 260px) !important;
    }
    @media (max-width: 992px) {
        .layout-page { margin-left: 0 !important; width: 100% !important; }
    }
    .container-xxl.container-p-y {
        padding-left: 20px !important;
        padding-right: 20px !important;
        max-width: 100% !important;
    }

    /* BREADCRUMB */
    .breadcrumb-box {
        background:#fff; border:1px solid #e9ecef; border-radius:10px;
        padding:12px 18px; margin-bottom:22px;
        display:flex; align-items:center; gap:8px; flex-wrap:wrap;
        box-shadow:0 1px 3px rgba(0,0,0,.04);
    }
    .breadcrumb-box a {
        display:inline-flex; align-items:center; gap:5px;
        font-size:13px; font-weight:500; color:#1877f2; text-decoration:none;
    }
    .breadcrumb-box a:hover { color:#0a5dc2; }
    .breadcrumb-box .separator { color:#cbd5e1; font-size:14px; }
    .breadcrumb-box .active {
        display:inline-flex; align-items:center; gap:5px;
        font-size:13px; font-weight:600; color:#1a2332;
    }

    /* PAGE HEADER */
    .page-header {
        display:flex; flex-wrap:wrap; justify-content:space-between;
        align-items:center; margin-bottom:24px;
        background:#fff; padding:20px 24px; border-radius:12px;
        box-shadow:0 2px 8px rgba(0,0,0,.05);
        border-left:4px solid #1877f2;
    }
    .page-header .header-left { display:flex; align-items:center; gap:14px; }
    .page-header .header-icon {
        width:50px; height:50px; border-radius:12px;
        background:linear-gradient(135deg,#1877f2,#0a5dc2);
        display:flex; align-items:center; justify-content:center;
        color:#fff; font-size:26px;
        box-shadow:0 4px 12px rgba(24,119,242,.3);
    }
    .page-header h4 { font-weight:700; color:#1a2332; font-size:20px; margin:0; }
    .page-header p { font-size:12px; color:#6b7a8f; margin:2px 0 0; }

    .badge-count {
        background:linear-gradient(135deg,#1877f2,#0a5dc2); color:#fff;
        padding:8px 16px; border-radius:20px;
        font-size:12px; font-weight:600;
        display:inline-flex; align-items:center; gap:6px;
    }
    .btn-primary {
        background:linear-gradient(135deg,#1877f2,#0a5dc2);
        border:none; padding:10px 22px;
        color:#fff; font-size:13px; font-weight:600;
        border-radius:8px; transition:all .25s;
        display:inline-flex; align-items:center; gap:6px;
        text-decoration:none;
    }
    .btn-primary:hover {
        background:linear-gradient(135deg,#0a5dc2,#084a9e);
        color:#fff; transform:translateY(-1px);
    }

    /* ============================================================
       SINGLE COLUMN FEED
       ============================================================ */
    .column-wrap {
        display: flex;
        justify-content: center;
        padding: 10px 0 60px;
        width: 100%;
    }

    .column-feed {
        width: 100%;
        max-width: 420px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* ============================================================
       SINGLE CARD
       ============================================================ */
    .col-card {
        position: relative;
        background: #ffffff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.1);
        border: 1px solid #eef1f5;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .col-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(0, 0, 0, 0.14);
        border-color: #c7d2fe;
    }
    .col-card:active { transform: translateY(-1px); }

    .col-card.is-latest {
        border: 2px solid #1877f2;
        box-shadow: 0 6px 22px rgba(24, 119, 242, 0.22);
    }
    .col-card.is-latest::after {
        content: 'NEW';
        position: absolute;
        top: 12px; left: 12px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.6px;
        padding: 4px 10px;
        border-radius: 12px;
        z-index: 6;
        box-shadow: 0 3px 10px rgba(239, 68, 68, 0.5);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50%      { transform: scale(1.08); }
    }

    /* MEDIA */
    .col-media {
        position: relative;
        width: 100%;
        aspect-ratio: 9 / 13;
        background: #0a0a0a;
        overflow: hidden;
    }
    .col-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }
    .col-card:hover .col-media img { transform: scale(1.03); }

    .col-media-ph {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        color: #fff;
    }
    .col-media-ph.pdf     { background: linear-gradient(135deg, #7f1d1d, #b91c1c); }
    .col-media-ph.no-file { background: linear-gradient(135deg, #0a2540, #1877f2); }
    .col-media-ph i       { font-size: 72px; }
    .col-media-ph span {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
    }

    /* Dark gradient overlay */
    .col-media-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to top,
            rgba(0,0,0,0.85) 0%,
            rgba(0,0,0,0.5) 25%,
            rgba(0,0,0,0.05) 55%,
            rgba(0,0,0,0) 75%
        );
        pointer-events: none;
        z-index: 2;
    }

    /* BOTTOM-LEFT INFO */
    .col-info {
        position: absolute;
        left: 14px;
        right: 76px;
        bottom: 14px;
        z-index: 4;
        color: #fff;
    }
    .col-author {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 8px;
    }
    .col-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1877f2, #0a5dc2);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 16px; flex-shrink: 0;
        border: 2px solid #fff;
    }
    .col-avatar.latest-avatar {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    .col-author-name {
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        display: block;
        line-height: 1.2;
        text-shadow: 0 1px 3px rgba(0,0,0,0.6);
    }
    .col-author-time {
        font-size: 11px;
        color: rgba(255,255,255,0.85);
        display: flex;
        align-items: center;
        gap: 3px;
        margin-top: 1px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.6);
    }
    .col-heading {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 4px;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-shadow: 0 1px 4px rgba(0,0,0,0.6);
    }
    .col-content {
        font-size: 12.5px;
        color: rgba(255,255,255,0.92);
        margin: 0;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-shadow: 0 1px 4px rgba(0,0,0,0.6);
    }

    /* RIGHT SIDE ACTION RAIL */
    .col-side-actions {
        position: absolute;
        right: 10px;
        bottom: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        z-index: 5;
    }

    .col-action-circle-btn {
        background: transparent;
        border: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        font-family: inherit;
        padding: 0;
    }

    .col-action-circle {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 22px;
        transition: all 0.2s ease;
        border: 1px solid rgba(255,255,255,0.18);
    }

    .col-action-circle-btn:hover .col-action-circle {
        background: rgba(0, 0, 0, 0.75);
        transform: scale(1.08);
    }
    .col-action-circle-btn:active .col-action-circle {
        transform: scale(0.95);
    }

    .col-action-circle-btn.liked .col-action-circle {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-color: #ef4444;
        animation: likePop 0.35s ease;
    }
    @keyframes likePop {
        0%   { transform: scale(1); }
        50%  { transform: scale(1.3); }
        100% { transform: scale(1); }
    }

    .col-action-count {
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,0.7);
    }

    /* STATS BAR */
    .col-stats {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        font-size: 12.5px;
        color: #65676b;
        background: #fff;
        border-top: 1px solid #e4e6eb;
    }
    .col-stats-left {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .col-reaction-icon {
        width: 18px; height: 18px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 10px;
        border: 2px solid #fff;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,.08);
        max-width: 400px;
        margin: 0 auto;
    }
    .empty-state .empty-icon {
        width: 100px; height: 100px;
        margin: 0 auto 22px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e7f3ff, #d4e8fb);
        display: flex; align-items: center; justify-content: center;
        color: #1877f2; font-size: 48px;
    }
    .empty-state h5 { font-size: 18px; font-weight: 700; color: #050505; margin-bottom: 8px; }
    .empty-state p { font-size: 13px; color: #65676b; margin: 0 0 22px; }

    /* FULLSCREEN VIEWER */
    .viewer-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.92);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        animation: vFadeIn 0.25s ease;
        padding: 40px 20px;
    }
    .viewer-overlay.active { display: flex; }

    @keyframes vFadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    .viewer-box {
        position: relative;
        max-width: 480px;
        width: 100%;
        max-height: 92vh;
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 30px 80px rgba(0,0,0,0.5);
        animation: vPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes vPop {
        from { opacity: 0; transform: scale(0.9) translateY(20px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .viewer-close {
        position: absolute;
        top: 12px; right: 12px;
        width: 40px; height: 40px;
        border-radius: 50%;
        background: rgba(0,0,0,0.5);
        color: #fff;
        border: none;
        font-size: 24px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        z-index: 10;
        transition: all 0.2s ease;
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }
    .viewer-close:hover {
        background: rgba(239, 68, 68, 0.9);
        transform: rotate(90deg);
    }

    .viewer-scroll {
        flex: 1 1 auto;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .viewer-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px 8px;
    }
    .viewer-body {
        padding: 4px 16px 16px;
    }
    .viewer-body h5 {
        font-size: 18px;
        font-weight: 700;
        color: #050505;
        margin: 0 0 8px;
        line-height: 1.35;
    }
    .viewer-body p {
        font-size: 14.5px;
        color: #050505;
        margin: 0;
        line-height: 1.55;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .viewer-media {
        width: 100%;
        background: #f0f2f5;
        overflow: hidden;
        position: relative;
    }
    .viewer-media img {
        width: 100%;
        height: auto;
        display: block;
    }
    .viewer-media-ph {
        width: 100%;
        min-height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }
    .viewer-media-ph.pdf     { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #b91c1c; }
    .viewer-media-ph.no-file { background: linear-gradient(135deg, #e7f3ff, #d4e8fb); color: #1877f2; }
    .viewer-media-ph i       { font-size: 72px; }
    .viewer-media-ph span {
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        opacity: 0.9;
    }

    .viewer-stats {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 16px;
        font-size: 13.5px;
        color: #65676b;
        border-top: 1px solid #e4e6eb;
    }
    .viewer-actions {
        display: flex;
        align-items: center;
        border-top: 1px solid #e4e6eb;
        padding: 4px 12px;
        background: #fff;
    }
    .viewer-action-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        color: #65676b;
        background: transparent;
        border: none;
        cursor: pointer;
        font-family: inherit;
        transition: background 0.15s ease;
    }
    .viewer-action-btn:hover { background: #f0f2f5; }
    .viewer-action-btn i { font-size: 20px; }
    .viewer-action-btn.liked {
        color: #1877f2;
        font-weight: 700;
    }

    /* Comments section inside viewer */
    .viewer-comment-input-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px 16px;
        border-top: 1px solid #e4e6eb;
    }
    .viewer-comment-input {
        flex: 1;
        background: #f0f2f5;
        border: none;
        border-radius: 20px;
        padding: 10px 14px;
        font-size: 13px;
        color: #050505;
        outline: none;
        font-family: inherit;
    }
    .viewer-comment-input:focus { background: #e4e6eb; }
    .viewer-comment-send {
        background: #1877f2;
        border: none;
        color: #fff;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: background 0.15s ease;
    }
    .viewer-comment-send:hover { background: #0a5dc2; }

    /* SIDEBAR OVERLAY */
    .layout-sidebar-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,.5);
        z-index: 9998;
    }
    .layout-sidebar-overlay.active { display: block; }

    body.viewer-open { overflow: hidden; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .column-feed { max-width: 100%; }
        .col-heading { font-size: 14px; }
        .col-content { font-size: 12px; }
        .col-action-circle { width: 42px; height: 42px; font-size: 20px; }
        .col-action-count { font-size: 11px; }
        .viewer-overlay { padding: 20px 12px; }
        .viewer-body h5 { font-size: 16px; }
        .viewer-body p { font-size: 13.5px; }
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
                        <span class="active">
                            <i class="bx bx-bell"></i> Notifications
                        </span>
                    </div>

                    <!-- Page Header -->
                    <div class="page-header">
                        <div class="header-left">
                            <div class="header-icon">
                                <i class="bx bx-bell"></i>
                            </div>
                            <div>
                                <h4>Notification Feed</h4>
                                <p>Click any card to open it in full view</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge-count">
                                <i class="bx bx-list-check"></i><?= $total ?> Total
                            </span>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- SINGLE COLUMN FEED                            -->
                    <!-- ============================================ -->
                    <?php if ($total > 0): ?>

                        <div class="column-wrap">
                            <div class="column-feed">
                                <?php $i = 0; foreach ($notifications as $n): $i++; ?>
                                    <?php
                                        $isLatest      = ($n['id'] == $latestId);
                                        $nid           = (int) $n['id'];
                                        $likeTotal     = (int) ($n['likes_count']    ?? 0);
                                        $commentTotal  = (int) ($n['comments_count'] ?? 0);
                                    ?>

                                    <div class="col-card <?= $isLatest ? 'is-latest' : '' ?>"
                                         id="col-card-<?= $nid ?>"
                                         onclick="openViewer(<?= $nid ?>)">

                                        <!-- MEDIA -->
                                        <div class="col-media">
                                            <?php if (!empty($n['file_name']) && $n['file_type'] === 'image'): ?>
                                                <img src="../uploads/notifications/<?= htmlspecialchars($n['file_name']) ?>"
                                                     alt="<?= htmlspecialchars($n['heading']) ?>"
                                                     loading="lazy">
                                            <?php elseif (!empty($n['file_name']) && $n['file_type'] === 'pdf'): ?>
                                                <div class="col-media-ph pdf">
                                                    <i class="bx bxs-file-pdf"></i>
                                                    <span>PDF Document</span>
                                                </div>
                                            <?php else: ?>
                                                <div class="col-media-ph no-file">
                                                    <i class="bx bx-image-alt"></i>
                                                    <span>No Image</span>
                                                </div>
                                            <?php endif; ?>

                                            <div class="col-media-overlay"></div>

                                            <!-- INFO -->
                                            <div class="col-info">
                                                <div class="col-author">
                                                    <div class="col-avatar <?= $isLatest ? 'latest-avatar' : '' ?>">
                                                        <i class="bx bx-bell"></i>
                                                    </div>
                                                    <div>
                                                        <span class="col-author-name">Notification</span>
                                                        <span class="col-author-time">
                                                            <i class="bx bx-time-five"></i>
                                                            <?= htmlspecialchars(timeAgo($n['created_at'] ?? '')) ?>
                                                            · <i class="bx bx-globe"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <h5 class="col-heading"><?= htmlspecialchars($n['heading']) ?></h5>
                                                <p class="col-content"><?= htmlspecialchars($n['content']) ?></p>
                                            </div>

                                            <!-- RIGHT ACTION RAIL -->
                                            <div class="col-side-actions">
                                                <button class="col-action-circle-btn"
                                                        id="like-btn-<?= $nid ?>"
                                                        onclick="toggleLike(<?= $nid ?>, this); event.stopPropagation();"
                                                        aria-label="Like">
                                                    <span class="col-action-circle">
                                                        <i class="bx bx-heart"></i>
                                                    </span>
                                                    <span class="col-action-count"
                                                          id="side-like-count-<?= $nid ?>"><?= $likeTotal ?></span>
                                                </button>

                                                <button class="col-action-circle-btn"
                                                        onclick="event.stopPropagation(); openViewer(<?= $nid ?>);"
                                                        aria-label="Comment">
                                                    <span class="col-action-circle">
                                                        <i class="bx bx-comment"></i>
                                                    </span>
                                                    <span class="col-action-count"
                                                          id="side-comment-count-<?= $nid ?>"><?= $commentTotal ?></span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- STATS -->
                                        <div class="col-stats">
                                            <div class="col-stats-left">
                                                <span class="col-reaction-icon">
                                                    <i class="bx bxs-like"></i>
                                                </span>
                                                <span id="like-count-<?= $nid ?>"><?= $likeTotal ?></span>
                                            </div>
                                            <div>
                                                <span id="comment-count-<?= $nid ?>"><?= $commentTotal ?></span> Comments
                                            </div>
                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bx bx-bell-off"></i>
                            </div>
                            <h5>No Notifications Yet</h5>
                            <p>Create your first notification to get started</p>
                            <a href="add.php" class="btn-primary">
                                <i class="bx bx-plus"></i> Create First Notification
                            </a>
                        </div>

                    <?php endif; ?>

                </div>

                <?php include('../includes/footer.php'); ?>
            </div>
        </div>

    </div>
</div>

<!-- ============================================ -->
<!-- FULLSCREEN VIEWER                              -->
<!-- ============================================ -->
<div class="viewer-overlay" id="viewerOverlay" onclick="closeViewerOnOverlay(event)">
    <div class="viewer-box">
        <button class="viewer-close" onclick="closeViewer()" aria-label="Close">
            <i class="bx bx-x"></i>
        </button>

        <div class="viewer-scroll">
            <div class="viewer-header">
                <div class="col-avatar" id="viewerAvatar">
                    <i class="bx bx-bell"></i>
                </div>
                <div>
                    <span class="col-author-name">Notification</span>
                    <span class="col-author-time" id="viewerTime">
                        <i class="bx bx-time-five"></i> Just now · <i class="bx bx-globe"></i>
                    </span>
                </div>
            </div>

            <div class="viewer-body">
                <h5 id="viewerHeading">Heading</h5>
                <p id="viewerContent">Content</p>
            </div>

            <div class="viewer-media" id="viewerMedia"></div>

            <div class="viewer-stats">
                <div class="col-stats-left">
                    <span class="col-reaction-icon">
                        <i class="bx bxs-like"></i>
                    </span>
                    <span id="viewerLikeCount">0</span>
                </div>
                <div>
                    <span id="viewerCommentCount">0</span> Comments
                </div>
            </div>

            <div class="viewer-actions">
                <button class="viewer-action-btn" id="viewerLikeBtn" onclick="toggleViewerLike()">
                    <i class="bx bx-heart"></i>
                    <span>Like</span>
                </button>
                <button class="viewer-action-btn" id="viewerCommentBtn"
                        onclick="document.getElementById('viewerCommentInput').focus()">
                    <i class="bx bx-comment"></i>
                    <span>Comment</span>
                </button>
            </div>

            <!-- Comment input inside viewer -->
            <div class="viewer-comment-input-wrap">
                <input type="text"
                       class="viewer-comment-input"
                       id="viewerCommentInput"
                       placeholder="Write a comment..."
                       onkeydown="if(event.key==='Enter'){ submitViewerComment(); }">
                <button class="viewer-comment-send" onclick="submitViewerComment()">
                    <i class="bx bx-send"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/script.php'); ?>

<script>
    // ========================================================
    //  SIDEBAR TOGGLE
    // ========================================================
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }

    // ========================================================
    //  OPEN VIEWER
    // ========================================================
    var currentViewerId = null;
    var viewerLiked = false;

    function openViewer(id) {
        var card = document.getElementById('col-card-' + id);
        if (!card) return;

        currentViewerId = id;
        viewerLiked = false;

        var heading = card.querySelector('.col-heading') ? card.querySelector('.col-heading').textContent : '';
        var content = card.querySelector('.col-content') ? card.querySelector('.col-content').textContent : '';
        var timeTxt = card.querySelector('.col-author-time') ? card.querySelector('.col-author-time').textContent.trim() : 'Just now';

        document.getElementById('viewerHeading').textContent = heading;
        document.getElementById('viewerContent').textContent = content;
        document.getElementById('viewerTime').innerHTML = '<i class="bx bx-time-five"></i> ' + timeTxt;

        var cardMedia = card.querySelector('.col-media');
        var viewerMedia = document.getElementById('viewerMedia');
        if (cardMedia) {
            var img = cardMedia.querySelector('img');
            if (img) {
                viewerMedia.innerHTML = '<img src="' + img.src + '" alt="">';
            } else if (cardMedia.querySelector('.col-media-ph.pdf')) {
                viewerMedia.innerHTML = '<div class="viewer-media-ph pdf"><i class="bx bxs-file-pdf"></i><span>PDF Document</span></div>';
            } else {
                viewerMedia.innerHTML = '<div class="viewer-media-ph no-file"><i class="bx bx-image-alt"></i><span>No Image</span></div>';
            }
        }

        var likeCount = document.getElementById('like-count-' + id);
        document.getElementById('viewerLikeCount').textContent = likeCount ? likeCount.textContent : '0';

        var cmtCount = document.getElementById('comment-count-' + id);
        document.getElementById('viewerCommentCount').textContent = cmtCount ? cmtCount.textContent : '0';

        var vLikeBtn = document.getElementById('viewerLikeBtn');
        vLikeBtn.classList.remove('liked');
        vLikeBtn.querySelector('i').className = 'bx bx-heart';

        document.getElementById('viewerOverlay').classList.add('active');
        document.body.classList.add('viewer-open');
    }

    // ========================================================
    //  CLOSE VIEWER
    // ========================================================
    function closeViewer() {
        document.getElementById('viewerOverlay').classList.remove('active');
        document.body.classList.remove('viewer-open');
        currentViewerId = null;
    }

    function closeViewerOnOverlay(event) {
        if (event.target.id === 'viewerOverlay') closeViewer();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeViewer();
    });

    // ========================================================
    //  CARD LIKE (right rail)
    //  Optimistic UI + server sync
    // ========================================================
    function toggleLike(id, btn) {
        var countEl    = document.getElementById('like-count-' + id);
        var sideCount  = document.getElementById('side-like-count-' + id);
        var viewerCnt  = document.getElementById('viewerLikeCount');
        var current    = parseInt(countEl.textContent) || 0;
        var liked      = btn.classList.contains('liked');
        var icon       = btn.querySelector('.col-action-circle i');
        var delta      = liked ? -1 : 1;

        // Optimistic update
        if (liked) {
            btn.classList.remove('liked');
            icon.className = 'bx bx-heart';
            countEl.textContent = Math.max(0, current - 1);
            if (sideCount) sideCount.textContent = Math.max(0, current - 1);
        } else {
            btn.classList.add('liked');
            icon.className = 'bx bxs-heart';
            countEl.textContent = current + 1;
            if (sideCount) sideCount.textContent = current + 1;
        }

        // Sync viewer if open on this card
        if (currentViewerId === id && viewerCnt) {
            viewerCnt.textContent = countEl.textContent;
        }

        // Send to server
        var fd = new FormData();
        fd.append('action', 'like');
        fd.append('notification_id', id);
        fd.append('delta', delta);

        fetch('list.php', { method: 'POST', body: fd })
            .then(function(r){ return r.json(); })
            .then(function(resp){
                if (resp && resp.ok) {
                    // Reconcile with server-side value
                    countEl.textContent = resp.count;
                    if (sideCount) sideCount.textContent = resp.count;
                    if (currentViewerId === id && viewerCnt) viewerCnt.textContent = resp.count;
                }
            })
            .catch(function(){ /* keep optimistic value */ });
    }

    // ========================================================
    //  VIEWER LIKE
    // ========================================================
    function toggleViewerLike() {
        if (!currentViewerId) return;

        var vLikeBtn = document.getElementById('viewerLikeBtn');
        var vIcon    = vLikeBtn.querySelector('i');
        var vCount   = document.getElementById('viewerLikeCount');

        var cardBtn   = document.getElementById('like-btn-' + currentViewerId);
        var cardCount = document.getElementById('like-count-' + currentViewerId);
        var sideCount = document.getElementById('side-like-count-' + currentViewerId);

        var current = parseInt(vCount.textContent) || 0;
        var delta   = viewerLiked ? -1 : 1;

        if (viewerLiked) {
            viewerLiked = false;
            vLikeBtn.classList.remove('liked');
            vIcon.className = 'bx bx-heart';
            vCount.textContent = Math.max(0, current - 1);
            if (cardBtn) { cardBtn.classList.remove('liked'); cardBtn.querySelector('i').className = 'bx bx-heart'; }
            if (cardCount) cardCount.textContent = Math.max(0, current - 1);
            if (sideCount) sideCount.textContent = Math.max(0, current - 1);
        } else {
            viewerLiked = true;
            vLikeBtn.classList.add('liked');
            vIcon.className = 'bx bxs-heart';
            vCount.textContent = current + 1;
            if (cardBtn) { cardBtn.classList.add('liked'); cardBtn.querySelector('i').className = 'bx bxs-heart'; }
            if (cardCount) cardCount.textContent = current + 1;
            if (sideCount) sideCount.textContent = current + 1;
        }

        // Send to server
        var fd = new FormData();
        fd.append('action', 'like');
        fd.append('notification_id', currentViewerId);
        fd.append('delta', delta);

        fetch('list.php', { method: 'POST', body: fd })
            .then(function(r){ return r.json(); })
            .then(function(resp){
                if (resp && resp.ok) {
                    vCount.textContent = resp.count;
                    if (cardCount) cardCount.textContent = resp.count;
                    if (sideCount) sideCount.textContent = resp.count;
                }
            })
            .catch(function(){});
    }

    // ========================================================
    //  SUBMIT VIEWER COMMENT
    // ========================================================
    function submitViewerComment() {
        if (!currentViewerId) return;

        var input = document.getElementById('viewerCommentInput');
        var text  = (input.value || '').trim();
        if (!text) { input.focus(); return; }

        var cntEl     = document.getElementById('comment-count-' + currentViewerId);
        var sideCnt   = document.getElementById('side-comment-count-' + currentViewerId);
        var viewerCnt = document.getElementById('viewerCommentCount');

        var fd = new FormData();
        fd.append('action', 'comment');
        fd.append('notification_id', currentViewerId);
        fd.append('comment_text', text);

        fetch('list.php', { method: 'POST', body: fd })
            .then(function(r){ return r.json(); })
            .then(function(resp){
                if (resp && resp.ok) {
                    if (cntEl)    cntEl.textContent = resp.count;
                    if (sideCnt)  sideCnt.textContent = resp.count;
                    if (viewerCnt) viewerCnt.textContent = resp.count;
                    input.value = '';
                } else {
                    alert('Could not post comment.');
                }
            })
            .catch(function(){ alert('Network error.'); });
    }
</script>
</body>
</html>