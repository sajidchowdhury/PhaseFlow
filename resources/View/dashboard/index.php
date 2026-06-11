<?php
/**
 * Dashboard Content - PhaseFlow CRM
 */
?>

<!-- Dashboard Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="section-header mb-1">Good evening, Sajid 👋</h1>
        <p class="text-muted mb-0">Here's what's happening with your client pipeline today.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-premium btn-premium d-flex align-items-center gap-2" onclick="showNewClientModal()">
            <i class="bi bi-person-plus"></i>
            <span>Add Targeted Client</span>
        </button>
        <button class="btn btn-teal btn-premium d-flex align-items-center gap-2 shadow-sm" onclick="window.location.href='/pipeline'">
            <i class="bi bi-kanban"></i>
            <span>Go to Pipeline</span>
        </button>
    </div>
</div>

<!-- KPI Cards Row -->
<div class="row g-3 mb-4">
    <!-- KPI 1 -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="premium-card kpi-card h-100">
            <div class="kpi-icon" style="background: #E0F2FE; color: #0369A1;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="kpi-value">187</div>
            <div class="kpi-label d-flex justify-content-between align-items-center">
                <span>Total Clients</span>
                <span class="trend-up small"><i class="bi bi-arrow-up"></i> 14</span>
            </div>
            <div class="mt-1"><span class="badge status-lead">142 Targeted • 45 Real</span></div>
        </div>
    </div>

    <!-- KPI 2 -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="premium-card kpi-card h-100">
            <div class="kpi-icon" style="background: #D1FAE5; color: #065F46;">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="kpi-value">৳48.2L</div>
            <div class="kpi-label d-flex justify-content-between align-items-center">
                <span>Pipeline Value</span>
                <span class="trend-up small"><i class="bi bi-arrow-up"></i> 22%</span>
            </div>
            <div class="mt-1 small text-success fw-medium">12 quotations pending acceptance</div>
        </div>
    </div>

    <!-- KPI 3 -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="premium-card kpi-card h-100">
            <div class="kpi-icon" style="background: #FEF3C7; color: #92400E;">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <div class="kpi-value">68%</div>
            <div class="kpi-label">Conversion Rate</div>
            <div class="progress mt-2" style="height: 6px;">
                <div class="progress-bar bg-warning" style="width: 68%"></div>
            </div>
        </div>
    </div>

    <!-- KPI 4 -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="premium-card kpi-card h-100">
            <div class="kpi-icon" style="background: #DBEAFE; color: #1E40AF;">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
            <div class="kpi-value">৳19.8L</div>
            <div class="kpi-label d-flex justify-content-between">
                <span>Revenue (This Month)</span>
            </div>
            <div class="mt-1 small"><span class="text-success fw-semibold">+৳4.2L</span> vs last month</div>
        </div>
    </div>

    <!-- KPI 5 -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="premium-card kpi-card h-100">
            <div class="kpi-icon" style="background: #FCE7F3; color: #9D174D;">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="kpi-value">23</div>
            <div class="kpi-label">Open Tickets</div>
            <div class="mt-1"><span class="badge bg-danger-subtle text-danger">4 Critical</span></div>
        </div>
    </div>

    <!-- KPI 6 -->
    <div class="col-6 col-md-4 col-xl-2">
        <div class="premium-card kpi-card h-100">
            <div class="kpi-icon" style="background: #E0E7FF; color: #3730A3;">
                <i class="bi bi-star-fill"></i>
            </div>
            <div class="kpi-value">14</div>
            <div class="kpi-label">Reviews Due (30d)</div>
            <div class="mt-1 small text-primary fw-medium">8 renewal quotations ready</div>
        </div>
    </div>
</div>

<!-- Pipeline Overview + Activity -->
<div class="row g-4">
    
    <!-- Pipeline Funnel Overview -->
    <div class="col-lg-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="section-header mb-0">Pipeline Overview</h5>
            <a href="/pipeline" class="text-teal fw-medium small text-decoration-none d-flex align-items-center gap-1">
                View Full Board <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        
        <div class="premium-card p-4">
            <div class="row g-3">
                <div class="col-6 col-md-4 col-xl-2-4">
                    <div class="kanban-column-preview p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold small">Phase 1 • Targeted</span>
                            <span class="badge bg-info-subtle text-info">87</span>
                        </div>
                        <div class="small text-muted">৳18.4L potential</div>
                        <div class="mt-3 d-flex flex-wrap gap-1">
                            <span class="badge status-lead">Fresh Leads</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2-4">
                    <div class="kanban-column-preview p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold small">Phase 2 • Quotation</span>
                            <span class="badge bg-warning-subtle text-warning">34</span>
                        </div>
                        <div class="small text-muted">৳12.7L in quotes</div>
                        <div class="mt-3"><span class="text-warning small fw-medium">19 awaiting reply</span></div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2-4">
                    <div class="kanban-column-preview p-3 border-teal">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold small">Phase 3 • Agreed</span>
                            <span class="badge bg-success-subtle text-success">29</span>
                        </div>
                        <div class="small text-muted">৳9.8L confirmed</div>
                        <div class="mt-2"><span class="badge status-agreed">In Polish</span></div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2-4">
                    <div class="kanban-column-preview p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold small">Phase 4 • Delivered</span>
                            <span class="badge bg-primary-subtle text-primary">22</span>
                        </div>
                        <div class="small text-muted">Warranty active</div>
                        <div class="mt-2 small text-danger fw-medium">7 ending soon</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl-2-4">
                    <div class="kanban-column-preview p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold small">Phase 5 • Review</span>
                            <span class="badge bg-pink-subtle text-pink">15</span>
                        </div>
                        <div class="small text-muted">Yearly cycle</div>
                        <div class="mt-2"><span class="badge status-review">8 due this month</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="col-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="section-header mb-0">Recent Activity</h5>
            <a href="#" class="text-teal small fw-medium text-decoration-none">View all</a>
        </div>
        <div class="premium-card p-3" style="max-height: 310px; overflow-y: auto;">
            <div class="d-flex gap-3 mb-3">
                <div class="flex-shrink-0"><i class="bi bi-check2-circle text-success fs-5 mt-1"></i></div>
                <div class="flex-grow-1 small">
                    <div><strong>DeshiMart Ltd</strong> accepted quotation and moved to Phase 3</div>
                    <div class="text-muted" style="font-size:0.75rem">Today • 4:12 PM • by You</div>
                </div>
            </div>
            <div class="d-flex gap-3 mb-3">
                <div class="flex-shrink-0"><i class="bi bi-file-earmark-text text-teal fs-5 mt-1"></i></div>
                <div class="flex-grow-1 small">
                    <div>Quotation <strong>#QT-2841</strong> sent to Sunrise Pharmacy</div>
                    <div class="text-muted" style="font-size:0.75rem">Today • 11:40 AM</div>
                </div>
            </div>
            <div class="d-flex gap-3 mb-3">
                <div class="flex-shrink-0"><i class="bi bi-exclamation-triangle text-danger fs-5 mt-1"></i></div>
                <div class="flex-grow-1 small">
                    <div>New <strong>Critical</strong> ticket opened for Inventory v2.3 (Rahim Traders)</div>
                    <div class="text-muted" style="font-size:0.75rem">Yesterday • Assigned to Rafiq</div>
                </div>
            </div>
            <div class="d-flex gap-3">
                <div class="flex-shrink-0"><i class="bi bi-star text-warning fs-5 mt-1"></i></div>
                <div class="flex-grow-1 small">
                    <div>5-star review received from <strong>City Hospital</strong></div>
                    <div class="text-muted" style="font-size:0.75rem">2 days ago • Published on website</div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Quick Actions -->
<div class="row g-4 mt-1">
    <div class="col-12">
        <div class="premium-card p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h6 class="mb-1 fw-semibold">Today's Priorities</h6>
                    <p class="text-muted small mb-0">3 quotations need follow-up • 2 warranty periods ending this week • 4 reviews to collect</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-premium btn-premium">Follow-up Queue</button>
                    <button class="btn btn-teal btn-premium" onclick="showNewClientModal()">Add New Lead</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Overview with Charts -->
<div class="mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="section-header mb-0">Analytics Overview</h5>
        <small class="text-muted">Last 30 days</small>
    </div>

    <div class="row g-4">
        
        <!-- Chart 1: Pipeline Value by Phase -->
        <div class="col-lg-6">
            <div class="premium-card p-4 h-100">
                <h6 class="fw-semibold mb-3">Pipeline Value by Phase</h6>
                <canvas id="pipelineValueChart" height="180"></canvas>
            </div>
        </div>

        <!-- Chart 2: Monthly Revenue Trend -->
        <div class="col-lg-6">
            <div class="premium-card p-4 h-100">
                <h6 class="fw-semibold mb-3">Monthly Revenue Trend</h6>
                <canvas id="revenueTrendChart" height="180"></canvas>
            </div>
        </div>

        <!-- Chart 3: Ticket Status Distribution -->
        <div class="col-lg-6">
            <div class="premium-card p-4 h-100">
                <h6 class="fw-semibold mb-3">Ticket Status Distribution</h6>
                <canvas id="ticketStatusChart" height="180"></canvas>
            </div>
        </div>

        <!-- Chart 4: Conversion Funnel -->
        <div class="col-lg-6">
            <div class="premium-card p-4 h-100">
                <h6 class="fw-semibold mb-3">Pipeline Conversion Funnel</h6>
                <canvas id="conversionFunnelChart" height="180"></canvas>
            </div>
        </div>

    </div>
</div>

<!-- New Client Modal -->
<div class="modal fade" id="newClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Targeted Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newClientForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="e.g. Md. Karim Hossain" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Organization Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="e.g. Karim Traders Ltd." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" placeholder="01711-XXXXXX" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Email Address</label>
                            <input type="email" class="form-control" placeholder="karim@company.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Address</label>
                            <textarea class="form-control" rows="2" placeholder="House #, Road, Area, City"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">How did you find this lead?</label>
                            <select class="form-select">
                                <option>Facebook / Social Media</option>
                                <option>Referral from existing client</option>
                                <option>Website inquiry</option>
                                <option>Direct call / Walk-in</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Interested In</label>
                            <select class="form-select" multiple>
                                <option>Inventory Software</option>
                                <option>Pharmacy Management</option>
                                <option>Inventory with Expiry Tracking</option>
                                <option>Custom Web Application</option>
                                <option>Hosting & Domain</option>
                                <option>Yearly Maintenance</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-outline-secondary btn-premium" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-teal btn-premium" onclick="createNewClient()">Add to Phase 1 • Targeted</button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
function showNewClientModal() {
    const modal = new bootstrap.Modal(document.getElementById('newClientModal'));
    modal.show();
}

function createNewClient() {
    const form = document.getElementById('newClientForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const modalEl = document.getElementById('newClientModal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();

    showToast('Success!', 'New client added to Phase 1 • Targeted. They will appear in the Pipeline Board.', 'success');
}

// ==================== CHARTS INITIALIZATION ====================
function initDashboardCharts() {
    // Chart 1: Pipeline Value by Phase (Horizontal Bar)
    const pipelineCtx = document.getElementById('pipelineValueChart');
    if (pipelineCtx) {
        new Chart(pipelineCtx, {
            type: 'bar',
            data: {
                labels: ['Phase 1 - Targeted', 'Phase 2 - Quotation', 'Phase 3 - Agreed', 'Phase 4 - Delivered', 'Phase 5 - Review'],
                datasets: [{
                    label: 'Value (৳)',
                    data: [1840000, 1270000, 980000, 1450000, 620000],
                    backgroundColor: ['#0EA5E9', '#F59E0B', '#10B981', '#3B82F6', '#9D174D'],
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { color: '#e2e8f0' } }, y: { grid: { display: false } } }
            }
        });
    }

    // Chart 2: Monthly Revenue Trend (Line)
    const revenueCtx = document.getElementById('revenueTrendChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue (৳ Lakhs)',
                    data: [12.5, 18.2, 15.8, 22.4, 19.6, 24.8],
                    borderColor: '#0D9488',
                    backgroundColor: 'rgba(13, 148, 136, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#e2e8f0' } },
                    x: { grid: { color: '#e2e8f0' } }
                }
            }
        });
    }

    // Chart 3: Ticket Status Distribution (Doughnut)
    const ticketCtx = document.getElementById('ticketStatusChart');
    if (ticketCtx) {
        new Chart(ticketCtx, {
            type: 'doughnut',
            data: {
                labels: ['Open', 'In Progress', 'Resolved', 'Closed'],
                datasets: [{
                    data: [8, 5, 12, 18],
                    backgroundColor: ['#EF4444', '#F59E0B', '#10B981', '#64748B'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } }
                }
            }
        });
    }

    // Chart 4: Conversion Funnel (Horizontal Bar)
    const funnelCtx = document.getElementById('conversionFunnelChart');
    if (funnelCtx) {
        new Chart(funnelCtx, {
            type: 'bar',
            data: {
                labels: ['Targeted', 'Quotation Sent', 'Accepted', 'Delivered', 'Reviewed'],
                datasets: [{
                    label: 'Clients',
                    data: [87, 61, 42, 29, 18],
                    backgroundColor: ['#0EA5E9', '#F59E0B', '#10B981', '#3B82F6', '#9D174D'],
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, grid: { color: '#e2e8f0' } }, y: { grid: { display: false } } }
            }
        });
    }
}

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit to ensure canvas elements are ready
    setTimeout(() => {
        initDashboardCharts();
    }, 300);
});
</script>