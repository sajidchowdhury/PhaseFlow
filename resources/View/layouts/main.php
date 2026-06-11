<?php
/**
 * Master Layout - PhaseFlow CRM
 * সব পেজ এই লেআউট ব্যবহার করবে
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'PhaseFlow CRM' ?> • PhaseFlow</title>
    
    <!-- Bootstrap 5.3.3 Latest -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom Premium Styles -->
    <style>
        :root {
            --primary-navy: #0F172A;
            --accent-teal: #0D9488;
            --success-green: #10B981;
            --warning-amber: #F59E0B;
            --danger-red: #EF4444;
            --light-bg: #F8FAFC;
            --card-bg: #FFFFFF;
            --text-primary: #1E293B;
            --text-secondary: #64748B;
            --border-color: #E2E8F0;
            --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.08);
            --shadow-md: 0 4px 12px rgba(15, 23, 42, 0.1);
            --radius-lg: 16px;
            --radius-md: 12px;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--text-primary);
            font-size: 15px;
            line-height: 1.6;
        }

        /* Premium Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--primary-navy);
            color: #CBD5E1;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link {
            color: #CBD5E1;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            margin: 0.15rem 0.75rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.08);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background-color: var(--accent-teal);
            color: #fff;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
        }

        .sidebar .nav-link i {
            width: 22px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand .logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .sidebar-brand .logo span {
            color: var(--accent-teal);
        }

        /* Topbar */
        .topbar {
            background: #fff;
            box-shadow: var(--shadow-sm);
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .topbar .search-input {
            background: var(--light-bg);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            padding-left: 2.75rem;
            transition: all 0.2s;
        }

        .topbar .search-input:focus {
            border-color: var(--accent-teal);
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
        }

        .notification-bell {
            position: relative;
        }

        .notification-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 9px;
            height: 9px;
            background: var(--danger-red);
            border-radius: 50%;
            border: 2px solid #fff;
        }

        /* Premium Cards */
        .premium-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), 
                        box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .premium-card:active {
            transform: translateY(-1px);
        }

        .kpi-card {
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .kpi-card .kpi-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.65rem;
            margin-bottom: 1rem;
        }

        .kpi-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.1;
            color: var(--primary-navy);
        }

        .kpi-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .trend-up { color: var(--success-green); font-weight: 600; }
        .trend-down { color: var(--danger-red); font-weight: 600; }

        /* Status Badges */
        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
        }

        .status-lead { background: #E0F2FE; color: #0369A1; }
        .status-quotation { background: #FEF3C7; color: #92400E; }
        .status-agreed { background: #D1FAE5; color: #065F46; }
        .status-delivered { background: #E0E7FF; color: #3730A3; }
        .status-review { background: #FCE7F3; color: #9D174D; }

        /* Section Headers */
        .section-header {
            font-weight: 700;
            color: var(--primary-navy);
            letter-spacing: -0.3px;
        }

        /* Modern Tables */
        .modern-table {
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .modern-table thead {
            background: #F1E7FF;
        }

        .modern-table th {
            font-weight: 600;
            color: var(--primary-navy);
            border-bottom: none;
            padding: 1rem 1.25rem;
        }

        .modern-table td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        /* Buttons */
        .btn-premium {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-teal {
            background: var(--accent-teal);
            color: #fff;
            border: none;
        }

        .btn-teal:hover {
            background: #0F766E;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.3);
        }

        .btn-teal:active {
            transform: scale(0.98);
        }

        .btn-outline-premium:hover {
            background: #f8fafc;
            border-color: #0D9488;
            color: #0D9488;
        }

        .btn-outline-premium {
            border: 1.5px solid var(--border-color);
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Responsive Sidebar */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
            }
            
            /* Mobile backdrop when sidebar is open */
            body.sidebar-open::after {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1025;
            }
        }

        /* Better touch targets on mobile */
        @media (max-width: 768px) {
            .nav-link {
                padding: 0.9rem 1.25rem !important;
                font-size: 1rem;
            }
            
            .btn-premium {
                padding: 0.75rem 1.25rem;
                font-size: 0.95rem;
            }
            
            .premium-card {
                padding: 1.25rem !important;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
        }

        /* ==================== ACCESSIBILITY ==================== */
        /* Visible focus states for keyboard navigation */
        a:focus-visible,
        button:focus-visible,
        .nav-link:focus-visible,
        .form-control:focus-visible,
        .form-select:focus-visible {
            outline: 3px solid #0D9488;
            outline-offset: 2px;
        }

        /* Skip to main content link (for screen readers & keyboard) */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #0D9488;
            color: white;
            padding: 8px 16px;
            z-index: 9999;
            transition: top 0.2s;
        }
        
        .skip-link:focus {
            top: 0;
        }

        .main-content {
            margin-left: 260px;
            transition: margin-left 0.3s ease;
        }

        .fade-in {
            animation: fadeInUp 0.4s ease forwards;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-content {
            border-radius: var(--radius-lg);
            border: none;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.2);
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.65rem 1rem;
            border-color: var(--border-color);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-teal);
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
        }

        /* ==================== DARK MODE ==================== */
        [data-theme="dark"] {
            --light-bg: #0F172A;
            --card-bg: #1E293B;
            --text-primary: #F1E7FF;
            --text-secondary: #94A3B8;
            --border-color: #334155;
        }

        [data-theme="dark"] .topbar {
            background: #1E293B;
            border-color: #334155;
        }

        [data-theme="dark"] .premium-card {
            background: #1E293B;
            border-color: #334155;
        }

        [data-theme="dark"] .sidebar {
            background: #0F172A;
        }

        [data-theme="dark"] .section-header {
            color: #F1E7FF;
        }

        [data-theme="dark"] .text-muted {
            color: #64748B !important;
        }

        [data-theme="dark"] .modern-table thead {
            background: #334155;
        }

        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background: #1E293B;
            border-color: #475569;
            color: #F1E7FF;
        }
    </style>
</head>
<body>

    <!-- Topbar -->
    <?php include __DIR__ . '/../partials/topbar.php'; ?>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content p-4 p-lg-5">
        <?= $content ?? '' ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Global Scripts -->
    <script>
        // Mobile Sidebar Toggle with Backdrop
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                const isOpen = sidebar.classList.toggle('show');
                
                // Update ARIA attributes
                sidebarToggle.setAttribute('aria-expanded', isOpen);
                
                // Add/remove backdrop class on body for mobile
                if (window.innerWidth < 992) {
                    if (isOpen) {
                        document.body.classList.add('sidebar-open');
                    } else {
                        document.body.classList.remove('sidebar-open');
                    }
                }
            });
        }

        // Close sidebar on mobile when clicking outside or on backdrop
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(e.target) && sidebarToggle && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                    document.body.classList.remove('sidebar-open');
                }
            }
        });

        // Reusable Toast
        function showToast(title, message, type = 'success') {
            const toastContainer = document.createElement('div');
            toastContainer.style.cssText = 'position:fixed; bottom:20px; right:20px; z-index:9999';
            
            const icon = type === 'success' ? 'bi-check-circle-fill text-success' : 'bi-info-circle-fill text-primary';
            
            toastContainer.innerHTML = `
                <div class="toast show align-items-center border-0 shadow-lg" role="alert" style="min-width:320px; border-radius:14px;">
                    <div class="d-flex">
                        <div class="toast-body d-flex gap-3 py-3 px-3">
                            <i class="bi ${icon} fs-4 mt-1"></i>
                            <div>
                                <div class="fw-semibold">${title}</div>
                                <div class="small text-muted">${message}</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(toastContainer);
            
            // Improved toast animation
            setTimeout(() => {
                toastContainer.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                toastContainer.style.opacity = '0';
                toastContainer.style.transform = 'translateY(20px)';
                setTimeout(() => toastContainer.remove(), 300);
            }, 4200);
        }

        // ==================== DARK MODE ====================
        function toggleDarkMode(e) {
            if (e) e.preventDefault();
            
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update toggle checkbox if it exists
            const toggle = document.getElementById('darkModeToggle');
            if (toggle) toggle.checked = (newTheme === 'dark');
        }

        // Initialize theme on page load
        function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            
            // Set initial state of toggle
            setTimeout(() => {
                const toggle = document.getElementById('darkModeToggle');
                if (toggle) toggle.checked = (savedTheme === 'dark');
            }, 100);
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            initTheme();
        });
    </script>
</body>
</html>