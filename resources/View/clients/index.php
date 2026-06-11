<?php
/**
 * Clients Module - PhaseFlow CRM (Phase 2 - Team B)
 * Table + Card View Toggle | Premium Design System
 */
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="section-header mb-1">Clients</h1>
        <p class="text-muted mb-0">Manage all targeted and real customers in one place</p>
    </div>
    <div>
        <button class="btn btn-teal btn-premium d-flex align-items-center gap-2 shadow-sm" onclick="showNewClientModal()">
            <i class="bi bi-person-plus"></i>
            <span>Add New Client</span>
        </button>
    </div>
</div>

<!-- Stats + View Toggle -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
    <div class="d-flex gap-2">
        <div class="premium-card px-4 py-2 d-flex align-items-center gap-2">
            <span class="fw-semibold">Total:</span> 
            <span class="badge bg-dark text-white px-3 py-1">187</span>
        </div>
        <div class="premium-card px-4 py-2 d-flex align-items-center gap-2">
            <span class="fw-semibold text-success">Real Customers:</span> 
            <span class="badge bg-success text-white px-3 py-1">45</span>
        </div>
        <div class="premium-card px-4 py-2 d-flex align-items-center gap-2">
            <span class="fw-semibold text-info">Targeted Leads:</span> 
            <span class="badge bg-info text-white px-3 py-1">142</span>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-premium active" id="btnTableView" onclick="switchView('table')">
            <i class="bi bi-table me-1"></i> Table
        </button>
        <button type="button" class="btn btn-outline-premium" id="btnCardView" onclick="switchView('card')">
            <i class="bi bi-grid-3x3-gap me-1"></i> Cards
        </button>
    </div>
</div>

<!-- Filters -->
<div class="premium-card p-3 mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-medium">Search</label>
            <input type="text" class="form-control" id="clientSearch" placeholder="Search by name or organization..." onkeyup="filterClients()">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-medium">Status</label>
            <select class="form-select" id="statusFilter" onchange="filterClients()">
                <option value="">All Status</option>
                <option value="targeted">Targeted / Lead</option>
                <option value="real">Real Customer</option>
                <option value="past">Past / Churned</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-medium">Source</label>
            <select class="form-select" id="sourceFilter" onchange="filterClients()">
                <option value="">All Sources</option>
                <option value="facebook">Facebook / Social</option>
                <option value="referral">Referral</option>
                <option value="website">Website</option>
                <option value="direct">Direct / Walk-in</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-premium w-100" onclick="resetFilters()">Reset</button>
        </div>
    </div>
</div>

<!-- TABLE VIEW -->
<div id="tableView">
    <div class="premium-card">
        <div class="table-responsive">
            <table class="table modern-table mb-0">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Organization</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Projects</th>
                        <th>Outstanding</th>
                        <th>Last Activity</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="clientsTableBody">
                    <!-- Populated by JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- CARD VIEW -->
<div id="cardView" style="display: none;">
    <div class="row g-3" id="clientsCardContainer">
        <!-- Populated by JS -->
    </div>
</div>

<!-- Client Quick View Modal -->
<div class="modal fade" id="clientQuickViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalClientName"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="text-center mb-3">
                            <img id="modalAvatar" src="https://i.pravatar.cc/120" class="rounded-circle border shadow-sm" width="110" height="110" alt="Client Avatar">
                            <div class="mt-2">
                                <span id="modalStatusBadge" class="badge px-3 py-2"></span>
                            </div>
                        </div>
                        
                        <div class="small">
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Phone</span>
                                <span id="modalPhone" class="fw-medium"></span>
                            </div>
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Email</span>
                                <span id="modalEmail" class="fw-medium"></span>
                            </div>
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span class="text-muted">Source</span>
                                <span id="modalSource" class="fw-medium"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h6 class="fw-semibold mb-2">Quick Stats</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="premium-card p-3 text-center">
                                    <div class="text-muted small">Total Projects</div>
                                    <div class="fs-3 fw-bold text-primary" id="modalProjects">12</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="premium-card p-3 text-center">
                                    <div class="text-muted small">Outstanding</div>
                                    <div class="fs-3 fw-bold text-danger" id="modalOutstanding">৳1.8L</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="fw-semibold mb-2">Recent Activity</h6>
                            <div class="small text-muted" id="modalActivity">
                                Last quotation sent 3 days ago
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-premium" data-bs-dismiss="modal">Close</button>
                <a href="#" class="btn btn-outline-premium">View Full Profile</a>
                <button class="btn btn-teal" onclick="createQuotationFromClient()">Create Quotation</button>
            </div>
        </div>
    </div>
</div>

<script>
// Demo Client Data
let clients = [
    {
        id: 1, name: "Md. Karim Hossain", org: "Karim Traders Ltd.", phone: "01711-554433",
        email: "karim@karimtraders.com", status: "real", source: "referral",
        projects: 8, outstanding: "৳2.4L", lastActivity: "2 days ago", avatar: "https://i.pravatar.cc/48?img=28"
    },
    {
        id: 2, name: "Fatema Begum", org: "Sunrise Pharmacy", phone: "01822-998877",
        email: "fatema@sunrisepharmacy.com", status: "targeted", source: "facebook",
        projects: 0, outstanding: "৳0", lastActivity: "Today", avatar: "https://i.pravatar.cc/48?img=47"
    },
    {
        id: 3, name: "Rahim Uddin", org: "Rahim Traders", phone: "01933-112233",
        email: "rahim@rahim.com", status: "real", source: "website",
        projects: 5, outstanding: "৳85K", lastActivity: "Yesterday", avatar: "https://i.pravatar.cc/48?img=15"
    },
    {
        id: 4, name: "Nasrin Akter", org: "City Hospital", phone: "01655-667788",
        email: "nasrin@cityhospital.com", status: "real", source: "referral",
        projects: 3, outstanding: "৳0", lastActivity: "5 days ago", avatar: "https://i.pravatar.cc/48?img=32"
    },
    {
        id: 5, name: "Jahangir Alam", org: "DeshiMart Ltd.", phone: "01744-556677",
        email: "jahangir@deshimart.com", status: "targeted", source: "direct",
        projects: 1, outstanding: "৳1.2L", lastActivity: "1 week ago", avatar: "https://i.pravatar.cc/48?img=12"
    }
];

function renderTable(filteredClients) {
    const tbody = document.getElementById('clientsTableBody');
    tbody.innerHTML = '';
    
    filteredClients.forEach(client => {
        const statusClass = client.status === 'real' ? 'status-agreed' : 
                           client.status === 'targeted' ? 'status-lead' : 'bg-secondary text-white';
        const statusText = client.status === 'real' ? 'Real Customer' : 
                          client.status === 'targeted' ? 'Targeted Lead' : 'Past Client';
        
        const row = `
            <tr onclick="showClientQuickView(${client.id})" style="cursor: pointer;">
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <img src="${client.avatar}" class="rounded-circle border" width="42" height="42" alt="">
                        <div>
                            <div class="fw-semibold">${client.name}</div>
                            <div class="small text-muted">${client.email}</div>
                        </div>
                    </div>
                </td>
                <td class="fw-medium">${client.org}</td>
                <td>${client.phone}</td>
                <td><span class="badge ${statusClass} px-3 py-2">${statusText}</span></td>
                <td><span class="fw-semibold">${client.projects}</span> <span class="text-muted small">projects</span></td>
                <td class="fw-semibold text-danger">${client.outstanding}</td>
                <td class="text-muted small">${client.lastActivity}</td>
                <td class="text-end" onclick="event.stopImmediatePropagation();">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-premium" onclick="showClientQuickView(${client.id})">View</button>
                        <button class="btn btn-teal" onclick="createQuotationFromClient(${client.id})">Quote</button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function renderCards(filteredClients) {
    const container = document.getElementById('clientsCardContainer');
    container.innerHTML = '';
    
    filteredClients.forEach(client => {
        const statusClass = client.status === 'real' ? 'status-agreed' : 
                           client.status === 'targeted' ? 'status-lead' : 'bg-secondary text-white';
        const statusText = client.status === 'real' ? 'Real Customer' : 
                          client.status === 'targeted' ? 'Targeted Lead' : 'Past Client';
        
        const card = `
            <div class="col-md-6 col-xl-4">
                <div class="premium-card p-3 h-100" onclick="showClientQuickView(${client.id})" style="cursor: pointer;">
                    <div class="d-flex align-items-start gap-3">
                        <img src="${client.avatar}" class="rounded-circle border flex-shrink-0" width="52" height="52" alt="">
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="fw-semibold">${client.name}</div>
                                    <div class="small text-muted text-truncate">${client.org}</div>
                                </div>
                                <span class="badge ${statusClass} px-2 py-1 align-self-start">${statusText}</span>
                            </div>
                            
                            <div class="mt-2 small">
                                <div><i class="bi bi-telephone me-1"></i> ${client.phone}</div>
                                <div class="mt-1"><i class="bi bi-folder me-1"></i> ${client.projects} projects • <span class="text-danger">${client.outstanding}</span></div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">${client.lastActivity}</small>
                                <div onclick="event.stopImmediatePropagation();">
                                    <button class="btn btn-sm btn-teal px-3" onclick="createQuotationFromClient(${client.id})">New Quote</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });
}

function filterClients() {
    const searchTerm = document.getElementById('clientSearch').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const sourceFilter = document.getElementById('sourceFilter').value;
    
    let filtered = clients.filter(client => {
        const matchesSearch = client.name.toLowerCase().includes(searchTerm) || 
                             client.org.toLowerCase().includes(searchTerm);
        const matchesStatus = !statusFilter || client.status === statusFilter;
        const matchesSource = !sourceFilter || client.source === sourceFilter;
        
        return matchesSearch && matchesStatus && matchesSource;
    });
    
    renderTable(filtered);
    renderCards(filtered);
}

function resetFilters() {
    document.getElementById('clientSearch').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('sourceFilter').value = '';
    filterClients();
}

function switchView(view) {
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    const btnTable = document.getElementById('btnTableView');
    const btnCard = document.getElementById('btnCardView');
    
    if (view === 'table') {
        tableView.style.display = 'block';
        cardView.style.display = 'none';
        btnTable.classList.add('active');
        btnCard.classList.remove('active');
    } else {
        tableView.style.display = 'none';
        cardView.style.display = 'block';
        btnTable.classList.remove('active');
        btnCard.classList.add('active');
    }
}

function showClientQuickView(clientId) {
    const client = clients.find(c => c.id === clientId);
    if (!client) return;
    
    document.getElementById('modalClientName').textContent = client.name;
    document.getElementById('modalAvatar').src = client.avatar;
    document.getElementById('modalPhone').textContent = client.phone;
    document.getElementById('modalEmail').textContent = client.email || 'Not provided';
    document.getElementById('modalSource').textContent = client.source.charAt(0).toUpperCase() + client.source.slice(1);
    document.getElementById('modalProjects').textContent = client.projects;
    document.getElementById('modalOutstanding').textContent = client.outstanding;
    document.getElementById('modalActivity').innerHTML = `Last activity: <strong>${client.lastActivity}</strong>`;
    
    const statusBadge = document.getElementById('modalStatusBadge');
    if (client.status === 'real') {
        statusBadge.className = 'badge status-agreed px-3 py-2';
        statusBadge.textContent = 'Real Customer';
    } else if (client.status === 'targeted') {
        statusBadge.className = 'badge status-lead px-3 py-2';
        statusBadge.textContent = 'Targeted Lead';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('clientQuickViewModal'));
    modal.show();
}

function createQuotationFromClient(clientId = null) {
    // Close any open modal first
    const openModal = document.querySelector('.modal.show');
    if (openModal) {
        bootstrap.Modal.getInstance(openModal).hide();
    }
    
    showToast('Coming Soon', 'Quotation creation flow will be ready in Phase 3 (Pipeline Module).', 'info');
}

// Initial Render
document.addEventListener('DOMContentLoaded', function() {
    renderTable(clients);
    renderCards(clients);
    
    // Make sure table view is active by default
    document.getElementById('tableView').style.display = 'block';
    document.getElementById('cardView').style.display = 'none';
});
</script>