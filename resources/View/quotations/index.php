<?php
/**
 * Quotations Module - PhaseFlow CRM (Phase 4B)
 * Improved Quotation listing with Convert to Invoice flow
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="section-header mb-1">Quotations</h1>
        <p class="text-muted mb-0">Manage all quotations and convert them into invoices</p>
    </div>
    <div>
        <button class="btn btn-teal btn-premium" onclick="showCreateQuotationModal()">
            <i class="bi bi-file-earmark-plus me-1"></i> Create New Quotation
        </button>
    </div>
</div>

<!-- Stats -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <div class="premium-card px-4 py-2"><span class="fw-semibold">Total:</span> <span class="badge bg-dark text-white px-3">18</span></div>
    <div class="premium-card px-4 py-2"><span class="fw-semibold text-warning">Draft:</span> <span class="badge bg-warning text-dark px-3">5</span></div>
    <div class="premium-card px-4 py-2"><span class="fw-semibold text-primary">Sent:</span> <span class="badge bg-primary text-white px-3">7</span></div>
    <div class="premium-card px-4 py-2"><span class="fw-semibold text-success">Accepted:</span> <span class="badge bg-success text-white px-3">4</span></div>
    <div class="premium-card px-4 py-2"><span class="fw-semibold text-danger">Rejected:</span> <span class="badge bg-danger text-white px-3">2</span></div>
</div>

<!-- Filters -->
<div class="premium-card p-3 mb-4">
    <div class="row g-3">
        <div class="col-md-4">
            <input type="text" class="form-control" id="quoteSearch" placeholder="Search by client or quote #" onkeyup="filterQuotations()">
        </div>
        <div class="col-md-3">
            <select class="form-select" id="quoteStatusFilter" onchange="filterQuotations()">
                <option value="">All Status</option>
                <option value="Draft">Draft</option>
                <option value="Sent">Sent</option>
                <option value="Accepted">Accepted</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="quoteClientFilter" onchange="filterQuotations()">
                <option value="">All Clients</option>
                <option value="Karim Traders Ltd.">Karim Traders Ltd.</option>
                <option value="Sunrise Pharmacy">Sunrise Pharmacy</option>
                <option value="City Hospital">City Hospital</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-premium w-100" onclick="resetQuoteFilters()">Reset</button>
        </div>
    </div>
</div>

<!-- Quotations Table -->
<div class="premium-card">
    <div class="table-responsive">
        <table class="table modern-table mb-0">
            <thead>
                <tr>
                    <th>Quote #</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Valid Until</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="quotationsTableBody"></tbody>
        </table>
    </div>
</div>

<!-- Convert to Invoice Confirmation Modal -->
<div class="modal fade" id="convertInvoiceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Convert to Invoice?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to convert <strong id="convertQuoteNumber"></strong> into an Invoice?</p>
                <div class="alert alert-info small">
                    This will create a new invoice in <strong>Draft</strong> status. You can edit and send it later.
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-teal" onclick="confirmConvertToInvoice()">Yes, Convert to Invoice</button>
            </div>
        </div>
    </div>
</div>

<script>
let quotations = [
    { id: 1, quoteNo: "QT-2026-001", client: "Karim Traders Ltd.", date: "2026-06-01", amount: 185000, status: "Accepted", validUntil: "2026-07-01" },
    { id: 2, quoteNo: "QT-2026-002", client: "Sunrise Pharmacy", date: "2026-06-05", amount: 245000, status: "Sent", validUntil: "2026-07-05" },
    { id: 3, quoteNo: "QT-2026-003", client: "City Hospital", date: "2026-06-08", amount: 420000, status: "Draft", validUntil: "2026-07-08" },
    { id: 4, quoteNo: "QT-2026-004", client: "DeshiMart Ltd.", date: "2026-05-20", amount: 165000, status: "Accepted", validUntil: "2026-06-20" },
    { id: 5, quoteNo: "QT-2026-005", client: "Karim Traders Ltd.", date: "2026-06-10", amount: 95000, status: "Rejected", validUntil: "2026-07-10" },
];

let selectedQuoteId = null;

function renderQuotations(filtered = null) {
    const tbody = document.getElementById('quotationsTableBody');
    tbody.innerHTML = '';
    
    const data = filtered || quotations;
    
    data.forEach(q => {
        let statusClass = '';
        if (q.status === 'Accepted') statusClass = 'bg-success-subtle text-success';
        else if (q.status === 'Sent') statusClass = 'bg-primary-subtle text-primary';
        else if (q.status === 'Draft') statusClass = 'bg-warning-subtle text-warning';
        else statusClass = 'bg-danger-subtle text-danger';
        
        let actions = '';
        if (q.status === 'Accepted') {
            actions = `<button class="btn btn-sm btn-teal" onclick="convertToInvoice(${q.id})">Convert to Invoice</button>`;
        } else if (q.status === 'Draft' || q.status === 'Sent') {
            actions = `<button class="btn btn-sm btn-outline-premium">Edit</button>`;
        }
        
        const row = `
            <tr>
                <td class="fw-semibold">${q.quoteNo}</td>
                <td>${q.client}</td>
                <td class="small text-muted">${q.date}</td>
                <td class="fw-bold text-success">৳${(q.amount/1000).toFixed(0)}K</td>
                <td><span class="badge px-3 py-2 ${statusClass}">${q.status}</span></td>
                <td class="small">${q.validUntil}</td>
                <td class="text-end">${actions}</td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function filterQuotations() {
    const search = document.getElementById('quoteSearch').value.toLowerCase();
    const status = document.getElementById('quoteStatusFilter').value;
    const client = document.getElementById('quoteClientFilter').value;
    
    const filtered = quotations.filter(q => {
        const matchSearch = q.quoteNo.toLowerCase().includes(search) || q.client.toLowerCase().includes(search);
        const matchStatus = !status || q.status === status;
        const matchClient = !client || q.client === client;
        return matchSearch && matchStatus && matchClient;
    });
    
    renderQuotations(filtered);
}

function resetQuoteFilters() {
    document.getElementById('quoteSearch').value = '';
    document.getElementById('quoteStatusFilter').value = '';
    document.getElementById('quoteClientFilter').value = '';
    renderQuotations();
}

function convertToInvoice(quoteId) {
    selectedQuoteId = quoteId;
    const quote = quotations.find(q => q.id === quoteId);
    
    document.getElementById('convertQuoteNumber').textContent = quote.quoteNo;
    
    const modal = new bootstrap.Modal(document.getElementById('convertInvoiceModal'));
    modal.show();
}

function confirmConvertToInvoice() {
    const quote = quotations.find(q => q.id === selectedQuoteId);
    if (!quote) return;
    
    // Change status to Accepted if not already
    if (quote.status !== 'Accepted') {
        quote.status = 'Accepted';
    }
    
    // Create new invoice (demo)
    const newInvoice = {
        id: Date.now(),
        invoiceNo: "INV-2026-" + String(Math.floor(Math.random() * 900) + 100),
        client: quote.client,
        date: new Date().toISOString().split('T')[0],
        dueDate: new Date(Date.now() + 15 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
        amount: quote.amount,
        status: "Draft",
        quoteId: quote.id
    };
    
    // Save to localStorage so Invoices page can access it
    let existingInvoices = JSON.parse(localStorage.getItem('phaseflow_invoices') || '[]');
    existingInvoices.unshift(newInvoice);
    localStorage.setItem('phaseflow_invoices', JSON.stringify(existingInvoices));
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('convertInvoiceModal')).hide();
    
    showToast('Invoice Created!', `Invoice ${newInvoice.invoiceNo} has been created from ${quote.quoteNo}.`, 'success');
    
    // Refresh quotations table
    setTimeout(() => {
        renderQuotations();
        // Optional: redirect hint
        if (confirm('Do you want to go to the Invoices page now?')) {
            window.location.href = '/invoices';
        }
    }, 800);
}

function showCreateQuotationModal() {
    showToast('Coming Soon', 'Full quotation creation form will be available in the next update.', 'info');
}

// Initial Load
document.addEventListener('DOMContentLoaded', function() {
    renderQuotations();
});
</script>