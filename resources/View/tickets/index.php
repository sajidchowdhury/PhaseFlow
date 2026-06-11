<?php
/**
 * Support Tickets Module - PhaseFlow CRM (Phase 5 - Team E)
 * Error vs Feature Request + Priority Workflow + Review System
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="section-header mb-1">Support Tickets</h1>
        <p class="text-muted mb-0">Manage bugs, feature requests, and client reviews</p>
    </div>
    <div>
        <button class="btn btn-teal btn-premium" onclick="showCreateTicketModal()">
            <i class="bi bi-ticket-perforated me-1"></i> Create New Ticket
        </button>
    </div>
</div>

<!-- Stats -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <div class="premium-card px-4 py-2"><span class="fw-semibold">Open Tickets:</span> <span class="badge bg-danger text-white px-3">8</span></div>
    <div class="premium-card px-4 py-2"><span class="fw-semibold text-warning">Critical/High:</span> <span class="badge bg-warning text-dark px-3">3</span></div>
    <div class="premium-card px-4 py-2"><span class="fw-semibold">Feature Requests:</span> <span class="badge bg-info text-white px-3">5</span></div>
    <div class="premium-card px-4 py-2"><span class="fw-semibold text-success">Resolved (This Month):</span> <span class="badge bg-success text-white px-3">12</span></div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3" id="ticketTabs">
    <li class="nav-item">
        <button class="nav-link active" onclick="filterTicketsByType('all')">All Tickets</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" onclick="filterTicketsByType('error')">Errors / Bugs</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" onclick="filterTicketsByType('feature')">Feature Requests</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" onclick="filterTicketsByType('review')">Review Requests</button>
    </li>
</ul>

<!-- Tickets Table -->
<div class="premium-card">
    <div class="table-responsive">
        <table class="table modern-table mb-0">
            <thead>
                <tr>
                    <th>Ticket #</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Client / Project</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="ticketsTableBody"></tbody>
        </table>
    </div>
</div>

<!-- Create Ticket Modal -->
<div class="modal fade" id="createTicketModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Create New Support Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="ticketForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Ticket Type</label>
                            <select class="form-select" id="ticketType" required>
                                <option value="Error">Error / Bug</option>
                                <option value="Feature">Feature Request</option>
                                <option value="Review">Review Request</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Priority</label>
                            <select class="form-select" id="ticketPriority">
                                <option value="Critical">Critical</option>
                                <option value="High">High</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ticketTitle" placeholder="Short description of the issue or request" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Related Client</label>
                            <select class="form-select" id="ticketClient">
                                <option value="">Select Client</option>
                                <option value="Karim Traders Ltd.">Karim Traders Ltd.</option>
                                <option value="Sunrise Pharmacy">Sunrise Pharmacy</option>
                                <option value="City Hospital">City Hospital</option>
                                <option value="DeshiMart Ltd.">DeshiMart Ltd.</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Related Project</label>
                            <select class="form-select" id="ticketProject">
                                <option value="">Select Project</option>
                                <option value="Inventory System v2.0">Inventory System v2.0</option>
                                <option value="Pharmacy Management System">Pharmacy Management System</option>
                                <option value="Hospital Inventory + Expiry">Hospital Inventory + Expiry</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-medium">Details / Steps to Reproduce</label>
                            <textarea class="form-control" id="ticketDetails" rows="4" placeholder="Please describe the issue or feature request in detail..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-teal" onclick="createNewTicket()">Create Ticket</button>
            </div>
        </div>
    </div>
</div>

<!-- Ticket Detail Modal -->
<div class="modal fade" id="ticketDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="modalTicketNo"></h5>
                    <span class="badge" id="modalTicketType"></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7">
                        <h6 class="fw-semibold" id="modalTicketTitle"></h6>
                        <div class="small text-muted mb-3" id="modalTicketClient"></div>
                        
                        <div class="mb-3">
                            <strong class="small">Details:</strong>
                            <div class="small mt-1 p-3 bg-light rounded" id="modalTicketDetails"></div>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Priority</label><br>
                            <span class="badge fs-6 px-3 py-2" id="modalTicketPriority"></span>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Current Status</label><br>
                            <span class="badge fs-6 px-3 py-2" id="modalTicketStatus"></span>
                        </div>
                        
                        <div>
                            <label class="form-label small fw-medium">Change Status</label>
                            <div class="d-grid gap-2 mt-1">
                                <button class="btn btn-sm btn-outline-primary" onclick="updateTicketStatus('In Progress')">In Progress</button>
                                <button class="btn btn-sm btn-outline-success" onclick="updateTicketStatus('Resolved')">Mark as Resolved</button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="updateTicketStatus('Closed')">Close Ticket</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-teal">Add Comment</button>
            </div>
        </div>
    </div>
</div>

<script>
let tickets = [
    {
        id: 1, ticketNo: "TK-2026-001", type: "Error", title: "Inventory report not generating correctly",
        client: "Karim Traders Ltd.", project: "Inventory System v2.0",
        priority: "High", status: "Open", assigned: "Rafiq",
        details: "When generating monthly report, some products are missing from the list."
    },
    {
        id: 2, ticketNo: "TK-2026-002", type: "Feature", title: "Add bulk barcode scanning feature",
        client: "City Hospital", project: "Hospital Inventory + Expiry",
        priority: "Medium", status: "In Progress", assigned: "Nadia",
        details: "Client wants to scan multiple items at once using barcode scanner."
    },
    {
        id: 3, ticketNo: "TK-2026-003", type: "Error", title: "Expiry alert not triggering on time",
        client: "Sunrise Pharmacy", project: "Pharmacy Management System",
        priority: "Critical", status: "Open", assigned: "You",
        details: "Expiry notification is delayed by 2-3 days."
    },
    {
        id: 4, ticketNo: "TK-2026-004", type: "Review", title: "Yearly Review Request - DeshiMart Ltd.",
        client: "DeshiMart Ltd.", project: "DeshiMart Core System",
        priority: "Low", status: "Open", assigned: "You",
        details: "Send yearly review request and renewal quotation."
    }
];

let currentTicketId = null;

function renderTickets(filteredTickets = null) {
    const tbody = document.getElementById('ticketsTableBody');
    tbody.innerHTML = '';
    
    const data = filteredTickets || tickets;
    
    data.forEach(ticket => {
        let typeBadge = '';
        if (ticket.type === 'Error') typeBadge = 'bg-danger-subtle text-danger';
        else if (ticket.type === 'Feature') typeBadge = 'bg-info-subtle text-info';
        else typeBadge = 'bg-warning-subtle text-warning';
        
        let priorityBadge = '';
        if (ticket.priority === 'Critical') priorityBadge = 'bg-danger text-white';
        else if (ticket.priority === 'High') priorityBadge = 'bg-warning text-dark';
        else if (ticket.priority === 'Medium') priorityBadge = 'bg-primary-subtle text-primary';
        else priorityBadge = 'bg-secondary-subtle text-secondary';
        
        let statusBadge = '';
        if (ticket.status === 'Open') statusBadge = 'bg-danger-subtle text-danger';
        else if (ticket.status === 'In Progress') statusBadge = 'bg-warning-subtle text-warning';
        else if (ticket.status === 'Resolved') statusBadge = 'bg-success-subtle text-success';
        else statusBadge = 'bg-secondary-subtle text-secondary';
        
        const row = `
            <tr onclick="showTicketDetail(${ticket.id})" style="cursor: pointer;">
                <td class="fw-semibold">${ticket.ticketNo}</td>
                <td><span class="badge ${typeBadge} px-3 py-1">${ticket.type}</span></td>
                <td>${ticket.title}</td>
                <td class="small">${ticket.client}<br><span class="text-muted">${ticket.project}</span></td>
                <td><span class="badge ${priorityBadge} px-3 py-1">${ticket.priority}</span></td>
                <td><span class="badge ${statusBadge} px-3 py-1">${ticket.status}</span></td>
                <td><span class="badge bg-light text-dark">${ticket.assigned}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-premium" onclick="event.stopImmediatePropagation(); showTicketDetail(${ticket.id})">Manage</button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function filterTicketsByType(type) {
    // Remove active from all tabs
    document.querySelectorAll('#ticketTabs .nav-link').forEach(el => el.classList.remove('active'));
    
    let filtered = tickets;
    
    if (type === 'error') {
        filtered = tickets.filter(t => t.type === 'Error');
        document.querySelector('#ticketTabs .nav-link:nth-child(2)').classList.add('active');
    } else if (type === 'feature') {
        filtered = tickets.filter(t => t.type === 'Feature');
        document.querySelector('#ticketTabs .nav-link:nth-child(3)').classList.add('active');
    } else if (type === 'review') {
        filtered = tickets.filter(t => t.type === 'Review');
        document.querySelector('#ticketTabs .nav-link:nth-child(4)').classList.add('active');
    } else {
        document.querySelector('#ticketTabs .nav-link:first-child').classList.add('active');
    }
    
    renderTickets(filtered);
}

function showCreateTicketModal() {
    document.getElementById('ticketForm').reset();
    new bootstrap.Modal(document.getElementById('createTicketModal')).show();
}

function createNewTicket() {
    const newTicket = {
        id: Date.now(),
        ticketNo: "TK-2026-" + String(Math.floor(Math.random() * 900) + 100),
        type: document.getElementById('ticketType').value,
        title: document.getElementById('ticketTitle').value,
        client: document.getElementById('ticketClient').value || "Unknown Client",
        project: document.getElementById('ticketProject').value || "General",
        priority: document.getElementById('ticketPriority').value,
        status: "Open",
        assigned: "You",
        details: document.getElementById('ticketDetails').value || "No details provided."
    };
    
    tickets.unshift(newTicket);
    
    bootstrap.Modal.getInstance(document.getElementById('createTicketModal')).hide();
    renderTickets();
    
    showToast('Ticket Created', `${newTicket.ticketNo} has been created successfully.`, 'success');
}

function showTicketDetail(ticketId) {
    const ticket = tickets.find(t => t.id === ticketId);
    if (!ticket) return;
    
    currentTicketId = ticketId;
    
    document.getElementById('modalTicketNo').textContent = ticket.ticketNo;
    document.getElementById('modalTicketTitle').textContent = ticket.title;
    document.getElementById('modalTicketClient').innerHTML = `${ticket.client} <span class="text-muted">• ${ticket.project}</span>`;
    document.getElementById('modalTicketDetails').textContent = ticket.details;
    
    // Type badge
    const typeEl = document.getElementById('modalTicketType');
    typeEl.textContent = ticket.type;
    typeEl.className = `badge px-3 py-2 ${ticket.type === 'Error' ? 'bg-danger-subtle text-danger' : ticket.type === 'Feature' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning'}`;
    
    // Priority
    const priorityEl = document.getElementById('modalTicketPriority');
    let pClass = '';
    if (ticket.priority === 'Critical') pClass = 'bg-danger text-white';
    else if (ticket.priority === 'High') pClass = 'bg-warning text-dark';
    else pClass = 'bg-primary-subtle text-primary';
    priorityEl.className = `badge px-3 py-2 ${pClass}`;
    priorityEl.textContent = ticket.priority;
    
    // Status
    const statusEl = document.getElementById('modalTicketStatus');
    statusEl.textContent = ticket.status;
    statusEl.className = `badge px-3 py-2 ${ticket.status === 'Open' ? 'bg-danger-subtle text-danger' : ticket.status === 'In Progress' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success'}`;
    
    new bootstrap.Modal(document.getElementById('ticketDetailModal')).show();
}

function updateTicketStatus(newStatus) {
    if (!currentTicketId) return;
    
    const ticket = tickets.find(t => t.id === currentTicketId);
    if (!ticket) return;
    
    ticket.status = newStatus;
    
    bootstrap.Modal.getInstance(document.getElementById('ticketDetailModal')).hide();
    
    setTimeout(() => {
        renderTickets();
        showToast('Status Updated', `Ticket ${ticket.ticketNo} marked as ${newStatus}.`, 'success');
    }, 300);
}

// Initial Load
document.addEventListener('DOMContentLoaded', function() {
    renderTickets();
});
</script>