<?php
// Start output buffering to prevent "headers already sent" errors
ob_start();

session_start();
include('../includes/dbConfig.php');
include('../includes/validation.php');
include('../includes/functions.php');


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
       ATTENDANCE REGISTER - COMPLETE STYLES
    ============================================================ */
    .attendance-register-card {
        margin-top: 25px;
    }

    .attendance-register-wrapper {
        width: 100%;
        max-height: 65vh;
        overflow-x: auto;
        overflow-y: auto;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        background: #ffffff;
        padding-bottom: 2px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        position: relative;
    }

    .attendance-register-wrapper::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    .attendance-register-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .attendance-register-wrapper::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 10px;
    }

    .attendance-register-wrapper::-webkit-scrollbar-thumb:hover {
        background: #a0a7ae;
    }

    .attendance-register {
        width: max-content;
        min-width: 100%;
        border-collapse: collapse;
        margin: 0;
        font-size: 13px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .attendance-register thead th {
        height: 52px;
        background: #f8f9fc;
        color: #1a2332;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
        border-bottom: 2px solid #e9ecef;
        border-right: 1px solid #f1f3f5;
        padding: 4px 4px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 5;
        line-height: 1.2;
    }

    .attendance-register thead th:last-child {
        border-right: none;
    }

    /* Day number + day name */
    .attendance-register thead th .date-num {
        display: block;
        font-weight: 700;
        font-size: 13px;
        color: #1a2332;
        line-height: 1.2;
    }

    .attendance-register thead th .day-label {
        display: block;
        font-size: 9px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        line-height: 1.2;
        margin-top: 2px;
    }

    .attendance-register thead th .day-label.sunday {
        color: #dc2626;
    }

    .attendance-register thead th .day-label.saturday {
        color: #d97706;
    }

    .attendance-register tbody td {
        height: 48px;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
        border-bottom: 1px solid #f1f3f5;
        border-right: 1px solid #f1f3f5;
        background: #ffffff;
        padding: 4px 6px;
        transition: background 0.15s ease;
    }

    .attendance-register tbody td:last-child {
        border-right: none;
    }

    .attendance-register tbody tr:hover td {
        background: #f8fafc;
    }

    .attendance-register tbody tr:last-child td {
        border-bottom: none;
    }

    /* Team Lead Badge */
    .teamlead-badge {
        display: inline-block;
        background: #696cff;
        color: #ffffff;
        font-size: 9px;
        font-weight: 700;
        padding: 1px 8px;
        border-radius: 10px;
        margin-left: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        vertical-align: middle;
        line-height: 16px;
    }

    /* ============================================================
       EMPLOYEE COLUMN - STICKY LEFT
    ============================================================ */
    .attendance-register .employee-column {
        width: 340px;
        min-width: 340px;
        max-width: 340px;
        text-align: left;
        position: sticky;
        left: 0;
        z-index: 10;
        background: #ffffff;
        padding: 6px 14px 6px 16px;
        border-right: 2px solid #e9ecef;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.04);
    }

    .attendance-register thead .employee-column {
        background: #f8f9fc;
        z-index: 11;
        border-right: 2px solid #e9ecef;
    }

    .attendance-register tbody tr:hover .employee-column {
        background: #f8fafc;
    }

    /* ============================================================
       ACTIONS COLUMN - STICKY LEFT (After Employee)
    ============================================================ */
    .attendance-register .actions-column {
        width: 100px;
        min-width: 100px;
        max-width: 100px;
        text-align: center;
        position: sticky;
        left: 340px;
        z-index: 9;
        background: #ffffff;
        border-right: 2px solid #e9ecef;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.04);
        padding: 6px 8px;
    }

    .attendance-register thead .actions-column {
        background: #f8f9fc;
        z-index: 10;
        border-right: 2px solid #e9ecef;
    }

    .attendance-register tbody tr:hover .actions-column {
        background: #f8fafc;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin: 0 2px;
        text-decoration: none;
    }

    .action-btn-view {
        background: #e8f0fe;
        color: #1a73e8;
    }

    .action-btn-view:hover {
        background: #d2e3fc;
        color: #1557b0;
        transform: scale(1.1);
    }

    .action-btn-edit {
        background: #fef3e8;
        color: #e37400;
    }

    .action-btn-edit:hover {
        background: #fde8d0;
        color: #b37400;
        transform: scale(1.1);
    }

    .action-btn i {
        font-size: 16px;
    }

    /* ============================================================
       EMPLOYEE INFO STYLES
    ============================================================ */
    .employee-info-wrapper {
        display: flex;
        flex-direction: column;
        gap: 3px;
        padding: 4px 0;
    }

    .register-employee-name {
        font-size: 14px;
        font-weight: 700;
        color: #1a2332;
        line-height: 1.4;
        letter-spacing: 0.3px;
    }

    .register-employee-id {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.3;
        font-weight: 500;
    }

    .register-employee-id .id-value {
        color: #495057;
        font-weight: 600;
        background: #f1f3f5;
        padding: 0 10px;
        border-radius: 3px;
        font-size: 11px;
    }

    .register-employee-dept {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.3;
        font-weight: 500;
    }

    .register-employee-dept .dept-value {
        color: #0056b3;
        font-weight: 600;
        background: #e8f0fe;
        padding: 0 10px;
        border-radius: 3px;
        font-size: 11px;
    }

    .register-employee-designation {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.3;
        font-weight: 500;
    }

    .register-employee-designation .designation-value {
        color: #b37400;
        font-weight: 600;
        background: #fef3e8;
        padding: 0 10px;
        border-radius: 3px;
        font-size: 11px;
    }

    /* ============================================================
       ATTENDANCE SELECT DROPDOWN
    ============================================================ */
    .attendance-register .day-column {
        width: 44px;
        min-width: 44px;
        max-width: 44px;
        padding: 4px 3px;
    }

    .attendance-register-select {
        width: 36px;
        min-width: 36px;
        height: 30px;
        padding: 0 2px;
        margin: 0 auto;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        border: 1.5px solid #dde1e6;
        border-radius: 5px;
        background-color: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
        display: block;
        color: #1a2332;
    }

    .attendance-register-select:hover {
        border-color: #b0b8c4;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    }

    .attendance-register-select:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.15);
        outline: none;
    }

    /* ============================================================
       ATTENDANCE COLORS
    ============================================================ */
    .attendance-present {
        background: #dcfce7 !important;
        border-color: #22c55e !important;
        color: #166534 !important;
        font-weight: 700 !important;
    }

    .attendance-absent {
        background: #fee2e2 !important;
        border-color: #ef4444 !important;
        color: #991b1b !important;
        font-weight: 700 !important;
    }

    .attendance-half {
        background: #fef9c3 !important;
        border-color: #eab308 !important;
        color: #713f12 !important;
        font-weight: 700 !important;
    }

    .attendance-leave {
        background: #dbeafe !important;
        border-color: #3b82f6 !important;
        color: #1e40af !important;
        font-weight: 700 !important;
    }

    .attendance-holiday {
        background: #ede9fe !important;
        border-color: #8b5cf6 !important;
        color: #5b21b6 !important;
        font-weight: 700 !important;
    }

    .attendance-weekoff {
        background: #f1f3f5 !important;
        border-color: #9ca3af !important;
        color: #4b5563 !important;
        font-weight: 700 !important;
    }

    /* ============================================================
       LEGEND
    ============================================================ */
    .legend-container {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 8px;
        padding: 0;
    }

    .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 500;
        color: #374151;
        padding: 3px 12px 3px 8px;
        border-radius: 20px;
        background: #f8fafc;
        border: 1px solid #e9ecef;
    }

    .legend-item:hover {
        background: #f1f3f5;
    }

    .legend-box {
        display: inline-block;
        width: 22px;
        height: 22px;
        border-radius: 4px;
        text-align: center;
        line-height: 22px;
        font-size: 10px;
        font-weight: 700;
        border: 1px solid rgba(0, 0, 0, 0.06);
        flex-shrink: 0;
    }

    .legend-box.present {
        background: #dcfce7;
        color: #166534;
        border-color: #bbf7d0;
    }

    .legend-box.absent {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .legend-box.half {
        background: #fef9c3;
        color: #713f12;
        border-color: #fde68a;
    }

    .legend-box.leave {
        background: #dbeafe;
        color: #1e40af;
        border-color: #bfdbfe;
    }

    .legend-box.holiday {
        background: #ede9fe;
        color: #5b21b6;
        border-color: #ddd6fe;
    }

    .legend-box.weekoff {
        background: #f1f3f5;
        color: #4b5563;
        border-color: #e5e7eb;
    }

    /* ============================================================
       FILTER
    ============================================================ */
    .register-filter {
        background: #f8fafc;
        padding: 16px 18px;
        border-radius: 10px;
        margin-bottom: 18px;
        border: 1px solid #e9ecef;
    }

    .register-filter .form-select {
        border-color: #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        padding: 8px 14px;
        background-color: #ffffff;
    }

    .register-filter .form-select:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.1);
    }

    .register-filter .form-control {
        border-color: #e9ecef;
        border-radius: 8px;
        font-size: 14px;
        padding: 8px 14px;
        background-color: #ffffff;
    }

    .register-filter .form-control:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 2px rgba(105, 108, 255, 0.1);
    }

    .register-filter .btn-filter {
        background: #696cff;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .register-filter .btn-filter:hover {
        background: #5a5de0;
        color: #ffffff;
    }

    .register-filter .btn-reset-filter {
        background: #e9ecef;
        color: #495057;
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .register-filter .btn-reset-filter:hover {
        background: #dde1e6;
        color: #1a2332;
    }

    /* Filter row fix - ensure proper alignment */
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 10px;
    }

    .filter-row .filter-group {
        flex: 1;
        min-width: 150px;
    }

    .filter-row .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 4px;
        display: block;
    }

    .filter-row .filter-actions {
        display: flex;
        gap: 8px;
        min-width: 180px;
    }

    .filter-row .filter-actions .btn {
        flex: 1;
    }

    @media (max-width: 768px) {
        .filter-row .filter-group {
            min-width: 100%;
        }

        .filter-row .filter-actions {
            min-width: 100%;
        }
    }

    .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e9ecef;
        padding: 18px 24px;
    }

    .card-header h5 {
        font-size: 16px;
        font-weight: 600;
        color: #1a2332;
    }

    .btn-primary {
        background: #696cff;
        border-color: #696cff;
    }

    .btn-primary:hover {
        background: #5a5de0;
        border-color: #5a5de0;
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

    /* Leave Status Badge */
    .leave-status-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .leave-status-badge.pending {
        background: #fef9c3;
        color: #713f12;
    }

    .leave-status-badge.approved {
        background: #dcfce7;
        color: #166534;
    }

    .leave-status-badge.rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        max-width: 420px;
        margin: 0 auto;
    }

    .modal-dialog {
        max-width: 420px;
        margin: 1.75rem auto;
    }

    .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 14px 20px;
        background: #ffffff;
        border-radius: 12px 12px 0 0;
    }

    .modal-header .modal-title {
        font-weight: 600;
        color: #1a2332;
        font-size: 16px;
    }

    .modal-header .modal-title i {
        font-size: 18px;
        color: #696cff;
        margin-right: 8px;
    }

    .modal-header .btn-close {
        font-size: 14px;
        padding: 6px;
        opacity: 0.6;
    }

    .modal-header .btn-close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 18px 22px 14px 22px;
    }

    .modal-body .info-item {
        margin-bottom: 8px;
    }

    .modal-body .info-item .label {
        font-size: 11px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 1px;
    }

    .modal-body .info-item .value {
        font-size: 15px;
        font-weight: 600;
        color: #1a2332;
    }

    .modal-body .info-item .value.reason {
        font-weight: 400;
        color: #6c757d;
        font-size: 14px;
    }

    .modal-body .alert-info {
        padding: 8px 14px;
        font-size: 13px;
        margin: 12px 0 14px 0;
        border-radius: 8px;
        background: #f0f4ff;
        color: #1a56db;
        border: 1px solid #dbeafe;
    }

    .modal-body .alert-info i {
        font-size: 16px;
        margin-right: 6px;
    }

    .modal-body .form-select {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 8px;
        border-color: #e9ecef;
        height: 40px;
        background-color: #ffffff;
    }

    .modal-body .form-select:focus {
        border-color: #696cff;
        box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
    }

    .modal-body label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 4px;
        display: block;
    }

    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 12px 22px 16px 22px;
        background: #fafbfc;
        border-radius: 0 0 12px 12px;
    }

    .modal-footer .btn {
        font-size: 14px;
        padding: 8px 22px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-approve {
        background: #22c55e;
        color: #ffffff;
        border: none;
        padding: 8px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .btn-approve:hover {
        background: #16a34a;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .btn-approve:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    .btn-secondary {
        font-size: 14px;
        padding: 8px 22px;
        border-radius: 8px;
        background: #f1f3f5;
        border: 1px solid #e9ecef;
        color: #495057;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        color: #1a2332;
    }

    .btn-reject {
        background: #ef4444;
        color: #ffffff;
        border: none;
        padding: 8px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-reject:hover {
        background: #dc2626;
        color: #ffffff;
    }

    /* Self Approval Warning */
    .self-approval-warning {
        padding: 6px 12px;
        font-size: 12px;
        margin-top: 6px;
        border-radius: 6px;
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
        display: none;
    }

    .self-approval-warning.show {
        display: block;
    }

    .self-approval-warning i {
        margin-right: 4px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modal-dialog {
            max-width: 380px;
            margin: 1rem auto;
        }
    }

    @media (max-width: 480px) {
        .modal-dialog {
            max-width: 340px;
            margin: 0.5rem auto;
        }
        .modal-body {
            padding: 14px 16px 10px 16px;
        }
        .modal-body .info-item .value {
            font-size: 14px;
        }
    }
    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 992px) {
        .attendance-register-wrapper {
            max-height: 60vh;
        }

        .attendance-register .employee-column {
            width: 280px;
            min-width: 280px;
            max-width: 280px;
        }

        .attendance-register .actions-column {
            width: 90px;
            min-width: 90px;
            max-width: 90px;
            left: 280px;
        }

        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }

        .action-btn i {
            font-size: 13px;
        }

        .attendance-register .day-column {
            width: 38px;
            min-width: 38px;
            max-width: 38px;
        }

        .attendance-register-select {
            width: 32px;
            min-width: 32px;
            height: 26px;
            font-size: 10px;
        }
    }

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

        .attendance-register-wrapper {
            max-height: 55vh;
        }

        .attendance-register .employee-column {
            width: 240px;
            min-width: 240px;
            max-width: 240px;
            padding: 6px 10px;
        }

        .attendance-register .actions-column {
            width: 75px;
            min-width: 75px;
            max-width: 75px;
            left: 240px;
            padding: 4px 4px;
        }

        .action-btn {
            width: 24px;
            height: 24px;
            font-size: 11px;
        }

        .action-btn i {
            font-size: 12px;
        }

        .attendance-register .day-column {
            width: 32px;
            min-width: 32px;
            max-width: 32px;
        }

        .attendance-register-select {
            width: 28px;
            min-width: 28px;
            height: 24px;
            font-size: 9px;
        }

        .legend-container {
            gap: 8px;
        }

        .legend-item {
            font-size: 10px;
            padding: 2px 10px 2px 6px;
        }

        .legend-box {
            width: 18px;
            height: 18px;
            line-height: 18px;
            font-size: 8px;
        }

        .attendance-register thead th {
            font-size: 10px;
            padding: 4px 4px;
            height: 46px;
        }

        .attendance-register thead th .date-num {
            font-size: 11px;
        }

        .attendance-register thead th .day-label {
            font-size: 8px;
        }

        .attendance-register tbody td {
            height: 40px;
            padding: 2px 2px;
        }

        .register-employee-name {
            font-size: 12px;
        }

        .register-employee-id,
        .register-employee-dept,
        .register-employee-designation {
            font-size: 10px;
        }

        .filter-row .filter-group {
            min-width: 100%;
        }

        .filter-row .filter-actions {
            min-width: 100%;
        }
    }

    @media (max-width: 480px) {
        .attendance-register .employee-column {
            width: 200px;
            min-width: 200px;
            max-width: 200px;
            padding: 4px 8px;
        }

        .attendance-register .actions-column {
            width: 65px;
            min-width: 65px;
            max-width: 65px;
            left: 200px;
            padding: 2px 4px;
        }

        .action-btn {
            width: 20px;
            height: 20px;
            font-size: 10px;
            border-radius: 4px;
        }

        .action-btn i {
            font-size: 10px;
        }

        .attendance-register .day-column {
            width: 28px;
            min-width: 28px;
            max-width: 28px;
            padding: 2px 1px;
        }

        .attendance-register-select {
            width: 24px;
            min-width: 24px;
            height: 22px;
            font-size: 8px;
            padding: 0 1px;
        }

        .attendance-register thead th {
            height: 44px;
            padding: 2px 2px;
        }

        .attendance-register thead th .date-num {
            font-size: 10px;
        }

        .attendance-register thead th .day-label {
            font-size: 7px;
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
<?php // Set session variables if not set (for demo purposes)
if (!isset($_SESSION['user_rank'])) {
    $_SESSION['user_rank'] = 'admin';
}
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

// ============================================================
// 1. GET PARAMETERS & SETUP DATA (Moved before HTML output)
// ============================================================
$loggedInUserRank = $_SESSION['user_rank'] ?? 'admin';
$loggedInUserId = $_SESSION['user_id'] ?? 0;

// CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

// Month/Year for attendance register
$register_month = isset($_GET['register_month']) ? (int) $_GET['register_month'] : (int) date('n');
$register_year = isset($_GET['register_year']) ? (int) $_GET['register_year'] : (int) date('Y');

if ($register_month < 1 || $register_month > 12)
    $register_month = (int) date('n');
if ($register_year < 2000 || $register_year > 2100)
    $register_year = (int) date('Y');

$register_month_name = date('F', mktime(0, 0, 0, $register_month, 1, $register_year));
$register_total_days = cal_days_in_month(CAL_GREGORIAN, $register_month, $register_year);

// Get filter values
$filter_employee_id = isset($_GET['filter_employee_id']) ? trim($_GET['filter_employee_id']) : '';

// ============================================================
// 2. HANDLE LEAVE APPROVAL/REJECTION (Moved before HTML)
// ============================================================
if (isset($_POST['leave_action'])) {
    $leaveAction = $_POST['leave_action'];
    $leaveId = isset($_POST['leave_id']) ? (int) $_POST['leave_id'] : 0;
    $leaveStatus = ($leaveAction == 'approve') ? 'approved' : 'rejected';
    $approvedBy = isset($_POST['leave_tl_id']) ? (int) $_POST['leave_tl_id'] : 0;
    $employeeId = isset($_POST['leave_employee_id']) ? (int) $_POST['leave_employee_id'] : 0;
    $leaveDate = isset($_POST['leave_date']) ? $_POST['leave_date'] : '';
    $attendanceTypeId = isset($_POST['leave_attendance_type']) ? (int) $_POST['leave_attendance_type'] : 4;

    // Check if the employee is trying to approve their own leave
    if ($employeeId == $approvedBy && $employeeId > 0) {
        $_SESSION['toast_error'] = "You cannot approve your own leave request. Please select another Team Lead.";
    } else {
        // FIRST: Save the attendance as "Leave"
        if ($employeeId > 0 && !empty($leaveDate)) {
            $checkAttSql = "SELECT id FROM tbl_user_attendance WHERE employee_id = '$employeeId' AND date = '$leaveDate' LIMIT 1";
            $checkAttResult = mysqli_query($conn, $checkAttSql);

            if ($checkAttResult && mysqli_num_rows($checkAttResult) > 0) {
                $updateAttSql = "UPDATE tbl_user_attendance SET attendance_type_id = '$attendanceTypeId' WHERE employee_id = '$employeeId' AND date = '$leaveDate'";
                mysqli_query($conn, $updateAttSql);
            } else {
                $insertAttSql = "INSERT INTO tbl_user_attendance (employee_id, date, attendance_type_id, gross_salary, total_working_day, employee_working_day, present_day, casual_leave, deduction_amount, net_salary, created_at)
                                 VALUES ('$employeeId', '$leaveDate', '$attendanceTypeId', '0', '0', '0', '0', '0', '0', '0', NOW())";
                mysqli_query($conn, $insertAttSql);
            }
        }

        // SECOND: Create or get the leave request
        if ($employeeId > 0 && !empty($leaveDate)) {
            $checkLeaveSql = "SELECT id FROM tbl_leave_requests WHERE employee_id = '$employeeId' AND start_date <= '$leaveDate' AND end_date >= '$leaveDate' AND status = 'pending'";
            $checkLeaveResult = mysqli_query($conn, $checkLeaveSql);

            if ($checkLeaveResult && mysqli_num_rows($checkLeaveResult) > 0) {
                $existingLeave = mysqli_fetch_assoc($checkLeaveResult);
                $leaveId = $existingLeave['id'];
            } else {
                $insertLeaveSql = "INSERT INTO tbl_leave_requests (employee_id, start_date, end_date, reason, status, created_at) 
                                   VALUES ('$employeeId', '$leaveDate', '$leaveDate', 'Leave request for $leaveDate', 'pending', NOW())";
                if (mysqli_query($conn, $insertLeaveSql)) {
                    $leaveId = mysqli_insert_id($conn);
                }
            }
        }

        // THIRD: Update leave status
        if ($leaveId > 0) {
            $updateLeaveSql = "UPDATE tbl_leave_requests SET status = '$leaveStatus', approved_by = '$approvedBy', updated_at = NOW() WHERE id = '$leaveId'";
            if (mysqli_query($conn, $updateLeaveSql)) {
                $_SESSION['toast_success'] = "Leave approved and attendance marked as Leave!";
            } else {
                $_SESSION['toast_error'] = "Failed to process leave request. Please try again.";
            }
        } else {
            $_SESSION['toast_warning'] = "No leave request found to process.";
        }
    }

    // Redirect to prevent form resubmission
    $redirectUrl = "add.php?register_month=$register_month&register_year=$register_year&leave_approved=1";
    if (!empty($filter_employee_id)) {
        $redirectUrl .= "&filter_employee_id=" . urlencode($filter_employee_id);
    }
    header("Location: $redirectUrl");
    exit;
}

// ============================================================
// 3. HANDLE ATTENDANCE REGISTER SAVE (Moved before HTML)
// ============================================================
if (isset($_POST['register_submit'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['toast_error'] = "CSRF token validation failed";
        header("Location: add.php?register_month=$register_month&register_year=$register_year");
        exit;
    }

    $registerAttendance = isset($_POST['register_attendance']) ? $_POST['register_attendance'] : [];
    $registerPostMonth = isset($_POST['register_month']) ? (int) $_POST['register_month'] : $register_month;
    $registerPostYear = isset($_POST['register_year']) ? (int) $_POST['register_year'] : $register_year;

    if (!empty($registerAttendance)) {
        $savedCount = 0;
        foreach ($registerAttendance as $employeeId => $days) {
            $employeeId = (int) $employeeId;
            if (!is_array($days))
                continue;

            foreach ($days as $attendanceDate => $attendanceTypeId) {
                $attendanceTypeId = (int) $attendanceTypeId;
                if ($attendanceTypeId <= 0)
                    continue;

                $dateObject = DateTime::createFromFormat('Y-m-d', $attendanceDate);
                if (!$dateObject || $dateObject->format('Y-m-d') !== $attendanceDate)
                    continue;

                $attendanceDate = mysqli_real_escape_string($conn, $attendanceDate);

                $checkRegisterSql = "SELECT id FROM tbl_user_attendance WHERE employee_id = '$employeeId' AND date = '$attendanceDate' LIMIT 1";
                $checkRegisterResult = mysqli_query($conn, $checkRegisterSql);

                if ($checkRegisterResult && mysqli_num_rows($checkRegisterResult) > 0) {
                    $updateSql = "UPDATE tbl_user_attendance SET attendance_type_id = '$attendanceTypeId' WHERE employee_id = '$employeeId' AND date = '$attendanceDate'";
                    if (mysqli_query($conn, $updateSql)) {
                        $savedCount++;
                    }
                } else {
                    $registerInsertSql = "INSERT INTO tbl_user_attendance (employee_id, date, attendance_type_id, gross_salary, total_working_day, employee_working_day, present_day, casual_leave, deduction_amount, net_salary, created_at)
                          VALUES ('$employeeId', '$attendanceDate', '$attendanceTypeId', '0', '0', '0', '0', '0', '0', '0', NOW())";
                    if (mysqli_query($conn, $registerInsertSql)) {
                        $savedCount++;
                    }
                }
            }
        }

        if ($savedCount > 0) {
            $_SESSION['toast_success'] = "Attendance Register Saved Successfully! ($savedCount records updated)";
        } else {
            $_SESSION['toast_warning'] = "No records were saved. Please try again.";
        }
    } else {
        $_SESSION['toast_warning'] = "Please select attendance before saving";
    }

    // Redirect to prevent form resubmission
    $redirectUrl = "add.php?register_month=$registerPostMonth&register_year=$registerPostYear";
    if (!empty($filter_employee_id)) {
        $redirectUrl .= "&filter_employee_id=" . urlencode($filter_employee_id);
    }
    header("Location: $redirectUrl");
    exit;
}

// Check if leave was just approved - prevent modal on reload
$leaveApproved = isset($_GET['leave_approved']) ? (int) $_GET['leave_approved'] : 0;

$pageTitle = 'Attendance Register';

// ============================================================
// 4. NOW INCLUDE HEADER & HTML OUTPUT
?>
                <?php
                // Get employees with department and designation using JOIN
                $employees = [];

                // Check if department_id and designation_id columns exist
                $deptIdCheck = mysqli_query($conn, "SHOW COLUMNS FROM tbl_user LIKE 'department_id'");
                $hasDeptId = ($deptIdCheck && mysqli_num_rows($deptIdCheck) > 0);

                $designationIdCheck = mysqli_query($conn, "SHOW COLUMNS FROM tbl_user LIKE 'designation_id'");
                $hasDesignationId = ($designationIdCheck && mysqli_num_rows($designationIdCheck) > 0);

                // Check if rank column exists
                $rankCheck = mysqli_query($conn, "SHOW COLUMNS FROM tbl_user LIKE 'rank'");
                $hasRankColumn = ($rankCheck && mysqli_num_rows($rankCheck) > 0);

                // Check if is_teamlead column exists
                $teamleadCheck = mysqli_query($conn, "SHOW COLUMNS FROM tbl_user LIKE 'is_teamlead'");
                $hasTeamleadColumn = ($teamleadCheck && mysqli_num_rows($teamleadCheck) > 0);

                // Build the query with JOIN to get department and designation names
                if ($hasDeptId && $hasDesignationId) {
                    // Query with JOIN to get department and designation names
                    $selectFields = "u.id, u.username, u.firstName, u.lastName, u.rank";

                    if ($hasTeamleadColumn) {
                        $selectFields .= ", u.is_teamlead";
                    }

                    $selectFields .= ", d.department_name AS department, des.designation_name AS designation";

                    $employeeQuery = "SELECT $selectFields FROM tbl_user u
                                      LEFT JOIN tbl_department d ON u.department_id = d.id
                                      LEFT JOIN tbl_designation des ON u.designation_id = des.id
                                      WHERE u.status = '1'";

                    if ($hasRankColumn) {
                        $employeeQuery .= " AND u.rank != 'superadmin'";
                    }

                    // Add employee ID filter
                    if (!empty($filter_employee_id)) {
                        $filter_employee_id = mysqli_real_escape_string($conn, $filter_employee_id);
                        $employeeQuery .= " AND (u.username LIKE '%$filter_employee_id%' OR u.id LIKE '%$filter_employee_id%')";
                    }

                    $employeeQuery .= " ORDER BY u.firstName ASC, u.lastName ASC";
                } else {
                    // Fallback query without JOIN (direct columns)
                    $selectFields = "id, username, firstName, lastName";

                    if ($hasTeamleadColumn) {
                        $selectFields .= ", is_teamlead";
                    }

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

                    if ($hasRankColumn) {
                        $selectFields .= ", rank";
                    }

                    $employeeQuery = "SELECT $selectFields FROM tbl_user WHERE status = '1'";

                    if ($hasRankColumn) {
                        $employeeQuery .= " AND rank != 'superadmin'";
                    }

                    // Add employee ID filter
                    if (!empty($filter_employee_id)) {
                        $filter_employee_id = mysqli_real_escape_string($conn, $filter_employee_id);
                        $employeeQuery .= " AND (username LIKE '%$filter_employee_id%' OR id LIKE '%$filter_employee_id%')";
                    }

                    $employeeQuery .= " ORDER BY firstName ASC, lastName ASC";
                }

                $employeeResult = mysqli_query($conn, $employeeQuery);
                if ($employeeResult && mysqli_num_rows($employeeResult) > 0) {
                    while ($employeeRow = mysqli_fetch_assoc($employeeResult)) {
                        $employees[] = $employeeRow;
                    }
                }

                // Get attendance types
                $attendanceTypes = [];
                $attendanceTypeTableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'tbl_attendance_type'");
                if ($attendanceTypeTableCheck && mysqli_num_rows($attendanceTypeTableCheck) > 0) {
                    $attendanceTypeQuery = "SELECT id, attendance_type, symbol FROM tbl_attendance_type ORDER BY attendance_type ASC";
                    $attendanceTypeResult = mysqli_query($conn, $attendanceTypeQuery);
                    if ($attendanceTypeResult && mysqli_num_rows($attendanceTypeResult) > 0) {
                        while ($attendanceTypeRow = mysqli_fetch_assoc($attendanceTypeResult)) {
                            $attendanceTypes[] = $attendanceTypeRow;
                        }
                    }
                }

                // If no attendance types found, create default ones
                if (empty($attendanceTypes)) {
                    $defaultTypes = [
                        ['id' => 1, 'attendance_type' => 'Present', 'symbol' => 'P'],
                        ['id' => 2, 'attendance_type' => 'Absent', 'symbol' => 'A'],
                        ['id' => 3, 'attendance_type' => 'Half Day', 'symbol' => 'H'],
                        ['id' => 4, 'attendance_type' => 'Leave', 'symbol' => 'L'],
                        ['id' => 5, 'attendance_type' => 'Holiday', 'symbol' => 'HD'],
                        ['id' => 6, 'attendance_type' => 'Week Off', 'symbol' => 'WO']
                    ];
                    $attendanceTypes = $defaultTypes;
                }

                // Get existing attendance for selected month
                $attendanceRegister = [];
                $startDate = sprintf('%04d-%02d-01', $register_year, $register_month);
                $endDate = date('Y-m-t', strtotime($startDate));

                $attendanceQuery = "SELECT id, employee_id, date, attendance_type_id FROM tbl_user_attendance WHERE date BETWEEN '$startDate' AND '$endDate'";
                $attendanceResult = mysqli_query($conn, $attendanceQuery);
                if ($attendanceResult && mysqli_num_rows($attendanceResult) > 0) {
                    while ($attendanceRow = mysqli_fetch_assoc($attendanceResult)) {
                        $employeeId = (int) $attendanceRow['employee_id'];
                        $attendanceDate = $attendanceRow['date'];
                        $attendanceRegister[$employeeId][$attendanceDate] = $attendanceRow['attendance_type_id'];
                    }
                }

                // Get pending leave requests
                $pendingLeaves = [];
                $leaveQuery = "SELECT lr.*, u.firstName, u.lastName, u.username 
                               FROM tbl_leave_requests lr
                               LEFT JOIN tbl_user u ON lr.employee_id = u.id
                               WHERE lr.status = 'pending' 
                               ORDER BY lr.created_at DESC";
                $leaveResult = mysqli_query($conn, $leaveQuery);
                if ($leaveResult && mysqli_num_rows($leaveResult) > 0) {
                    while ($leaveRow = mysqli_fetch_assoc($leaveResult)) {
                        $pendingLeaves[$leaveRow['employee_id']][] = $leaveRow;
                    }
                }

                // Get Team Leads list for modal dropdown
                $teamLeadsList = [];
                if ($hasTeamleadColumn) {
                    $tlQuery = "SELECT id, firstName, lastName, username FROM tbl_user WHERE is_teamlead = 1 AND status = '1'";
                    if ($hasRankColumn) {
                        $tlQuery .= " AND rank != 'superadmin'";
                    }
                    $tlQuery .= " ORDER BY firstName ASC";
                    $tlResult = mysqli_query($conn, $tlQuery);
                    if ($tlResult && mysqli_num_rows($tlResult) > 0) {
                        while ($tlRow = mysqli_fetch_assoc($tlResult)) {
                            $teamLeadsList[] = $tlRow;
                        }
                    }
                }
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
                            <span class="breadcrumb-item active">
                                <i class="bx bx-calendar-check"></i> Attendance Register
                            </span>
                        </div>
                        <!-- Page Title -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold py-3 mb-0">
                                <span class="text-muted fw-light"></span>
                            </h4>
                            <div>
                               
                            </div>
                        </div>

                        <!-- Attendance Register -->
                        <div class="row attendance-register-card">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <div>
                                                <h5 class="mb-1">
                                                    <i class="bx bx-calendar-check me-2"></i>
                                                    Attendance Register —
                                                    <?= htmlspecialchars($register_month_name) ?> <?= $register_year ?>
                                                    <span class="badge bg-primary ms-2">
                                                        <?= count($employees) ?> Employees
                                                    </span>
                                                </h5>

                                                <!-- Legend -->
                                                <div class="legend-container">
                                                    <span class="legend-item">
                                                        <span class="legend-box present">P</span> Present
                                                    </span>
                                                    <span class="legend-item">
                                                        <span class="legend-box absent">A</span> Absent
                                                    </span>
                                                    <span class="legend-item">
                                                        <span class="legend-box half">H</span> Half Day
                                                    </span>
                                                    <span class="legend-item">
                                                        <span class="legend-box leave">L</span> Leave
                                                    </span>
                                                    <span class="legend-item">
                                                        <span class="legend-box holiday">HD</span> Holiday
                                                    </span>
                                                    <span class="legend-item">
                                                        <span class="legend-box weekoff">WO</span> Week Off
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <!-- Month/Year Filter and Employee ID Filter -->
                                        <div class="register-filter">
                                            <form method="GET" action="" id="filterForm">
                                                <div class="filter-row">
                                                    <div class="filter-group">
                                                        <label for="register_month"><i class="bx bx-calendar me-1"></i>
                                                            Month</label>
                                                        <select name="register_month" id="register_month"
                                                            class="form-select"
                                                            onchange="document.getElementById('filterForm').submit()">
                                                            <?php for ($month = 1; $month <= 12; $month++): ?>
                                                                <option value="<?= $month ?>" <?= ($month == $register_month) ? 'selected' : '' ?>>
                                                                    <?= date('F', mktime(0, 0, 0, $month, 1, $register_year)) ?>
                                                                </option>
                                                            <?php endfor; ?>
                                                        </select>
                                                    </div>
                                                    <div class="filter-group">
                                                        <label for="register_year"><i class="bx bx-calendar me-1"></i>
                                                            Year</label>
                                                        <select name="register_year" id="register_year"
                                                            class="form-select"
                                                            onchange="document.getElementById('filterForm').submit()">
                                                            <?php $currentYear = (int) date('Y'); ?>
                                                            <?php for ($year = $currentYear - 5; $year <= $currentYear + 5; $year++): ?>
                                                                <option value="<?= $year ?>" <?= ($year == $register_year) ? 'selected' : '' ?>>
                                                                    <?= $year ?>
                                                                </option>
                                                            <?php endfor; ?>
                                                        </select>
                                                    </div>
                                                    <div class="filter-group">
                                                        <label for="filter_employee_id"><i
                                                                class="bx bx-id-card me-1"></i> Employee ID</label>
                                                        <input type="text" name="filter_employee_id"
                                                            id="filter_employee_id" class="form-control"
                                                            placeholder="Search by ID..."
                                                            value="<?= htmlspecialchars($filter_employee_id) ?>">
                                                    </div>
                                                    <div class="filter-actions">
                                                        <button type="submit" class="btn btn-filter">
                                                            <i class="bx bx-search me-1"></i> Search
                                                        </button>
                                                        <a href="add.php?register_month=<?= $register_month ?>&register_year=<?= $register_year ?>&filter_employee_id="
                                                            class="btn btn-reset-filter">
                                                            <i class="bx bx-refresh me-1"></i> Reset
                                                        </a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Register Form -->
                                        <form method="POST" action="" id="registerForm">
                                            <input type="hidden" name="csrf_token"
                                                value="<?= htmlspecialchars($csrfToken) ?>">
                                            <input type="hidden" name="register_month" value="<?= $register_month ?>">
                                            <input type="hidden" name="register_year" value="<?= $register_year ?>">

                                            <div class="attendance-register-wrapper">
                                                <table class="table attendance-register">
                                                    <thead>
                                                        <tr>
                                                            <th class="employee-column">Employee</th>
                                                            <th class="actions-column">Actions</th>
                                                            <?php for ($day = 1; $day <= $register_total_days; $day++):
                                                                // Calculate day of week
                                                                $dayTimestamp = mktime(0, 0, 0, $register_month, $day, $register_year);
                                                                $dayShortName = date('D', $dayTimestamp); // Mon, Tue, Wed...
                                                                $dayFullName = date('l', $dayTimestamp);   // Monday, Tuesday...
                                                                $dayClass = '';
                                                                if ($dayFullName == 'Sunday') {
                                                                    $dayClass = 'sunday';
                                                                } elseif ($dayFullName == 'Saturday') {
                                                                    $dayClass = 'saturday';
                                                                }
                                                            ?>
                                                                <th class="day-column">
                                                                    <span class="date-num"><?= $day ?></span>
                                                                    <span class="day-label <?= $dayClass ?>"><?= $dayShortName ?></span>
                                                                </th>
                                                            <?php endfor; ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($employees)): ?>
                                                            <?php foreach ($employees as $employee):
                                                                $employeeId = (int) $employee['id'];
                                                                $fullName = trim(($employee['firstName'] ?? '') . ' ' . ($employee['lastName'] ?? ''));
                                                                if (empty($fullName)) {
                                                                    $fullName = $employee['username'] ?? 'Unknown';
                                                                }

                                                                $department = isset($employee['department']) && !empty($employee['department']) ? $employee['department'] : 'N/A';
                                                                $designation = isset($employee['designation']) && !empty($employee['designation']) ? $employee['designation'] : 'N/A';
                                                                $isTeamLead = isset($employee['is_teamlead']) ? (int) $employee['is_teamlead'] : 0;
                                                                $empPendingLeaves = isset($pendingLeaves[$employeeId]) ? $pendingLeaves[$employeeId] : [];
                                                                ?>
                                                                <tr>
                                                                    <td class="employee-column">
                                                                        <div class="employee-info-wrapper">
                                                                            <div class="register-employee-name">
                                                                                <?= htmlspecialchars($fullName) ?>
                                                                                <?php if ($isTeamLead == 1): ?>
                                                                                    <span class="teamlead-badge">
                                                                                        <i class="bx bx-crown me-1"
                                                                                            style="font-size: 10px;"></i> TL
                                                                                    </span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div class="register-employee-id">
                                                                                ID: <span
                                                                                    class="id-value"><?= htmlspecialchars($employee['username'] ?? 'N/A') ?></span>
                                                                            </div>
                                                                            <div class="register-employee-dept">
                                                                                Dept: <span
                                                                                    class="dept-value"><?= htmlspecialchars($department) ?></span>
                                                                            </div>
                                                                            <div class="register-employee-designation">
                                                                                Desg: <span
                                                                                    class="designation-value"><?= htmlspecialchars($designation) ?></span>
                                                                            </div>
                                                                            <?php if (!empty($empPendingLeaves)): ?>
                                                                                <div class="mt-1">
                                                                                    <?php foreach ($empPendingLeaves as $leave): ?>
                                                                                        <span class="leave-status-badge pending me-1">
                                                                                            <i class="bx bx-time me-1"></i>
                                                                                            <?= date('d M', strtotime($leave['start_date'])) ?>
                                                                                            -
                                                                                            <?= date('d M', strtotime($leave['end_date'])) ?>
                                                                                        </span>
                                                                                    <?php endforeach; ?>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </td>
                                                                    <td class="actions-column">
                                                                        <a href="view-attendance.php?id=<?= $employeeId ?>&month=<?= $register_month ?>&year=<?= $register_year ?>"
                                                                            class="action-btn action-btn-view"
                                                                            title="View Attendance">
                                                                            <i class="bx bx-show"></i>
                                                                        </a>
                                                                        <a href="edit-attendance.php?id=<?= $employeeId ?>&month=<?= $register_month ?>&year=<?= $register_year ?>"
                                                                            class="action-btn action-btn-edit"
                                                                            title="Edit Attendance">
                                                                            <i class="bx bx-edit"></i>
                                                                        </a>
                                                                    </td>
                                                                    <?php for ($day = 1; $day <= $register_total_days; $day++):
                                                                        $attendanceDate = sprintf('%04d-%02d-%02d', $register_year, $register_month, $day);
                                                                        $selectedType = isset($attendanceRegister[$employeeId][$attendanceDate]) ? $attendanceRegister[$employeeId][$attendanceDate] : '';
                                                                        ?>
                                                                        <td class="day-column">
                                                                            <select
                                                                                name="register_attendance[<?= $employeeId ?>][<?= $attendanceDate ?>]"
                                                                                class="form-select attendance-register-select attendance-select-<?= $employeeId ?>"
                                                                                data-value="<?= $selectedType ?>"
                                                                                data-employee-id="<?= $employeeId ?>"
                                                                                data-employee-name="<?= htmlspecialchars($fullName) ?>"
                                                                                data-date="<?= $attendanceDate ?>">
                                                                                <option value="">-</option>
                                                                                <?php foreach ($attendanceTypes as $type):
                                                                                    $typeId = (int) $type['id'];
                                                                                    $typeSymbol = !empty($type['symbol']) ? $type['symbol'] : $type['attendance_type'];
                                                                                    $isSelected = ((string) $selectedType === (string) $typeId) ? 'selected' : '';
                                                                                    ?>
                                                                                    <option value="<?= $typeId ?>" <?= $isSelected ?>>
                                                                                        <?= htmlspecialchars($typeSymbol) ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </td>
                                                                    <?php endfor; ?>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="<?= $register_total_days + 2 ?>"
                                                                    class="text-center py-4">
                                                                    <span class="text-muted">No employees found.</span>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="mt-3 d-flex gap-2 flex-wrap">
                                                <button type="submit" name="register_submit" class="btn btn-primary">
                                                    <i class="bx bx-save me-1"></i> Save Register
                                                </button>
                                                <!-- <button type="button" class="btn btn-outline-secondary"
                                                    onclick="selectAllAttendance()">
                                                    <i class="bx bx-check-all me-1"></i> Mark All Present
                                                </button>
                                                <button type="button" class="btn btn-outline-danger"
                                                    onclick="clearAllAttendance()">
                                                    <i class="bx bx-eraser me-1"></i> Clear All
                                                </button> -->
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <?php include('../includes/footer.php'); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Leave Approval Modal -->
    <div class="modal fade" id="leaveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-time me-2"></i> Leave Request Approval
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-semibold">Employee:</label>
                        <p id="leaveEmployeeName" class="mb-0 text-muted">-</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-semibold">Leave Date:</label>
                        <p id="leaveDate" class="mb-0 text-muted">-</p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-semibold">Reason:</label>
                        <p id="leaveReason" class="mb-0 text-muted">-</p>
                    </div>
                    <div class="alert alert-info mt-2">
                        <i class="bx bx-info-circle me-1"></i>
                        Please select a Team Lead to approve this leave:
                    </div>
                    <div class="mb-3">
                        <label class="fw-semibold">Team Lead:</label>
                        <select id="teamLeadSelect" class="form-select mt-1">
                            <option value="">Select Team Lead</option>
                            <?php if (!empty($teamLeadsList)): ?>
                                <?php foreach ($teamLeadsList as $tl): ?>
                                    <option value="<?= $tl['id'] ?>">
                                        <?= htmlspecialchars($tl['firstName'] . ' ' . $tl['lastName']) ?>
                                        (<?= htmlspecialchars($tl['username']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>No Team Leads available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <!-- Hidden fields -->
                    <input type="hidden" id="currentEmployeeId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="" id="leaveActionForm">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <input type="hidden" name="leave_id" id="leaveIdInput" value="0">
                        <input type="hidden" name="leave_action" id="leaveActionInput" value="approve">
                        <input type="hidden" name="leave_tl_id" id="leaveTlIdInput" value="">
                        <input type="hidden" name="leave_employee_id" id="leaveEmployeeIdInput" value="">
                        <input type="hidden" name="leave_date" id="leaveDateInput" value="">
                        <input type="hidden" name="leave_attendance_type" id="leaveAttendanceTypeInput" value="4">
                        <button type="submit" class="btn btn-approve" id="leaveActionBtn">
                            <i class="bx bx-check-circle me-1"></i> Approve
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <?php include('../includes/script.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

    <script>
        // Global flag to prevent multiple modals
        let isLeaveModalOpen = false;
        // Flag to prevent modal on page refresh (set by PHP)
        let leaveApproved = <?= $leaveApproved ?>;

        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        // Close sidebar on mobile when clicking outside
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

        // Update attendance select color - with page load protection
        function updateAttendanceSelect(select, isPageLoad) {
            // Remove all classes first
            select.classList.remove(
                'attendance-present',
                'attendance-absent',
                'attendance-half',
                'attendance-leave',
                'attendance-holiday',
                'attendance-weekoff'
            );

            const value = select.value;

            if (value == '1') {
                select.classList.add('attendance-present');
            } else if (value == '2') {
                select.classList.add('attendance-absent');
            } else if (value == '3') {
                select.classList.add('attendance-half');
            } else if (value == '4') {
                select.classList.add('attendance-leave');
                // Show Leave Approval Popup - only if not page load and not already open
                if (!isPageLoad && !leaveApproved) {
                    showLeaveApprovalPopup(select);
                } else if (leaveApproved) {
                    // If leave was approved, reset the dropdown
                    select.value = '';
                    select.classList.remove('attendance-leave');
                }
            } else if (value == '5') {
                select.classList.add('attendance-holiday');
            } else if (value == '6') {
                select.classList.add('attendance-weekoff');
            }
        }

        // Show Leave Approval Popup - FIXED: Only opens once and not on refresh
        function showLeaveApprovalPopup(select) {
            // Check if modal is already open or leave was just approved
            if (isLeaveModalOpen || leaveApproved == 1) {
                // Reset the dropdown value if leave was approved
                if (leaveApproved == 1) {
                    select.value = '';
                    select.classList.remove('attendance-leave');
                }
                return;
            }

            const employeeId = select.getAttribute('data-employee-id');
            const employeeName = select.getAttribute('data-employee-name');
            const date = select.getAttribute('data-date');

            // Format date
            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('en-US', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });

            // Set modal data
            document.getElementById('leaveEmployeeName').textContent = employeeName;
            document.getElementById('leaveDate').textContent = formattedDate;
            document.getElementById('leaveReason').textContent = 'Leave request submitted for ' + formattedDate;
            document.getElementById('leaveIdInput').value = 0;
            document.getElementById('leaveActionInput').value = 'approve';
            document.getElementById('leaveEmployeeIdInput').value = employeeId;
            document.getElementById('leaveDateInput').value = date;
            document.getElementById('currentEmployeeId').value = employeeId;

            // Reset TL selection
            document.getElementById('teamLeadSelect').value = '';

            // Set flag to prevent multiple modals
            isLeaveModalOpen = true;

            // Reset the dropdown value to empty to prevent re-triggering
            select.value = '';
            select.classList.remove('attendance-leave');

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('leaveModal'));
            modal.show();

            // Reset flag when modal is hidden
            document.getElementById('leaveModal').addEventListener('hidden.bs.modal', function () {
                isLeaveModalOpen = false;
            });
        }

        // Mark All Present
        function selectAllAttendance() {
            if (!confirm('Mark all employees as Present for all days?')) return;

            const selects = document.querySelectorAll('.attendance-register-select');
            selects.forEach(function (select) {
                select.value = '1';
                updateAttendanceSelect(select, false);
            });

            iziToast.info({
                title: 'Info',
                message: 'All attendance marked as Present',
                position: 'topRight'
            });
        }

        // Clear All Attendance
        function clearAllAttendance() {
            if (!confirm('Clear all attendance selections?')) return;

            const selects = document.querySelectorAll('.attendance-register-select');
            selects.forEach(function (select) {
                select.value = '';
                updateAttendanceSelect(select, false);
            });

            iziToast.info({
                title: 'Info',
                message: 'All attendance cleared',
                position: 'topRight'
            });
        }

        // Validate Team Lead selection and prevent self-approval
        document.addEventListener('DOMContentLoaded', function () {
            // Show toast messages from session (if any)
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

            <?php if (isset($_SESSION['toast_warning'])): ?>
                iziToast.warning({
                    title: 'Warning',
                    message: '<?= addslashes($_SESSION['toast_warning']) ?>',
                    position: 'topRight'
                });
                <?php unset($_SESSION['toast_warning']); ?>
            <?php endif; ?>

            const leaveForm = document.getElementById('leaveActionForm');
            if (leaveForm) {
                leaveForm.addEventListener('submit', function (e) {
                    const tlSelect = document.getElementById('teamLeadSelect');
                    const tlValue = tlSelect.value;
                    const employeeId = document.getElementById('currentEmployeeId').value;

                    if (!tlValue) {
                        e.preventDefault();
                        iziToast.warning({
                            title: 'Warning',
                            message: 'Please select a Team Lead to approve this leave.',
                            position: 'topRight'
                        });
                        tlSelect.focus();
                        return false;
                    }

                    // Check if employee is trying to approve their own leave
                    if (tlValue == employeeId) {
                        e.preventDefault();
                        iziToast.error({
                            title: 'Error',
                            message: 'You cannot approve your own leave request. Please select another Team Lead.',
                            position: 'topRight'
                        });
                        tlSelect.focus();
                        return false;
                    }

                    document.getElementById('leaveTlIdInput').value = tlValue;
                });
            }

            // Register Save Confirmation
            const registerForm = document.getElementById('registerForm');
            if (registerForm) {
                registerForm.addEventListener('submit', function (event) {
                    const selects = registerForm.querySelectorAll('.attendance-register-select');
                    let hasAttendance = false;
                    selects.forEach(function (select) {
                        if (select.value !== '') hasAttendance = true;
                    });
                    if (!hasAttendance) {
                        event.preventDefault();
                        iziToast.warning({
                            title: 'Warning',
                            message: 'Please select at least one attendance status',
                            position: 'topRight'
                        });
                        return false;
                    }
                });
            }

            // Initialize all attendance selects with colors - skip modal on page load
            const registerSelects = document.querySelectorAll('.attendance-register-select');
            registerSelects.forEach(function (select) {
                // Set initial color - pass true for page load
                updateAttendanceSelect(select, true);

                // Add change event listener
                select.addEventListener('change', function () {
                    updateAttendanceSelect(this, false);
                });
            });

            // Auto-submit filter on Enter key in employee ID field
            const filterInput = document.querySelector('input[name="filter_employee_id"]');
            if (filterInput) {
                filterInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('filterForm').submit();
                    }
                });
            }

            // Real-time validation on Team Lead dropdown change
            document.getElementById('teamLeadSelect').addEventListener('change', function () {
                const tlValue = this.value;
                const employeeId = document.getElementById('currentEmployeeId').value;

                // Remove existing warning
                const existingWarning = document.getElementById('selfApprovalWarning');
                if (existingWarning) {
                    existingWarning.remove();
                }

                if (tlValue && tlValue == employeeId) {
                    // Show warning
                    const alertDiv = document.createElement('div');
                    alertDiv.id = 'selfApprovalWarning';
                    alertDiv.className = 'alert alert-danger mt-2';
                    alertDiv.innerHTML = '<i class="bx bx-error-circle me-1"></i> You cannot approve your own leave request. Please select another Team Lead.';

                    // Insert after the Team Lead select
                    const tlSelectDiv = this.closest('.mb-3');
                    tlSelectDiv.parentNode.insertBefore(alertDiv, tlSelectDiv.nextSibling);

                    // Disable approve button
                    document.getElementById('leaveActionBtn').disabled = true;
                    document.getElementById('leaveActionBtn').style.opacity = '0.5';
                } else {
                    // Enable approve button
                    document.getElementById('leaveActionBtn').disabled = false;
                    document.getElementById('leaveActionBtn').style.opacity = '1';
                }
            });
        });
    </script>

</body>

</html>