<nav class="topbar navbar navbar-expand-lg border-bottom">
    <div class="container-fluid px-4">
        <button class="btn btn-outline-secondary d-lg-none me-3" 
                id="sidebarToggle" 
                aria-label="Toggle navigation menu"
                aria-expanded="false"
                aria-controls="sidebar">
            <i class="bi bi-list fs-4"></i>
        </button>

        <a class="navbar-brand d-lg-none fw-bold text-dark" href="#">Phase<span class="text-teal">Flow</span></a>

 
        <!-- Right side actions -->
        <div class="d-flex align-items-center gap-2 gap-lg-3 ms-auto">
            <!-- Quick Create Dropdown -->
            <div class="dropdown">
                <button class="btn btn-premium btn-teal d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="dropdown">
                    <i class="bi bi-plus-lg"></i>
                    <span class="d-none d-sm-inline">Quick Create</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-2">
                    <li><a class="dropdown-item py-2 px-3" href="/clients?action=create"><i class="bi bi-person-plus me-2"></i> New Client</a></li>
                    <li><a class="dropdown-item py-2 px-3" href="#"><i class="bi bi-folder-plus me-2"></i> New Project</a></li>
                    <li><a class="dropdown-item py-2 px-3" href="#"><i class="bi bi-file-earmark-text me-2"></i> New Quotation</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item py-2 px-3" href="#"><i class="bi bi-ticket-perforated me-2"></i> Log Support Ticket</a></li>
                </ul>
            </div>

            <!-- Notifications -->
            <div class="dropdown">
                <button class="btn btn-light position-relative notification-bell p-2 rounded-circle border" data-bs-toggle="dropdown">
                    <i class="bi bi-bell fs-5 text-muted"></i>
                    <span class="notification-dot"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3" style="width: 340px;">
                    <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Notifications</span>
                        <span class="badge bg-teal text-white rounded-pill px-2 py-1" style="font-size:0.7rem">12 new</span>
                    </div>
                    <div class="py-1" style="max-height: 320px; overflow-y: auto;">
                        <a href="#" class="dropdown-item px-3 py-2.5 d-flex gap-3">
                            <div class="flex-shrink-0"><i class="bi bi-check2-circle text-success fs-4"></i></div>
                            <div>
                                <div class="fw-medium">Client "Rahim Traders" moved to Phase 3</div>
                                <small class="text-muted">2 minutes ago • Pipeline</small>
                            </div>
                        </a>
                    </div>
                    <div class="border-top px-3 py-2 text-center">
                        <a href="#" class="text-teal fw-medium small text-decoration-none">View all notifications</a>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown">
                    <img src="https://i.pravatar.cc/36?img=68" alt="User Avatar" class="rounded-circle border" width="36" height="36">
                    <div class="d-none d-md-block ms-2">
                        <div class="fw-semibold text-dark small">Sajid Rahman</div>
                        <div class="text-muted" style="font-size: 0.7rem; line-height:1;">Admin • Founder</div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                    <li>
                        <a class="dropdown-item d-flex justify-content-between align-items-center" href="#" onclick="toggleDarkMode(event)">
                            <span><i class="bi bi-moon me-2"></i> Dark Mode</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="darkModeToggle" onchange="toggleDarkMode(event)">
                            </div>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>