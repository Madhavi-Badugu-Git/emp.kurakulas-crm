<?php session_start(); 
include('includes/dbConfig.php'); 
$message = '';
$cond = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>KURAKULA'S - Employee Portal</title>
    <meta name="description" content="KURAKULA'S - The Complete Solution for all your services" />
    <link rel="icon" type="image/x-icon" href="assets/img/logos/favicon.jpeg" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        /* ══ LOCK TO VIEWPORT — ZERO SCROLL ══ */
        html, body {
            width:100%; height:100%;
            overflow:hidden;
            font-family:'Inter',sans-serif;
            background:#0b1120;
        }

        /* ══ OUTER SHELL ══ */
        .shell {
            width:100vw; height:100vh;
            display:flex;
            background:linear-gradient(145deg,#0b1120 0%,#192132 100%);
        }

        /* ══════════════════════════════
           LEFT PANEL
        ══════════════════════════════ */
        .left-panel {
            flex:1.25;
            background:linear-gradient(160deg,#0d1b2e 0%,#111e35 50%,#0a1529 100%);
            display:flex;
            flex-direction:column;
            padding:22px 26px;
            position:relative;
            overflow:hidden;
            border-right:1px solid rgba(99,102,241,0.12);
        }

        /* animated grid */
        .left-panel::before {
            content:'';
            position:absolute; inset:0;
            background-image:
                linear-gradient(rgba(99,102,241,0.04) 1px,transparent 1px),
                linear-gradient(90deg,rgba(99,102,241,0.04) 1px,transparent 1px);
            background-size:36px 36px;
            animation:gridShift 25s linear infinite;
            pointer-events:none;
        }
        @keyframes gridShift { 0%{background-position:0 0} 100%{background-position:36px 36px} }

        /* glow blobs */
        .blob {
            position:absolute; border-radius:50%;
            filter:blur(70px); pointer-events:none; animation:blobPulse 8s ease-in-out infinite;
        }
        .blob-1 { top:-80px; right:-60px;  width:320px; height:320px; background:rgba(99,102,241,.10); }
        .blob-2 { bottom:-60px; left:-60px; width:280px; height:280px; background:rgba(139,92,246,.08); animation-delay:3s; }
        .blob-3 { top:40%; left:30%; width:200px; height:200px; background:rgba(236,72,153,.05); animation-delay:5s; }
        @keyframes blobPulse { 0%,100%{opacity:.4;transform:scale(1)} 50%{opacity:.8;transform:scale(1.12)} }

        /* ── TOP: logo + live ── */
        .lp-top {
            position:relative; z-index:2;
            display:flex; align-items:center; justify-content:space-between;
            margin-bottom:16px;
        }
        .lp-logo img {
            height:34px; width:auto;
            filter:brightness(0) invert(1) opacity(.7);
        }
        .live-pill {
            display:inline-flex; align-items:center; gap:6px;
            background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.25);
            border-radius:20px; padding:4px 11px;
            font-size:10.5px; font-weight:700; color:#ef4444;
            letter-spacing:.5px;
        }
        .live-dot {
            width:6px; height:6px; border-radius:50%; background:#ef4444;
            animation:blink 1.4s infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.2} }

        /* ── HEADLINE ── */
        .lp-headline {
            position:relative; z-index:2;
            margin-bottom:14px;
        }
        .lp-headline h2 {
            font-size:20px; font-weight:800; color:#fff;
            line-height:1.25; margin-bottom:3px;
        }
        .lp-headline h2 span { color:#818cf8; }
        .lp-headline p { font-size:11.5px; color:#64748b; }

        /* ── STATS ROW ── */
        .stats-row {
            position:relative; z-index:2;
            display:grid; grid-template-columns:repeat(3,1fr); gap:8px;
            margin-bottom:12px;
        }
        .stat-card {
            background:rgba(255,255,255,.03);
            border:1px solid rgba(255,255,255,.06);
            border-radius:14px; padding:11px 10px; text-align:center;
            transition:background .3s;
            animation:statGlow 3s ease-in-out infinite;
        }
        .stat-card:nth-child(2){animation-delay:.3s}
        .stat-card:nth-child(3){animation-delay:.6s}
        @keyframes statGlow {
            0%,100%{background:rgba(255,255,255,.03)} 
            50%{background:rgba(99,102,241,.10)}
        }
        .stat-val {
            font-size:22px; font-weight:800;
            background:linear-gradient(135deg,#fff,#94a3b8);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
            background-clip:text; display:block; line-height:1.1;
        }
        .stat-lbl { font-size:9.5px; color:#64748b; text-transform:uppercase; letter-spacing:.8px; margin-top:3px; display:block; }

        /* ── CHART CARD ── */
        .chart-card {
            position:relative; z-index:2;
            background:rgba(255,255,255,.03);
            border:1px solid rgba(255,255,255,.06);
            border-radius:16px; padding:14px;
            margin-bottom:10px;
        }
        .chart-head {
            display:flex; justify-content:space-between; align-items:center;
            margin-bottom:10px;
        }
        .chart-head span {
            font-size:12px; font-weight:600; color:rgba(255,255,255,.70);
            display:flex; align-items:center; gap:6px;
        }
        .chart-head span i { color:#818cf8; }
        .chart-badge {
            font-size:10px; font-weight:700; color:#4ade80;
            background:rgba(74,222,128,.10); border-radius:8px; padding:2px 8px;
        }
        /* bar chart */
        .bars {
            display:flex; align-items:flex-end; gap:5px; height:60px;
        }
        .bar {
            flex:1; border-radius:4px 4px 0 0;
            background:linear-gradient(to top,#6366f1,#a78bfa);
            animation:barWave 2.5s ease-in-out infinite;
        }
        .bar:nth-child(1){animation-delay:0s; --h:45%}
        .bar:nth-child(2){animation-delay:.2s; --h:75%}
        .bar:nth-child(3){animation-delay:.4s; --h:55%}
        .bar:nth-child(4){animation-delay:.6s; --h:90%}
        .bar:nth-child(5){animation-delay:.8s; --h:65%}
        .bar:nth-child(6){animation-delay:1.0s;--h:80%}
        .bar:nth-child(7){animation-delay:1.2s;--h:50%}
        @keyframes barWave {
            0%,100%{height:var(--h)}
            50%{height:calc(var(--h) + 15%)}
        }

        /* ── PROGRESS CARD ── */
        .prog-card {
            position:relative; z-index:2;
            background:rgba(255,255,255,.03);
            border:1px solid rgba(255,255,255,.06);
            border-radius:16px; padding:12px 14px;
            margin-bottom:10px;
        }
        .prog-row { margin-bottom:8px; }
        .prog-row:last-child { margin-bottom:0; }
        .prog-meta {
            display:flex; justify-content:space-between;
            font-size:11px; color:#94a3b8; margin-bottom:5px;
        }
        .prog-meta span:last-child { color:#818cf8; font-weight:700; }
        .prog-track {
            height:5px; background:rgba(255,255,255,.05); border-radius:10px; overflow:hidden;
        }
        .prog-fill {
            height:100%; border-radius:10px;
            animation:fillAnim 3s ease-in-out infinite alternate;
        }
        .pf-1 { background:linear-gradient(90deg,#6366f1,#a78bfa); width:78%; animation-delay:0s; }
        .pf-2 { background:linear-gradient(90deg,#ec4899,#f472b6); width:62%; animation-delay:.5s; }
        .pf-3 { background:linear-gradient(90deg,#14b8a6,#2dd4bf); width:89%; animation-delay:1s; }
        @keyframes fillAnim { from{opacity:.6} to{opacity:1} }

        /* ── ACTIVITY FEED ── */
        .activity-card {
            position:relative; z-index:2;
            background:rgba(255,255,255,.03);
            border:1px solid rgba(255,255,255,.06);
            border-radius:16px; padding:12px 14px;
            flex:1; /* fill remaining space */
        }
        .activity-head {
            font-size:11.5px; font-weight:700;
            color:rgba(255,255,255,.55); margin-bottom:9px;
            display:flex; align-items:center; gap:5px;
        }
        .activity-head i { color:#818cf8; }
        .act-item {
            display:flex; align-items:center; gap:9px;
            padding:7px 8px; border-radius:10px;
            margin-bottom:5px;
            animation:actSlide 3s ease-in-out infinite;
        }
        .act-item:last-child { margin-bottom:0; }
        .act-item:nth-child(1){animation-delay:0s}
        .act-item:nth-child(2){animation-delay:.4s}
        .act-item:nth-child(3){animation-delay:.8s}
        @keyframes actSlide {
            0%,100%{background:rgba(255,255,255,.02); transform:translateX(0)}
            50%{background:rgba(99,102,241,.08); transform:translateX(4px)}
        }
        .act-dot {
            width:8px; height:8px; border-radius:50%; flex-shrink:0;
            animation:dotBeat 1.5s infinite;
        }
        @keyframes dotBeat { 0%,100%{transform:scale(1)} 50%{transform:scale(1.6); opacity:.5} }
        .dot-p { background:#818cf8; }
        .dot-s { background:#4ade80; }
        .dot-w { background:#f59e0b; }
        .act-text { flex:1; }
        .act-title { font-size:11.5px; font-weight:600; color:rgba(255,255,255,.75); }
        .act-time  { font-size:10px; color:#4b5563; margin-top:1px; }
        .act-tag {
            font-size:9.5px; font-weight:700; padding:2px 7px; border-radius:6px;
        }
        .tag-new { background:rgba(129,140,248,.15); color:#818cf8; }
        .tag-ok  { background:rgba(74,222,128,.12);  color:#4ade80; }
        .tag-w   { background:rgba(245,158,11,.12);  color:#f59e0b; }

        /* ── BOTTOM BAR ── */
        .lp-bottom {
            position:relative; z-index:2;
            display:flex; align-items:center; justify-content:space-between;
            margin-top:8px; padding-top:8px;
            border-top:1px solid rgba(255,255,255,.05);
        }
        .lp-version { font-size:10px; color:#374151; }
        .lp-online {
            display:flex; align-items:center; gap:5px;
            font-size:10px; color:#374151;
        }
        .online-dot { width:6px; height:6px; border-radius:50%; background:#4ade80; box-shadow:0 0 6px #4ade80; }


        /* ══════════════════════════════
           RIGHT PANEL — LOGIN FORM
        ══════════════════════════════ */
        .right-panel {
            flex:0.82;
            background:#fff;
            display:flex;
            flex-direction:column;
            justify-content:center;
            padding:24px 42px;
            position:relative;
            overflow:hidden;
        }

        /* subtle top accent */
        .right-panel::before {
            content:'';
            position:absolute; top:0; left:0; right:0; height:3px;
            background:linear-gradient(90deg,#6366f1,#8b5cf6,#ec4899);
        }

        /* faint corner graphic */
        .right-panel::after {
            content:'';
            position:absolute; top:-80px; right:-80px;
            width:260px; height:260px; border-radius:50%;
            background:radial-gradient(circle,rgba(99,102,241,.06) 0%,transparent 70%);
            pointer-events:none;
        }

        /* LOGO */
        .rp-logo { text-align:center; margin-bottom:16px; position:relative; z-index:1; }
        .rp-logo img {
            height:52px; width:auto;
            filter:drop-shadow(0 3px 12px rgba(99,102,241,.18));
            transition:transform .3s;
        }
        .rp-logo img:hover { transform:scale(1.07) rotate(2deg); }

        /* welcome */
        .rp-welcome { margin-bottom:14px; position:relative; z-index:1; }
        .rp-welcome h4 {
            color:#1e293b; font-size:20px; font-weight:800;
            margin-bottom:3px; line-height:1.2;
        }
        .rp-welcome p { color:#64748b; font-size:12.5px; }

        /* employee badge */
        .emp-badge {
            display:flex; align-items:center; gap:12px;
            background:linear-gradient(135deg,#f8faff,#f1f5ff);
            border:1px solid #e0e7ff; border-radius:14px;
            padding:11px 14px; margin-bottom:14px;
            position:relative; z-index:1;
        }
        .badge-ico {
            width:40px; height:40px; flex-shrink:0;
            background:linear-gradient(135deg,#6366f1,#8b5cf6);
            border-radius:12px; display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:18px;
        }
        .badge-txt strong { display:block; color:#1e293b; font-size:13px; margin-bottom:2px; font-weight:700; }
        .badge-txt span   { color:#64748b; font-size:11.5px; }

        /* form groups */
        .form-group { margin-bottom:13px; position:relative; z-index:1; }
        .form-group label {
            display:block; font-size:10.5px; font-weight:700;
            color:#6366f1; text-transform:uppercase; letter-spacing:.8px; margin-bottom:6px;
        }
        .input-wrap { position:relative; }
        .input-wrap i {
            position:absolute; top:50%; transform:translateY(-50%);
            color:#cbd5e1; font-size:15px; pointer-events:none; z-index:2; transition:color .25s;
        }
        .input-wrap .ii-left  { left:14px; }
        .input-wrap .ii-right { left:auto; right:14px; cursor:pointer; pointer-events:all; }
        .input-wrap:focus-within .ii-left { color:#6366f1; }
        .input-wrap input {
            width:100%; padding:13px 44px;
            border:2px solid #e2e8f0; border-radius:14px;
            font-size:13.5px; font-family:'Inter',sans-serif;
            background:#f8fafc; color:#1e293b;
            transition:all .25s; outline:none;
        }
        .input-wrap input:focus {
            border-color:#6366f1; background:#fff;
            box-shadow:0 0 0 4px rgba(99,102,241,.10);
        }
        .input-wrap input::placeholder { color:#cbd5e1; }

        /* security notice */
        .sec-notice {
            display:flex; align-items:center; gap:9px;
            background:#f8faff; border-left:3px solid #6366f1;
            border-radius:0 11px 11px 0; padding:10px 13px;
            margin-bottom:13px; position:relative; z-index:1;
        }
        .sec-notice i { color:#6366f1; font-size:14px; flex-shrink:0; }
        .sec-notice span { color:#475569; font-size:11.5px; }

        /* actions row */
        .actions-row {
            display:flex; justify-content:space-between; align-items:center;
            margin-bottom:14px; position:relative; z-index:1;
        }
        .cb-wrap { display:flex; align-items:center; gap:7px; cursor:pointer; }
        .cb-wrap input[type="checkbox"] { width:15px; height:15px; accent-color:#6366f1; cursor:pointer; }
        .cb-wrap span { color:#475569; font-size:12.5px; }
        .forgot-link {
            color:#6366f1; text-decoration:none; font-size:12.5px;
            font-weight:600; display:flex; align-items:center; gap:4px; transition:all .2s;
        }
        .forgot-link:hover { color:#8b5cf6; gap:7px; }

        /* LOGIN BUTTON */
        .login-btn {
            width:100%; padding:14px;
            background:linear-gradient(135deg,#6366f1,#8b5cf6);
            border:none; border-radius:14px; color:#fff;
            font-size:14px; font-weight:700;
            font-family:'Inter',sans-serif;
            cursor:pointer; display:flex; align-items:center; justify-content:center; gap:9px;
            position:relative; overflow:hidden;
            transition:transform .25s, box-shadow .25s;
            margin-bottom:14px; z-index:1;
        }
        .login-btn::before {
            content:'';
            position:absolute; top:0; left:-100%; width:55%; height:100%;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.22),transparent);
            animation:shimBtn 2.5s infinite;
        }
        @keyframes shimBtn { 0%{left:-100%} 100%{left:200%} }
        .login-btn:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(99,102,241,.30); }

        /* footer */
        .rp-footer {
            text-align:center; position:relative; z-index:1;
            border-top:1px solid #f1f5f9; padding-top:10px;
        }
        .office-info {
            display:flex; justify-content:center; gap:16px;
            margin-bottom:6px;
        }
        .office-info span { color:#94a3b8; font-size:11px; display:flex; align-items:center; gap:4px; }
        .office-info i { color:#6366f1; font-size:11px; }
        .copy { color:#cbd5e1; font-size:10.5px; }
        .copy a { color:#6366f1; text-decoration:none; margin:0 4px; }
        .copy a:hover { text-decoration:underline; }

        /* ══ RESPONSIVE ══ */
        @media (max-width:1024px) {
            .left-panel { display:none; }
            .right-panel { flex:1; padding:28px 28px; }
        }
        @media (max-width:480px) {
            .right-panel { padding:20px 18px; }
            .actions-row { flex-direction:column; gap:8px; align-items:flex-start; }
        }

        /* Short screens — tighten everything */
        @media (max-height:720px) {
            .left-panel { padding:16px 20px; }
            .right-panel { padding:18px 36px; }
            .lp-headline h2 { font-size:17px; }
            .stat-val { font-size:19px; }
            .rp-logo img { height:44px; }
            .rp-welcome { margin-bottom:10px; }
            .rp-welcome h4 { font-size:17px; }
            .emp-badge { padding:9px 12px; margin-bottom:10px; }
            .form-group { margin-bottom:9px; }
            .input-wrap input { padding:11px 42px; font-size:13px; }
            .sec-notice { padding:8px 11px; margin-bottom:10px; }
            .actions-row { margin-bottom:10px; }
            .login-btn { padding:12px; margin-bottom:10px; }
            .stats-row { margin-bottom:8px; }
            .chart-card { margin-bottom:7px; }
            .prog-card  { margin-bottom:7px; }
        }
        @media (max-height:620px) {
            .activity-card { display:none; }
            .prog-card { display:none; }
            .rp-footer .office-info { display:none; }
        }
    </style>
</head>
<body>
<div class="shell">

    <!-- ══════════════════════════════
         LEFT PANEL
    ══════════════════════════════ -->
    <div class="left-panel">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        <!-- TOP: logo + LIVE -->
        <div class="lp-top">
            <div class="lp-logo">
                <img src="assets/img/logos/kurakulas.png" alt="KURAKULA'S">
            </div>
            <div class="live-pill">
                <div class="live-dot"></div> LIVE
            </div>
        </div>

        <!-- HEADLINE -->
        <div class="lp-headline">
            <h2>Employee <span>Dashboard</span></h2>
            <p>Real-time company overview &amp; analytics</p>
        </div>

        <!-- STATS ROW -->
        <div class="stats-row">
            <div class="stat-card">
                <span class="stat-val">156</span>
                <span class="stat-lbl">Active Staff</span>
            </div>
            <div class="stat-card">
                <span class="stat-val">24</span>
                <span class="stat-lbl">Projects</span>
            </div>
            <div class="stat-card">
                <span class="stat-val">89%</span>
                <span class="stat-lbl">Performance</span>
            </div>
        </div>

        <!-- CHART CARD -->
        <div class="chart-card">
            <div class="chart-head">
                <span><i class="fas fa-chart-bar"></i> Weekly Analytics</span>
                <div class="chart-badge">+12.4%</div>
            </div>
            <div class="bars">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
        </div>

        <!-- PROGRESS CARD -->
        <div class="prog-card">
            <div class="prog-row">
                <div class="prog-meta"><span>Monthly Target</span><span>78%</span></div>
                <div class="prog-track"><div class="prog-fill pf-1"></div></div>
            </div>
            <div class="prog-row">
                <div class="prog-meta"><span>Sales Goal</span><span>62%</span></div>
                <div class="prog-track"><div class="prog-fill pf-2"></div></div>
            </div>
            <div class="prog-row">
                <div class="prog-meta"><span>Completion Rate</span><span>89%</span></div>
                <div class="prog-track"><div class="prog-fill pf-3"></div></div>
            </div>
        </div>

        <!-- ACTIVITY FEED -->
        <div class="activity-card">
            <div class="activity-head"><i class="fas fa-bolt"></i> Live Activity</div>

            <div class="act-item">
                <div class="act-dot dot-p"></div>
                <div class="act-text">
                    <div class="act-title">Monthly report ready</div>
                    <div class="act-time">20 minutes ago</div>
                </div>
                <div class="act-tag tag-new">NEW</div>
            </div>

            <div class="act-item">
                <div class="act-dot dot-s"></div>
                <div class="act-text">
                    <div class="act-title">Client feedback received</div>
                    <div class="act-time">8 minutes ago</div>
                </div>
                <div class="act-tag tag-ok">REVIEW</div>
            </div>

            <div class="act-item">
                <div class="act-dot dot-w"></div>
                <div class="act-text">
                    <div class="act-title">System update completed</div>
                    <div class="act-time">30 minutes ago</div>
                </div>
                <div class="act-tag tag-w">SUCCESS</div>
            </div>
        </div>

        
    </div>


    <!-- ══════════════════════════════
         RIGHT PANEL — LOGIN
    ══════════════════════════════ -->
    <div class="right-panel">

        <!-- LOGO -->
        <div class="rp-logo">
            <img src="assets/img/logos/kurakulas.png" alt="KURAKULA'S" style="height:70px;">
        </div>

        <!-- WELCOME -->
        <div class="rp-welcome">
            <h4>Welcome Back!</h4>
            <p>Sign in to access your employee dashboard</p>
        </div>

        <!-- EMPLOYEE BADGE -->
        <div class="emp-badge">
            <div class="badge-ico"><i class="fas fa-id-card"></i></div>
            <div class="badge-txt">
                <strong>Employee ID Required</strong>
                <span>Use your company credentials to login</span>
            </div>
        </div>

        <!-- FORM -->
        <form method="POST" id="formAuthentication">

            <div class="form-group">
                <label><i class="fas fa-user"></i> Employee ID / Username</label>
                <div class="input-wrap">
                    <i class="fas fa-user ii-left"></i>
                    <input type="text" name="username" placeholder="Enter your employee ID" autofocus>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock ii-left"></i>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                    <i class="fas fa-eye ii-right" onclick="togglePassword()"></i>
                </div>
            </div>

            <!-- SECURITY NOTICE -->
            <div class="sec-notice">
                <i class="fas fa-shield-alt"></i>
                <span>Secured company portal &nbsp;•&nbsp; Authorized access only</span>
            </div>

            <!-- ACTIONS -->
            <div class="actions-row">
                <label class="cb-wrap">
                    <input type="checkbox" id="remember-me">
                    <span>Remember me</span>
                </label>
                <a href="#" class="forgot-link" onclick="showForgotPassword(event)">
                    <i class="fas fa-question-circle"></i> Forgot Password?
                </a>
            </div>

            <!-- LOGIN BUTTON -->
            <button type="submit" name="submit" class="login-btn">
                <span>LOGIN TO DASHBOARD</span>
                <i class="fas fa-arrow-right"></i>
            </button>

        </form>

        <!-- FOOTER -->
        <div class="rp-footer">
            <!-- <div class="office-info">
                <span><i class="fas fa-building"></i> KURAKULA'S HQ</span>
                <span><i class="fas fa-clock"></i> 24/7 Support</span>
                <span><i class="fas fa-phone"></i> Ext: 1234</span>
            </div> -->
            <div class="copy">
                &copy; <?php echo date('Y'); ?> KURAKULA'S. 
                <a href="#">Privacy</a> | <a href="#">Terms</a>
            </div>
        </div>

    </div><!-- /right-panel -->

</div><!-- /shell -->

<!-- Scripts — identical to original -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<script>
    function togglePassword() {
        const inp  = document.getElementById('password');
        const ico  = document.querySelector('.ii-right');
        if (inp.type === 'password') {
            inp.type = 'text';
            ico.classList.replace('fa-eye','fa-eye-slash');
        } else {
            inp.type = 'password';
            ico.classList.replace('fa-eye-slash','fa-eye');
        }
    }

    function showForgotPassword(e) {
        e.preventDefault();
        iziToast.info({
            title: '🔐 Password Recovery',
            message: 'Please contact HR at hr@kurakulas.com or ext. 1234',
            position: 'topRight',
            backgroundColor: '#ffffff',
            titleColor: '#6366f1',
            messageColor: '#475569',
            timeout: 5000,
            progressBarColor: '#6366f1'
        });
    }

    document.getElementById('formAuthentication').addEventListener('submit', function(e) {
        const username = document.querySelector('input[name="username"]').value;
        const password = document.querySelector('input[name="password"]').value;
        if (!username || !password) {
            e.preventDefault();
            iziToast.warning({
                title: '⚠️ Required Fields',
                message: 'Please enter both username and password',
                position: 'topRight',
                backgroundColor: '#ffffff',
                titleColor: '#6366f1',
                messageColor: '#475569',
                timeout: 3000,
                progressBarColor: '#6366f1'
            });
        }
    });
</script>
</body>
</html>

<?php 
if(isset($_POST['submit'])){
    $username_1 = $_POST['username'];
    $password_1 = $_POST['password'];

    if(empty($username_1) || empty($password_1)){
        ?>
<script>
$(document).ready(function() {
    iziToast.warning({
        title: "⚠️ Required Fields",
        message: "Please enter both username and password!",
        position: "topRight",
        backgroundColor: '#ffffff',
        titleColor: '#6366f1',
        messageColor: '#475569',
        timeout: 4000,
        progressBarColor: '#6366f1'
    });
});
</script>
<?php
    } else {
        $sql = "SELECT * FROM tbl_user WHERE username='$username_1' AND password='$password_1' AND status='1'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            if($row = mysqli_fetch_assoc($result)){
                $username = $row['username'];
                $password = $row['password'];
                $rank = $row['rank'];
                $_SESSION['loggedInUser'] = $username;
                $logged_in_at = date('Y-m-d H:i:s');

                $sql_session = "INSERT INTO tbl_login(username,logged_in_at) VALUES('$username','$logged_in_at')";
                $result_session = mysqli_query($conn, $sql_session);
                
                if(isset($_SESSION['url'])){
                    $url = $_SESSION['url'];
                } else {
                    if($rank == 'superAdmin'){
                        $url = "dashboard/superAdmin";
                    } else if($rank == 'Admin'){
                        $url = 'dashboard/admin';
                    } else if($rank == 'User'){
                        $url = 'dashboard/user';
                    }
                }
                ?>
<script type="text/javascript">
window.location = "<?= $url; ?>";
</script>
<?php
            }
        } else {
            ?>
<script>
$(document).ready(function() {
    iziToast.error({
        title: "❌ Access Denied",
        message: "Invalid username or password. Please try again!",
        position: "topRight",
        backgroundColor: '#ffffff',
        titleColor: '#ef4444',
        messageColor: '#475569',
        timeout: 4000,
        progressBarColor: '#ef4444'
    });
});
</script>
<?php
        }
    }
}
?>
<?php include('includes/script.php'); ?>