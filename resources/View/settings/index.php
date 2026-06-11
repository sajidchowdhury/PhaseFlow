<?php
/**
 * Settings & Configuration - PhaseFlow CRM (Phase 6C)
 * Product Catalog + Email Templates + Team Settings
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="section-header mb-1">Settings</h1>
        <p class="text-muted mb-0">Configure your products, templates, and team preferences</p>
    </div>
</div>

<div class="row g-4">
    
    <!-- Sidebar Navigation -->
    <div class="col-lg-3">
        <div class="premium-card p-3">
            <div class="nav flex-column nav-pills">
                <button class="nav-link active text-start mb-1" onclick="showSettingsSection('catalog')">
                    <i class="bi bi-box-seam me-2"></i> Product / Service Catalog
                </button>
                <button class="nav-link text-start mb-1" onclick="showSettingsSection('templates')">
                    <i class="bi bi-envelope me-2"></i> Email Templates
                </button>
                <button class="nav-link text-start mb-1" onclick="showSettingsSection('team')">
                    <i class="bi bi-people me-2"></i> Team & Users
                </button>
                <button class="nav-link text-start" onclick="showSettingsSection('general')">
                    <i class="bi bi-gear me-2"></i> General Preferences
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="col-lg-9">
        
        <!-- Product/Service Catalog -->
        <div id="section-catalog" class="settings-section">
            <div class="premium-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold mb-0">Product & Service Catalog</h5>
                    <button class="btn btn-teal btn-sm" onclick="showAddProductModal()">
                        <i class="bi bi-plus-lg"></i> Add New Service
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Type</th>
                                <th class="text-end">Base Price (৳)</th>
                                <th>Billing Model</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="catalogTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Email Templates -->
        <div id="section-templates" class="settings-section" style="display: none;">
            <div class="premium-card p-4">
                <h5 class="fw-semibold mb-3">Email Templates</h5>
                
                <div class="list-group">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Quotation Sent</div>
                            <small class="text-muted">Sent when a quotation is created</small>
                        </div>
                        <button class="btn btn-sm btn-outline-premium" onclick="editEmailTemplate('quotation')">Edit</button>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Review Request</div>
                            <small class="text-muted">Yearly review collection email</small>
                        </div>
                        <button class="btn btn-sm btn-outline-premium" onclick="editEmailTemplate('review')">Edit</button>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Ticket Resolution</div>
                            <small class="text-muted">When a support ticket is resolved</small>
                        </div>
                        <button class="btn btn-sm btn-outline-premium" onclick="editEmailTemplate('ticket')">Edit</button>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">Invoice Payment Reminder</div>
                            <small class="text-muted">Sent before due date</small>
                        </div>
                        <button class="btn btn-sm btn-outline-premium" onclick="editEmailTemplate('invoice')">Edit</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team & Users -->
        <div id="section-team" class="settings-section" style="display: none;">
            <div class="premium-card p-4">
                <h5 class="fw-semibold mb-3">Team Members</h5>
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Sajid Rahman</td>
                                <td><span class="badge bg-dark">Admin / Founder</span></td>
                                <td>sajid@phaseflow.com</td>
                                <td><span class="badge bg-success-subtle text-success">Active</span></td>
                                <td class="text-end"><button class="btn btn-sm btn-outline-premium">Edit</button></td>
                            </tr>
                            <tr>
                                <td>Rafiq Ahmed</td>
                                <td><span class="badge bg-primary-subtle text-primary">Developer</span></td>
                                <td>rafiq@phaseflow.com</td>
                                <td><span class="badge bg-success-subtle text-success">Active</span></td>
                                <td class="text-end"><button class="btn btn-sm btn-outline-premium">Edit</button></td>
                            </tr>
                            <tr>
                                <td>Nadia Islam</td>
                                <td><span class="badge bg-info-subtle text-info">Project Manager</span></td>
                                <td>nadia@phaseflow.com</td>
                                <td><span class="badge bg-success-subtle text-success">Active</span></td>
                                <td class="text-end"><button class="btn btn-sm btn-outline-premium">Edit</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button class="btn btn-outline-premium btn-sm mt-2">+ Invite New Team Member</button>
            </div>
        </div>

        <!-- General Preferences -->
        <div id="section-general" class="settings-section" style="display: none;">
            <div class="premium-card p-4">
                <h5 class="fw-semibold mb-3">General Preferences</h5>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" value="PhaseFlow CRM">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Default Currency</label>
                        <select class="form-select">
                            <option>BDT (৳)</option>
                            <option>USD ($)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Default Quotation Validity</label>
                        <select class="form-select">
                            <option>15 Days</option>
                            <option selected>30 Days</option>
                            <option>45 Days</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Default Invoice Due Period</label>
                        <select class="form-select">
                            <option>7 Days</option>
                            <option selected>15 Days</option>
                            <option>30 Days</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button class="btn btn-teal">Save Preferences</button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Add/Edit Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="productModalTitle">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <div class="mb-3">
                        <label class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="productName" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" id="productType">
                                <option>Software</option>
                                <option>Service</option>
                                <option>Maintenance</option>
                                <option>Hosting</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Base Price (৳)</label>
                            <input type="number" class="form-control" id="productPrice" value="100000">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Billing Model</label>
                        <select class="form-select" id="productBilling">
                            <option>One-time</option>
                            <option>Monthly</option>
                            <option>Yearly</option>
                            <option>Hybrid</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-teal" onclick="saveProduct()">Save Service</button>
            </div>
        </div>
    </div>
</div>

<script>
let catalog = [
    { id: 1, name: "Inventory Management System", type: "Software", price: 95000, billing: "One-time" },
    { id: 2, name: "Pharmacy Management System", type: "Software", price: 145000, billing: "One-time" },
    { id: 3, name: "Inventory + Expiry Tracking", type: "Software", price: 265000, billing: "One-time" },
    { id: 4, name: "Annual Maintenance & Support", type: "Maintenance", price: 55000, billing: "Yearly" },
    { id: 5, name: "Custom Web Application Development", type: "Service", price: 350000, billing: "Hybrid" },
];

function renderCatalog() {
    const tbody = document.getElementById('catalogTableBody');
    tbody.innerHTML = '';
    
    catalog.forEach(item => {
        const row = `
            <tr>
                <td class="fw-semibold">${item.name}</td>
                <td><span class="badge bg-light text-dark">${item.type}</span></td>
                <td class="text-end fw-bold">৳${item.price.toLocaleString()}</td>
                <td><span class="badge bg-primary-subtle text-primary">${item.billing}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-premium me-1" onclick="editProduct(${item.id})">Edit</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(${item.id})">Delete</button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

let editingProductId = null;

function showAddProductModal() {
    editingProductId = null;
    document.getElementById('productModalTitle').textContent = 'Add New Service';
    document.getElementById('productForm').reset();
    new bootstrap.Modal(document.getElementById('productModal')).show();
}

function editProduct(id) {
    const product = catalog.find(p => p.id === id);
    if (!product) return;
    
    editingProductId = id;
    document.getElementById('productModalTitle').textContent = 'Edit Service';
    document.getElementById('productName').value = product.name;
    document.getElementById('productType').value = product.type;
    document.getElementById('productPrice').value = product.price;
    document.getElementById('productBilling').value = product.billing;
    
    new bootstrap.Modal(document.getElementById('productModal')).show();
}

function saveProduct() {
    const name = document.getElementById('productName').value;
    const type = document.getElementById('productType').value;
    const price = parseInt(document.getElementById('productPrice').value);
    const billing = document.getElementById('productBilling').value;
    
    if (!name) return;
    
    if (editingProductId) {
        // Update existing
        const index = catalog.findIndex(p => p.id === editingProductId);
        if (index !== -1) {
            catalog[index].name = name;
            catalog[index].type = type;
            catalog[index].price = price;
            catalog[index].billing = billing;
        }
    } else {
        // Add new
        catalog.push({
            id: Date.now(),
            name: name,
            type: type,
            price: price,
            billing: billing
        });
    }
    
    bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
    renderCatalog();
    showToast('Success', 'Service catalog updated.', 'success');
}

function deleteProduct(id) {
    if (!confirm('Delete this service from catalog?')) return;
    catalog = catalog.filter(p => p.id !== id);
    renderCatalog();
}

function showSettingsSection(section) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(el => el.style.display = 'none');
    
    // Show selected
    document.getElementById('section-' + section).style.display = 'block';
    
    // Update active nav
    document.querySelectorAll('.nav-pills .nav-link').forEach(el => el.classList.remove('active'));
    event.currentTarget?.classList.add('active');
}

function editEmailTemplate(type) {
    showToast('Coming Soon', `Email template editor for "${type}" will be available soon.`, 'info');
}

// Initial Load
document.addEventListener('DOMContentLoaded', function() {
    renderCatalog();
    
    // Show catalog by default
    document.getElementById('section-catalog').style.display = 'block';
});
</script>