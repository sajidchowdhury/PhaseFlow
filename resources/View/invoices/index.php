<?php
/**
 * Invoices Module - PhaseFlow CRM (Phase 4B)
 * Invoice Listing + Status Management + Detail
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="section-header mb-1">Invoices</h1>
        <p class="text-muted mb-0">Track all invoices and their payment status</p>
    </div>
</div>

<!-- Stats -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <div class="premium-card px-4 py-2"><span class="fw-semibold">Total Invoices:</span> <span class="badge bg-dark text-white px-3">12</span></div>
    <div class="premium-card px-4 py-2"><span class="fw-semibold text-warning">Draft:</span> <span class="badge bg-warning text-dark px-3">3</span></div>
    <div class="premium-card px-4 py-2"><span class="fw-semibold text-primary">Sent / Unpaid:</span> <span class="badge bg-primary text-white px-3">5</span></div>
    <div class="premium-card px-4 py-2"><span class="fw-semibold text-success">Paid:</span> <span class="badge bg-success text-white px-3">3</span></div>
    <div class="premium-card px-4 py-2"><span class="fw-semibold text-danger">Overdue:</span> <span class="badge bg-danger text-white px-3">1</span></div>
</div>

<!-- Filters -->
<div class="premium-card p-3 mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <input type="text" class="form-control" id="invoiceSearch" placeholder="Search invoice or client..." onkeyup="filterInvoices()">
        </div>
        <div class="col-md-3">
            <select class="form-select" id="invoiceStatusFilter" onchange="filterInvoices()">
                <option value="">All Status</option>
                <option value="Draft">Draft</option>
                <option value="Sent">Sent</option>
                <option value="Paid">Paid</option>
                <option value="Partial">Partial</option>
                <option value="Overdue">Overdue</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="invoiceClientFilter" onchange="filterInvoices()">
                <option value="">All Clients</option>
                <option value="Karim Traders Ltd.">Karim Traders Ltd.</option>
                <option value="Sunrise Pharmacy">Sunrise Pharmacy</option>
                <option value="City Hospital">City Hospital</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-premium w-100" onclick="resetInvoiceFilters()">Reset</button>
        </div>
    </div>
</div>

<!-- Invoices Table -->
<div class="premium-card">
    <div class="table-responsive">
        <table class="table modern-table mb-0">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="invoicesTableBody"></tbody>
        </table>
    </div>
</div>

<!-- Invoice Detail / Status Modal -->
<div class="modal fade" id="invoiceDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="modalInvoiceNo"></h5>
                    <small class="text-muted" id="modalInvoiceClient"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Current Status</label><br>
                            <span class="badge fs-6 px-3 py-2" id="modalInvoiceStatus"></span>
                        </div>
                        
                        <div class="small">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Issue Date</span>
                                <span class="fw-semibold" id="modalInvoiceDate"></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Due Date</span>
                                <span class="fw-semibold" id="modalInvoiceDue"></span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">Total Amount</span>
                                <span class="fw-bold text-success fs-5" id="modalInvoiceAmount"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-2">Change Status</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="updateInvoiceStatus('Sent')">Mark as Sent</button>
                            <button class="btn btn-outline-success btn-sm" onclick="updateInvoiceStatus('Paid')">Mark as Paid (Full)</button>
                            <button class="btn btn-outline-warning btn-sm" onclick="updateInvoiceStatus('Partial')">Mark as Partial Payment</button>
                            <button class="btn btn-outline-danger btn-sm" onclick="updateInvoiceStatus('Overdue')">Mark as Overdue</button>
                        </div>
                        
                        <div class="mt-3 small text-muted">
                            <strong>Note:</strong> When marked as <strong>Paid</strong>, it will be suggested in Cashbook (Phase 4C).
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-teal">Download PDF</button>
            </div>
        </div>
    </div>
</div>

<script>
let invoices = [];

// Load from localStorage (created from Quotations conversion)
function loadInvoices() {
    const saved = localStorage.getItem('phaseflow_invoices');
    if (saved) {
        invoices = JSON.parse(saved);
    } else {
        // Default demo invoices
        invoices = [
            { id: 101, invoiceNo: "INV-2026-101", client: "DeshiMart Ltd.", date: "2026-05-15", dueDate: "2026-06-15", amount: 165000, status: "Paid" },
            { id: 102, invoiceNo: "INV-2026-102", client: "City Hospital", date: "2026-06-01", dueDate: "2026-06-30", amount: 420000, status: "Sent" },
            { id: 103, invoiceNo: "INV-2026-103", client: "Karim Traders Ltd.", date: "2026-06-08", dueDate: "2026-06-25", amount: 185000, status: "Draft" },
        ];
    }
}

function saveInvoices() {
    localStorage.setItem('phaseflow_invoices', JSON.stringify(invoices));
}

function renderInvoices(filtered = null) {
    const tbody = document.getElementById('invoicesTableBody');
    tbody.innerHTML = '';
    
    const data = filtered || invoices;
    
    data.forEach(inv => {
        let statusClass = '';
        if (inv.status === 'Paid') statusClass = 'bg-success-subtle text-success';
        else if (inv.status === 'Sent') statusClass = 'bg-primary-subtle text-primary';
        else if (inv.status === 'Draft') statusClass = 'bg-warning-subtle text-warning';
        else if (inv.status === 'Overdue') statusClass = 'bg-danger-subtle text-danger';
        else statusClass = 'bg-info-subtle text-info';
        
        const row = `
            <tr onclick="showInvoiceDetail(${inv.id})" style="cursor: pointer;">
                <td class="fw-semibold">${inv.invoiceNo}</td>
                <td>${inv.client}</td>
                <td class="small text-muted">${inv.date}</td>
                <td class="small">${inv.dueDate}</td>
                <td class="fw-bold text-success">৳${(inv.amount/1000).toFixed(0)}K</td>
                <td><span class="badge px-3 py-2 ${statusClass}">${inv.status}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-premium" onclick="event.stopImmediatePropagation(); showInvoiceDetail(${inv.id})">Manage</button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function filterInvoices() {
    const search = document.getElementById('invoiceSearch').value.toLowerCase();
    const status = document.getElementById('invoiceStatusFilter').value;
    const client = document.getElementById('invoiceClientFilter').value;
    
    const filtered = invoices.filter(i => {
        const matchSearch = i.invoiceNo.toLowerCase().includes(search) || i.client.toLowerCase().includes(search);
        const matchStatus = !status || i.status === status;
        const matchClient = !client || i.client === client;
        return matchSearch && matchStatus && matchClient;
    });
    
    renderInvoices(filtered);
}

function resetInvoiceFilters() {
    document.getElementById('invoiceSearch').value = '';
    document.getElementById('invoiceStatusFilter').value = '';
    document.getElementById('invoiceClientFilter').value = '';
    renderInvoices();
}

let currentInvoiceId = null;

function showInvoiceDetail(invoiceId) {
    const inv = invoices.find(i => i.id === invoiceId);
    if (!inv) return;
    
    currentInvoiceId = invoiceId;
    
    document.getElementById('modalInvoiceNo').textContent = inv.invoiceNo;
    document.getElementById('modalInvoiceClient').textContent = inv.client;
    document.getElementById('modalInvoiceDate').textContent = inv.date;
    document.getElementById('modalInvoiceDue').textContent = inv.dueDate;
    document.getElementById('modalInvoiceAmount').textContent = '৳' + inv.amount.toLocaleString();
    
    const statusBadge = document.getElementById('modalInvoiceStatus');
    let statusClass = '';
    if (inv.status === 'Paid') statusClass = 'bg-success-subtle text-success';
    else if (inv.status === 'Sent') statusClass = 'bg-primary-subtle text-primary';
    else if (inv.status === 'Draft') statusClass = 'bg-warning-subtle text-warning';
    else if (inv.status === 'Overdue') statusClass = 'bg-danger-subtle text-danger';
    
    statusBadge.className = `badge px-3 py-2 fs-6 ${statusClass}`;
    statusBadge.textContent = inv.status;
    
    new bootstrap.Modal(document.getElementById('invoiceDetailModal')).show();
}

function updateInvoiceStatus(newStatus) {
    if (!currentInvoiceId) return;
    
    const inv = invoices.find(i => i.id === currentInvoiceId);
    if (!inv) return;
    
    inv.status = newStatus;
    saveInvoices();
    
    // Close modal and refresh
    bootstrap.Modal.getInstance(document.getElementById('invoiceDetailModal')).hide();
    
    setTimeout(() => {
        renderInvoices();
        showToast('Status Updated', `Invoice ${inv.invoiceNo} marked as ${newStatus}.`, 'success');
        
        // Future: If Paid, suggest adding to Cashbook
        if (newStatus === 'Paid') {
            setTimeout(() => {
                showToast('Ready for Cashbook', 'This payment can now be recorded in Cashbook (Phase 4C).', 'info');
            }, 1200);
        }
    }, 300);
}

// Initial Load
document.addEventListener('DOMContentLoaded', function() {
    loadInvoices();
    renderInvoices();
});
</script>