<?php
/**
 * Cashbook Module - PhaseFlow CRM (Phase 4C)
 * Professional Cashbook with Running Balance + Suggested Entries
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="section-header mb-1">Cashbook</h1>
        <p class="text-muted mb-0">Track all income and expenses with running balance</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-premium btn-premium" onclick="showSuggestedEntries()">
            <i class="bi bi-lightning-charge me-1"></i> Suggested from Invoices
        </button>
        <button class="btn btn-teal btn-premium" onclick="showAddEntryModal()">
            <i class="bi bi-plus-lg me-1"></i> Add New Entry
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="premium-card p-4">
            <div class="text-muted small">Total Income</div>
            <div class="fs-3 fw-bold text-success" id="totalIncome">৳0</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="premium-card p-4">
            <div class="text-muted small">Total Expenses</div>
            <div class="fs-3 fw-bold text-danger" id="totalExpense">৳0</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="premium-card p-4 border border-teal">
            <div class="text-muted small">Current Balance</div>
            <div class="fs-2 fw-bold text-teal" id="currentBalance">৳0</div>
        </div>
    </div>
</div>

<!-- Cashbook Table -->
<div class="premium-card">
    <div class="table-responsive">
        <table class="table modern-table mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th class="text-end">Amount</th>
                    <th class="text-end">Balance</th>
                </tr>
            </thead>
            <tbody id="cashbookTableBody"></tbody>
        </table>
    </div>
</div>

<!-- Add New Entry Modal -->
<div class="modal fade" id="addEntryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="entryForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Date</label>
                            <input type="date" class="form-control" id="entryDate" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Type</label>
                            <select class="form-select" id="entryType" onchange="updateCategoryOptions()">
                                <option value="Income">Income</option>
                                <option value="Expense">Expense</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Category</label>
                            <select class="form-select" id="entryCategory"></select>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Description</label>
                            <input type="text" class="form-control" id="entryDescription" placeholder="e.g. Payment received from Karim Traders">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Amount (৳)</label>
                            <input type="number" class="form-control" id="entryAmount" placeholder="0">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-teal" onclick="addNewEntry()">Add Entry</button>
            </div>
        </div>
    </div>
</div>

<!-- Suggested Entries Modal -->
<div class="modal fade" id="suggestedModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Suggested Entries from Paid Invoices</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="suggestedList"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
let cashbookEntries = [];

// Load from localStorage
function loadCashbook() {
    const saved = localStorage.getItem('phaseflow_cashbook');
    if (saved) {
        cashbookEntries = JSON.parse(saved);
    } else {
        // Default demo data
        cashbookEntries = [
            { id: 1, date: "2026-06-01", type: "Income", category: "Project Revenue", description: "Payment received - DeshiMart Ltd.", amount: 165000 },
            { id: 2, date: "2026-06-05", type: "Expense", category: "Office Expense", description: "Office rent - June", amount: 35000 },
            { id: 3, date: "2026-06-08", type: "Income", category: "Project Revenue", description: "Advance received - City Hospital", amount: 200000 },
            { id: 4, date: "2026-06-10", type: "Expense", category: "Salary", description: "Developer salary - Rafiq", amount: 45000 },
        ];
    }
}

function saveCashbook() {
    localStorage.setItem('phaseflow_cashbook', JSON.stringify(cashbookEntries));
}

// Calculate running balance
function calculateRunningBalance(entries) {
    let balance = 0;
    return entries.map(entry => {
        if (entry.type === 'Income') {
            balance += entry.amount;
        } else {
            balance -= entry.amount;
        }
        return { ...entry, balance: balance };
    });
}

function renderCashbook(filteredEntries = null) {
    const tbody = document.getElementById('cashbookTableBody');
    tbody.innerHTML = '';
    
    let data = filteredEntries || cashbookEntries;
    
    // Sort by date (newest first)
    data = [...data].sort((a, b) => new Date(b.date) - new Date(a.date));
    
    const entriesWithBalance = calculateRunningBalance(data);
    
    let totalIncome = 0;
    let totalExpense = 0;
    
    entriesWithBalance.forEach(entry => {
        if (entry.type === 'Income') totalIncome += entry.amount;
        else totalExpense += entry.amount;
        
        const row = `
            <tr>
                <td class="small text-muted">${entry.date}</td>
                <td>
                    <span class="badge ${entry.type === 'Income' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'} px-3 py-1">
                        ${entry.type}
                    </span>
                </td>
                <td><span class="badge bg-light text-dark border">${entry.category}</span></td>
                <td>${entry.description}</td>
                <td class="text-end fw-bold ${entry.type === 'Income' ? 'text-success' : 'text-danger'}">
                    ${entry.type === 'Income' ? '+' : '-'}৳${entry.amount.toLocaleString()}
                </td>
                <td class="text-end fw-bold ${entry.balance >= 0 ? 'text-teal' : 'text-danger'}">
                    ৳${entry.balance.toLocaleString()}
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    
    // Update summary cards
    document.getElementById('totalIncome').textContent = '৳' + totalIncome.toLocaleString();
    document.getElementById('totalExpense').textContent = '৳' + totalExpense.toLocaleString();
    document.getElementById('currentBalance').textContent = '৳' + (totalIncome - totalExpense).toLocaleString();
}

function updateCategoryOptions() {
    const type = document.getElementById('entryType').value;
    const categorySelect = document.getElementById('entryCategory');
    categorySelect.innerHTML = '';
    
    let categories = [];
    
    if (type === 'Income') {
        categories = ['Project Revenue', 'Advance Payment', 'Maintenance Contract', 'Consulting Fee', 'Other Income'];
    } else {
        categories = ['Office Expense', 'Salary', 'Software Tools', 'Marketing', 'Travel', 'Utilities', 'Other Expense'];
    }
    
    categories.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat;
        option.textContent = cat;
        categorySelect.appendChild(option);
    });
}

function showAddEntryModal() {
    // Reset form
    document.getElementById('entryForm').reset();
    document.getElementById('entryDate').value = new Date().toISOString().split('T')[0];
    
    // Set default categories for Income
    updateCategoryOptions();
    
    new bootstrap.Modal(document.getElementById('addEntryModal')).show();
}

function addNewEntry() {
    const newEntry = {
        id: Date.now(),
        date: document.getElementById('entryDate').value,
        type: document.getElementById('entryType').value,
        category: document.getElementById('entryCategory').value,
        description: document.getElementById('entryDescription').value || 'No description',
        amount: parseFloat(document.getElementById('entryAmount').value) || 0
    };
    
    if (newEntry.amount <= 0) {
        alert('Please enter a valid amount');
        return;
    }
    
    cashbookEntries.unshift(newEntry);
    saveCashbook();
    
    bootstrap.Modal.getInstance(document.getElementById('addEntryModal')).hide();
    renderCashbook();
    
    showToast('Entry Added', 'Transaction has been recorded in Cashbook.', 'success');
}

function showSuggestedEntries() {
    const suggestedDiv = document.getElementById('suggestedList');
    suggestedDiv.innerHTML = '';
    
    // Get paid invoices from localStorage
    const paidInvoices = JSON.parse(localStorage.getItem('phaseflow_invoices') || '[]')
        .filter(inv => inv.status === 'Paid');
    
    if (paidInvoices.length === 0) {
        suggestedDiv.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">No paid invoices found yet.<br>Mark some invoices as Paid first.</p>
            </div>
        `;
    } else {
        paidInvoices.forEach(inv => {
            const div = document.createElement('div');
            div.className = 'd-flex justify-content-between align-items-center border rounded p-3 mb-2';
            div.innerHTML = `
                <div>
                    <div class="fw-semibold">${inv.invoiceNo} - ${inv.client}</div>
                    <small class="text-muted">৳${inv.amount.toLocaleString()}</small>
                </div>
                <button class="btn btn-sm btn-teal" onclick="addSuggestedEntry(${inv.id}, this)">Add as Income</button>
            `;
            suggestedDiv.appendChild(div);
        });
    }
    
    new bootstrap.Modal(document.getElementById('suggestedModal')).show();
}

function addSuggestedEntry(invoiceId, buttonElement) {
    const paidInvoices = JSON.parse(localStorage.getItem('phaseflow_invoices') || '[]');
    const inv = paidInvoices.find(i => i.id === invoiceId);
    if (!inv) return;
    
    const newEntry = {
        id: Date.now(),
        date: new Date().toISOString().split('T')[0],
        type: "Income",
        category: "Project Revenue",
        description: `Payment received from ${inv.client} (${inv.invoiceNo})`,
        amount: inv.amount
    };
    
    cashbookEntries.unshift(newEntry);
    saveCashbook();
    
    // Remove the suggestion row
    buttonElement.closest('.d-flex').remove();
    
    renderCashbook();
    showToast('Suggested Entry Added', `Income of ৳${inv.amount.toLocaleString()} recorded.`, 'success');
}

// Initial Load
document.addEventListener('DOMContentLoaded', function() {
    loadCashbook();
    renderCashbook();
});
</script>