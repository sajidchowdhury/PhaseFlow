<?php
/**
 * Projects Module - PhaseFlow CRM (Phase 4A - Team D)
 * Project Listing with Table + Card View + Creation Modal
 */
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="section-header mb-1">Projects</h1>
        <p class="text-muted mb-0">Manage all ongoing and completed software projects</p>
    </div>
    <div>
        <button class="btn btn-teal btn-premium d-flex align-items-center gap-2 shadow-sm" onclick="showCreateProjectModal()">
            <i class="bi bi-folder-plus"></i>
            <span>Create New Project</span>
        </button>
    </div>
</div>

<!-- Stats -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <div class="premium-card px-4 py-2 d-flex align-items-center gap-2">
        <span class="fw-semibold">Total Projects:</span> 
        <span class="badge bg-dark text-white px-3 py-1">24</span>
    </div>
    <div class="premium-card px-4 py-2 d-flex align-items-center gap-2">
        <span class="fw-semibold text-success">In Progress:</span> 
        <span class="badge bg-success text-white px-3 py-1">11</span>
    </div>
    <div class="premium-card px-4 py-2 d-flex align-items-center gap-2">
        <span class="fw-semibold text-primary">Delivered:</span> 
        <span class="badge bg-primary text-white px-3 py-1">9</span>
    </div>
    <div class="premium-card px-4 py-2 d-flex align-items-center gap-2">
        <span class="fw-semibold text-warning">Planning:</span> 
        <span class="badge bg-warning text-dark px-3 py-1">4</span>
    </div>
</div>

<!-- View Toggle + Filters -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-premium active" id="btnProjectTable" onclick="switchProjectView('table')">
            <i class="bi bi-table me-1"></i> Table
        </button>
        <button type="button" class="btn btn-outline-premium" id="btnProjectCard" onclick="switchProjectView('card')">
            <i class="bi bi-grid-3x3-gap me-1"></i> Cards
        </button>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        <input type="text" class="form-control" style="max-width: 220px;" id="projectSearch" placeholder="Search project or client..." onkeyup="filterProjects()">
        
        <select class="form-select" style="max-width: 160px;" id="projectStatusFilter" onchange="filterProjects()">
            <option value="">All Status</option>
            <option value="Planning">Planning</option>
            <option value="In Progress">In Progress</option>
            <option value="Delivered">Delivered</option>
            <option value="On Hold">On Hold</option>
        </select>
        
        <select class="form-select" style="max-width: 160px;" id="projectTypeFilter" onchange="filterProjects()">
            <option value="">All Types</option>
            <option value="Inventory">Inventory Software</option>
            <option value="Pharmacy">Pharmacy System</option>
            <option value="Expiry">Inventory + Expiry</option>
            <option value="Custom">Custom Development</option>
            <option value="Hosting">Hosting & Maintenance</option>
        </select>
    </div>
</div>

<!-- TABLE VIEW -->
<div id="projectTableView">
    <div class="premium-card">
        <div class="table-responsive">
            <table class="table modern-table mb-0">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Value</th>
                        <th>Delivery Date</th>
                        <th>Assigned</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="projectsTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- CARD VIEW -->
<div id="projectCardView" style="display: none;">
    <div class="row g-3" id="projectsCardContainer"></div>
</div>

<!-- Create Project Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Create New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createProjectForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Project Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="projectName" placeholder="e.g. Inventory System v2.0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Client <span class="text-danger">*</span></label>
                            <select class="form-select" id="projectClient" required>
                                <option value="">Select Client</option>
                                <option value="1">Md. Karim Hossain - Karim Traders Ltd.</option>
                                <option value="2">Fatema Begum - Sunrise Pharmacy</option>
                                <option value="3">Rahim Uddin - Rahim Traders</option>
                                <option value="4">Nasrin Akter - City Hospital</option>
                                <option value="5">Jahangir Alam - DeshiMart Ltd.</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Project Type</label>
                            <select class="form-select" id="projectType">
                                <option value="Inventory">Inventory Software</option>
                                <option value="Pharmacy">Pharmacy Management System</option>
                                <option value="Expiry">Inventory with Expiry Tracking</option>
                                <option value="Custom">Custom Web Application</option>
                                <option value="Hosting">Hosting & Domain + Maintenance</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Estimated Value (৳)</label>
                            <input type="number" class="form-control" id="projectValue" value="185000">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Expected Delivery Date</label>
                            <input type="date" class="form-control" id="projectDeliveryDate" value="<?= date('Y-m-d', strtotime('+45 days')) ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Assigned To</label>
                            <select class="form-select" id="projectAssignee">
                                <option value="You">You (Sajid)</option>
                                <option value="Rafiq">Rafiq</option>
                                <option value="Nadia">Nadia</option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-medium">Project Description</label>
                            <textarea class="form-control" id="projectDescription" rows="3" placeholder="Key requirements and scope..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-outline-secondary btn-premium" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-teal btn-premium" onclick="createNewProject()">Create Project</button>
            </div>
        </div>
    </div>
</div>

<!-- Project Quick View Modal -->
<div class="modal fade" id="projectQuickViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="modalProjectName"></h5>
                    <small class="text-muted" id="modalProjectClient"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="mb-3">
                            <span class="badge px-3 py-2 fs-6" id="modalProjectStatus"></span>
                        </div>
                        
                        <div class="small">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Project Type</span>
                                <span class="fw-semibold" id="modalProjectType"></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Contract Value</span>
                                <span class="fw-bold text-success" id="modalProjectValue"></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Expected Delivery</span>
                                <span class="fw-semibold" id="modalProjectDelivery"></span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">Assigned To</span>
                                <span class="fw-semibold" id="modalProjectAssignee"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        <h6 class="fw-semibold mb-2">Description</h6>
                        <div class="small text-muted" id="modalProjectDescription" style="min-height: 80px;">
                            No description provided.
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="fw-semibold mb-2 small">Quick Actions</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-sm btn-outline-premium">View Full Detail</button>
                                <button class="btn btn-sm btn-outline-premium">Create Quotation</button>
                                <button class="btn btn-sm btn-teal">Mark as Delivered</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-outline-premium">Edit Project</button>
            </div>
        </div>
    </div>
</div>

<script>
// Demo Project Data
let projects = [
    {
        id: 1, name: "Inventory System v2.0", client: "Karim Traders Ltd.", clientId: 1,
        type: "Inventory", status: "In Progress", value: 185000,
        deliveryDate: "2026-07-15", assignee: "You", description: "Multi-branch inventory with reporting dashboard."
    },
    {
        id: 2, name: "Pharmacy Management System", client: "Sunrise Pharmacy", clientId: 2,
        type: "Pharmacy", status: "Planning", value: 245000,
        deliveryDate: "2026-08-20", assignee: "Rafiq", description: "Complete pharmacy solution with expiry tracking."
    },
    {
        id: 3, name: "Hospital Inventory + Expiry", client: "City Hospital", clientId: 4,
        type: "Expiry", status: "In Progress", value: 420000,
        deliveryDate: "2026-07-05", assignee: "Nadia", description: "Expiry management + barcode integration."
    },
    {
        id: 4, name: "DeshiMart Core System", client: "DeshiMart Ltd.", clientId: 5,
        type: "Inventory", status: "Delivered", value: 165000,
        deliveryDate: "2026-05-10", assignee: "You", description: "Core inventory and sales system."
    },
    {
        id: 5, name: "MediCare Annual Maintenance", client: "MediCare Pharmacy", clientId: 3,
        type: "Hosting", status: "In Progress", value: 78000,
        deliveryDate: "2026-12-31", assignee: "Rafiq", description: "Yearly support and updates contract."
    }
];

function renderProjectTable(filteredProjects) {
    const tbody = document.getElementById('projectsTableBody');
    tbody.innerHTML = '';
    
    filteredProjects.forEach(project => {
        const statusClass = getStatusClass(project.status);
        
        const row = `
            <tr onclick="showProjectQuickView(${project.id})" style="cursor: pointer;">
                <td>
                    <div class="fw-semibold">${project.name}</div>
                </td>
                <td>${project.client}</td>
                <td><span class="badge bg-light text-dark border">${project.type}</span></td>
                <td><span class="badge ${statusClass} px-3 py-2">${project.status}</span></td>
                <td class="fw-bold text-success">৳${(project.value/1000).toFixed(0)}K</td>
                <td class="small text-muted">${project.deliveryDate}</td>
                <td><span class="badge bg-light text-dark">${project.assignee}</span></td>
                <td class="text-end" onclick="event.stopImmediatePropagation();">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-premium" onclick="showProjectQuickView(${project.id})">View</button>
                        <button class="btn btn-teal">Invoice</button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function renderProjectCards(filteredProjects) {
    const container = document.getElementById('projectsCardContainer');
    container.innerHTML = '';
    
    filteredProjects.forEach(project => {
        const statusClass = getStatusClass(project.status);
        
        const card = `
            <div class="col-md-6 col-xl-4">
                <div class="premium-card p-3 h-100" onclick="showProjectQuickView(${project.id})" style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="fw-semibold">${project.name}</div>
                        <span class="badge ${statusClass} px-2 py-1">${project.status}</span>
                    </div>
                    
                    <div class="small text-muted mb-2">${project.client}</div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div>
                            <span class="badge bg-light text-dark border">${project.type}</span>
                        </div>
                        <div class="fw-bold text-success">৳${(project.value/1000).toFixed(0)}K</div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3 small">
                        <div class="text-muted">Delivery: ${project.deliveryDate}</div>
                        <div><span class="badge bg-light text-dark">${project.assignee}</span></div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });
}

function getStatusClass(status) {
    if (status === 'In Progress') return 'bg-success-subtle text-success';
    if (status === 'Planning') return 'bg-warning-subtle text-warning';
    if (status === 'Delivered') return 'bg-primary-subtle text-primary';
    if (status === 'On Hold') return 'bg-secondary-subtle text-secondary';
    return 'bg-light text-dark';
}

function filterProjects() {
    const search = document.getElementById('projectSearch').value.toLowerCase();
    const statusFilter = document.getElementById('projectStatusFilter').value;
    const typeFilter = document.getElementById('projectTypeFilter').value;
    
    const filtered = projects.filter(p => {
        const matchSearch = p.name.toLowerCase().includes(search) || p.client.toLowerCase().includes(search);
        const matchStatus = !statusFilter || p.status === statusFilter;
        const matchType = !typeFilter || p.type === typeFilter;
        return matchSearch && matchStatus && matchType;
    });
    
    renderProjectTable(filtered);
    renderProjectCards(filtered);
}

function switchProjectView(view) {
    const tableView = document.getElementById('projectTableView');
    const cardView = document.getElementById('projectCardView');
    const btnTable = document.getElementById('btnProjectTable');
    const btnCard = document.getElementById('btnProjectCard');
    
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

function showCreateProjectModal() {
    const modal = new bootstrap.Modal(document.getElementById('createProjectModal'));
    modal.show();
}

function createNewProject() {
    const form = document.getElementById('createProjectForm');
    
    const newProject = {
        id: Date.now(),
        name: document.getElementById('projectName').value || 'Untitled Project',
        client: document.getElementById('projectClient').selectedOptions[0].text.split(' - ')[1] || 'Unknown Client',
        clientId: parseInt(document.getElementById('projectClient').value) || 1,
        type: document.getElementById('projectType').value,
        status: 'Planning',
        value: parseInt(document.getElementById('projectValue').value) || 100000,
        deliveryDate: document.getElementById('projectDeliveryDate').value,
        assignee: document.getElementById('projectAssignee').value,
        description: document.getElementById('projectDescription').value || 'No description provided.'
    };
    
    projects.unshift(newProject);
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('createProjectModal'));
    modal.hide();
    
    // Refresh views
    renderProjectTable(projects);
    renderProjectCards(projects);
    
    showToast('Project Created', `"${newProject.name}" has been created successfully.`, 'success');
}

function showProjectQuickView(projectId) {
    const project = projects.find(p => p.id === projectId);
    if (!project) return;
    
    document.getElementById('modalProjectName').textContent = project.name;
    document.getElementById('modalProjectClient').textContent = project.client;
    document.getElementById('modalProjectType').textContent = project.type;
    document.getElementById('modalProjectValue').textContent = '৳' + project.value.toLocaleString();
    document.getElementById('modalProjectDelivery').textContent = project.deliveryDate;
    document.getElementById('modalProjectAssignee').textContent = project.assignee;
    document.getElementById('modalProjectDescription').textContent = project.description || 'No description provided.';
    
    const statusBadge = document.getElementById('modalProjectStatus');
    statusBadge.className = `badge px-3 py-2 fs-6 ${getStatusClass(project.status)}`;
    statusBadge.textContent = project.status;
    
    const modal = new bootstrap.Modal(document.getElementById('projectQuickViewModal'));
    modal.show();
}

// Initial Render
document.addEventListener('DOMContentLoaded', function() {
    renderProjectTable(projects);
    renderProjectCards(projects);
    
    // Default to Table view
    document.getElementById('projectTableView').style.display = 'block';
    document.getElementById('projectCardView').style.display = 'none';
});
</script>