<?php
/**
 * Reports Module - PhaseFlow CRM (Phase 4D)
 * Outstanding Receivables + Simple P&L + Cash Flow Summary
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="section-header mb-1">Reports & Analytics</h1>
        <p class="text-muted mb-0">Financial overview and outstanding payments</p>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="premium-card p-4 text-center">
            <div class="text-muted small">Outstanding Receivables</div>
            <div class="fs-2 fw-bold text-danger" id="outstandingAmount">৳0</div>
            <div class="small text-muted" id="outstandingCount">0 invoices</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="premium-card p-4 text-center">
            <div class="text-muted small">This Month Income</div>
            <div class="fs-2 fw-bold text-success" id="monthlyIncome">৳0</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="premium-card p-4 text-center">
            <div class="text-muted small">This Month Expense</div>
            <div class="fs-2 fw-bold text-danger" id="monthlyExpense">৳0</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="premium-card p-4 text-center border border-teal">
            <div class="text-muted small">Net Profit (This Month)</div>
            <div class="fs-2 fw-bold text-teal" id="monthlyProfit">৳0</div>
        </div>
    </div>
</div>

<div class="row g-4">
    
    <!-- Outstanding Receivables -->
    <div class="col-lg-7">
        <div class="premium-card p-4 h-100">
            <h5 class="fw-semibold mb-3">Outstanding Receivables</h5>
            <div id="outstandingList">
                <!-- Populated by JS -->
            </div>
        </div>
    </div>

    <!-- Simple P&L Summary -->
    <div class="col-lg-5">
        <div class="premium-card p-4 h-100">
            <h5 class="fw-semibold mb-3">Monthly Summary (June 2026)</h5>
            
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span>Total Income</span>
                <span class="fw-bold text-success" id="summaryIncome">৳0</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span>Total Expenses</span>
                <span class="fw-bold text-danger" id="summaryExpense">৳0</span>
            </div>
            <div class="d-flex justify-content-between py-3 mt-2 border-top">
                <span class="fw-bold">Net Profit / Loss</span>
                <span class="fw-bold fs-5" id="summaryNet">৳0</span>
            </div>
            
            <div class="mt-3 small text-muted">
                This is a simplified view. Full reports will be available in future updates.
            </div>
        </div>
    </div>

</div>

<!-- Integration Note -->
<div class="premium-card p-4 mt-4">
    <h6 class="fw-semibold">System Integration Status</h6>
    <div class="small text-muted">
        <i class="bi bi-check-circle-fill text-success me-1"></i> Quotation → Invoice conversion is working<br>
        <i class="bi bi-check-circle-fill text-success me-1"></i> Invoice status changes are saved<br>
        <i class="bi bi-check-circle-fill text-success me-1"></i> Paid invoices can be added to Cashbook via "Suggested Entries"<br>
        <i class="bi bi-check-circle-fill text-success me-1"></i> Running balance is automatically calculated in Cashbook
    </div>
</div>

<!-- Advanced Reports Section (Phase 6B) -->
<div class="mt-4">
    <h5 class="section-header mb-3">Advanced Business Reports</h5>
    
    <div class="row g-4">
        
        <!-- Client Lifetime Value -->
        <div class="col-lg-6">
            <div class="premium-card p-4 h-100">
                <h6 class="fw-semibold mb-3">Client Lifetime Value (CLV)</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th class="text-end">Total Revenue</th>
                                <th class="text-end">Projects</th>
                                <th class="text-end">CLV</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>City Hospital</td>
                                <td class="text-end">৳8.4L</td>
                                <td class="text-end">3</td>
                                <td class="text-end fw-bold text-success">৳2.8L</td>
                            </tr>
                            <tr>
                                <td>Karim Traders Ltd.</td>
                                <td class="text-end">৳5.2L</td>
                                <td class="text-end">4</td>
                                <td class="text-end fw-bold text-success">৳1.3L</td>
                            </tr>
                            <tr>
                                <td>DeshiMart Ltd.</td>
                                <td class="text-end">৳3.9L</td>
                                <td class="text-end">2</td>
                                <td class="text-end fw-bold text-success">৳1.95L</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detailed Conversion Funnel -->
        <div class="col-lg-6">
            <div class="premium-card p-4 h-100">
                <h6 class="fw-semibold mb-3">Detailed Conversion Funnel</h6>
                <canvas id="detailedFunnelChart" height="160"></canvas>
                <div class="mt-3 small">
                    <div class="d-flex justify-content-between"><span>Targeted → Quotation:</span> <span class="fw-bold">70%</span></div>
                    <div class="d-flex justify-content-between"><span>Quotation → Accepted:</span> <span class="fw-bold">69%</span></div>
                    <div class="d-flex justify-content-between"><span>Accepted → Delivered:</span> <span class="fw-bold">69%</span></div>
                </div>
            </div>
        </div>

        <!-- Team Performance -->
        <div class="col-12">
            <div class="premium-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold">Team Performance (Last 30 Days)</h6>
                    <button class="btn btn-sm btn-outline-premium" onclick="exportReport('team')">Export CSV</button>
                </div>
                <canvas id="teamPerformanceChart" height="100"></canvas>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
function loadReportsData() {
    // Load invoices
    const invoices = JSON.parse(localStorage.getItem('phaseflow_invoices') || '[]');
    
    // Outstanding Receivables
    const outstanding = invoices.filter(inv => inv.status !== 'Paid');
    const outstandingTotal = outstanding.reduce((sum, inv) => sum + inv.amount, 0);
    
    document.getElementById('outstandingAmount').textContent = '৳' + outstandingTotal.toLocaleString();
    document.getElementById('outstandingCount').textContent = outstanding.length + ' invoices pending';
    
    const outstandingDiv = document.getElementById('outstandingList');
    outstandingDiv.innerHTML = '';
    
    if (outstanding.length === 0) {
        outstandingDiv.innerHTML = `<div class="text-muted small">No outstanding invoices. Great job!</div>`;
    } else {
        outstanding.forEach(inv => {
            const div = document.createElement('div');
            div.className = 'd-flex justify-content-between align-items-center border-bottom py-2';
            div.innerHTML = `
                <div>
                    <div class="fw-semibold">${inv.invoiceNo}</div>
                    <small class="text-muted">${inv.client}</small>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-danger">৳${inv.amount.toLocaleString()}</div>
                    <small class="text-muted">Due: ${inv.dueDate}</small>
                </div>
            `;
            outstandingDiv.appendChild(div);
        });
    }
    
    // Load Cashbook for monthly summary
    const cashbook = JSON.parse(localStorage.getItem('phaseflow_cashbook') || '[]');
    
    const currentMonth = '2026-06';
    const monthlyIncome = cashbook
        .filter(e => e.type === 'Income' && e.date.startsWith(currentMonth))
        .reduce((sum, e) => sum + e.amount, 0);
    
    const monthlyExpense = cashbook
        .filter(e => e.type === 'Expense' && e.date.startsWith(currentMonth))
        .reduce((sum, e) => sum + e.amount, 0);
    
    const net = monthlyIncome - monthlyExpense;
    
    document.getElementById('monthlyIncome').textContent = '৳' + monthlyIncome.toLocaleString();
    document.getElementById('monthlyExpense').textContent = '৳' + monthlyExpense.toLocaleString();
    document.getElementById('monthlyProfit').textContent = '৳' + net.toLocaleString();
    
    document.getElementById('summaryIncome').textContent = '৳' + monthlyIncome.toLocaleString();
    document.getElementById('summaryExpense').textContent = '৳' + monthlyExpense.toLocaleString();
    
    const netEl = document.getElementById('summaryNet');
    netEl.textContent = '৳' + net.toLocaleString();
    netEl.className = net >= 0 ? 'fw-bold fs-5 text-success' : 'fw-bold fs-5 text-danger';
}

// Advanced Reports Charts
function initAdvancedReportsCharts() {
    // Detailed Conversion Funnel
    const funnelCtx = document.getElementById('detailedFunnelChart');
    if (funnelCtx) {
        new Chart(funnelCtx, {
            type: 'bar',
            data: {
                labels: ['Targeted', 'Quotation', 'Accepted', 'Delivered', 'Reviewed'],
                datasets: [{
                    label: 'Clients',
                    data: [87, 61, 42, 29, 18],
                    backgroundColor: ['#0EA5E9', '#F59E0B', '#10B981', '#3B82F6', '#9D174D'],
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });
    }

    // Team Performance Chart
    const teamCtx = document.getElementById('teamPerformanceChart');
    if (teamCtx) {
        new Chart(teamCtx, {
            type: 'bar',
            data: {
                labels: ['You (Sajid)', 'Rafiq', 'Nadia'],
                datasets: [
                    {
                        label: 'Projects Delivered',
                        data: [4, 3, 2],
                        backgroundColor: '#0D9488'
                    },
                    {
                        label: 'Tickets Resolved',
                        data: [7, 9, 5],
                        backgroundColor: '#3B82F6'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    }
}

function exportReport(type) {
    if (type === 'team') {
        alert('Team Performance Report exported as CSV (Demo)');
        // In real system: Generate and download CSV
    }
}

// Initial Load
document.addEventListener('DOMContentLoaded', function() {
    loadReportsData();
    setTimeout(() => {
        initAdvancedReportsCharts();
    }, 400);
});
</script>