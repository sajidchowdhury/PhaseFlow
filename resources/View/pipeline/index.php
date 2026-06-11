<?php
/**
 * Pipeline Board - PhaseFlow CRM (Phase 3 - Team C)
 * Interactive 5-Column Kanban with Drag & Drop + Quotation Flow + Persistence
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="section-header mb-1">Pipeline Board</h1>
        <p class="text-muted mb-0">Drag cards between phases • Data is automatically saved in your browser</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-premium btn-premium" onclick="resetDemoData()">
            <i class="bi bi-arrow-counterclockwise"></i> Reset Demo
        </button>
        <button class="btn btn-teal btn-premium" onclick="addNewLeadToPhase1()">
            <i class="bi bi-plus-lg me-1"></i> Add New Lead
        </button>
    </div>
</div>

<!-- Filters -->
<div class="premium-card p-3 mb-4">
    <div class="row g-3">
        <div class="col-md-4">
            <input type="text" class="form-control" id="pipelineSearch" placeholder="Search client or organization..." onkeyup="filterPipeline()">
        </div>
        <div class="col-md-3">
            <select class="form-select" id="assigneeFilter" onchange="filterPipeline()">
                <option value="">All Assignees</option>
                <option value="You">You (Sajid)</option>
                <option value="Rafiq">Rafiq</option>
                <option value="Nadia">Nadia</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="productFilter" onchange="filterPipeline()">
                <option value="">All Product Types</option>
                <option value="Inventory">Inventory Software</option>
                <option value="Pharmacy">Pharmacy Management</option>
                <option value="Expiry">Inventory with Expiry</option>
                <option value="Hosting">Hosting & Domain</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-premium w-100" onclick="resetPipelineFilters()">Reset Filters</button>
        </div>
    </div>
</div>

<!-- KANBAN BOARD -->
<div class="kanban-board d-flex gap-3 pb-4" style="overflow-x: auto; min-height: 620px;">
    
    <!-- Phase 1 -->
    <div class="kanban-column flex-shrink-0" data-phase="1">
        <div class="kanban-header rounded-top p-3 d-flex justify-content-between align-items-center" style="background: #0EA5E9; color: white;">
            <div><span class="fw-bold">Phase 1 • Targeted</span> <span class="badge bg-white text-info ms-2" id="count-phase-1">0</span></div>
            <small class="opacity-90" id="value-phase-1">৳0</small>
        </div>
        <div class="kanban-body bg-light p-2 rounded-bottom" id="phase-1" style="min-height: 520px; border: 2px dashed #bae6fd;"></div>
    </div>

    <!-- Phase 2 -->
    <div class="kanban-column flex-shrink-0" data-phase="2">
        <div class="kanban-header rounded-top p-3 d-flex justify-content-between align-items-center" style="background: #F59E0B; color: #1F2937;">
            <div><span class="fw-bold">Phase 2 • Quotation</span> <span class="badge bg-white text-warning ms-2" id="count-phase-2">0</span></div>
            <small class="opacity-90" id="value-phase-2">৳0</small>
        </div>
        <div class="kanban-body bg-light p-2 rounded-bottom" id="phase-2" style="min-height: 520px; border: 2px dashed #fde68a;"></div>
    </div>

    <!-- Phase 3 -->
    <div class="kanban-column flex-shrink-0" data-phase="3">
        <div class="kanban-header rounded-top p-3 d-flex justify-content-between align-items-center" style="background: #10B981; color: white;">
            <div><span class="fw-bold">Phase 3 • Agreed</span> <span class="badge bg-white text-success ms-2" id="count-phase-3">0</span></div>
            <small class="opacity-90" id="value-phase-3">৳0</small>
        </div>
        <div class="kanban-body bg-light p-2 rounded-bottom" id="phase-3" style="min-height: 520px; border: 2px dashed #a7f3d0;"></div>
    </div>

    <!-- Phase 4 -->
    <div class="kanban-column flex-shrink-0" data-phase="4">
        <div class="kanban-header rounded-top p-3 d-flex justify-content-between align-items-center" style="background: #3B82F6; color: white;">
            <div><span class="fw-bold">Phase 4 • Delivered</span> <span class="badge bg-white text-primary ms-2" id="count-phase-4">0</span></div>
            <small class="opacity-90" id="value-phase-4">৳0</small>
        </div>
        <div class="kanban-body bg-light p-2 rounded-bottom" id="phase-4" style="min-height: 520px; border: 2px dashed #bfdbfe;"></div>
    </div>

    <!-- Phase 5 -->
    <div class="kanban-column flex-shrink-0" data-phase="5">
        <div class="kanban-header rounded-top p-3 d-flex justify-content-between align-items-center" style="background: #9D174D; color: white;">
            <div><span class="fw-bold">Phase 5 • Review & Renew</span> <span class="badge bg-white text-pink ms-2" id="count-phase-5">0</span></div>
            <small class="opacity-90" id="value-phase-5">৳0</small>
        </div>
        <div class="kanban-body bg-light p-2 rounded-bottom" id="phase-5" style="min-height: 520px; border: 2px dashed #fbcfe8;"></div>
    </div>

</div>

<!-- Card Detail Modal -->
<div class="modal fade" id="cardDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold" id="modalCardTitle"></h5>
                    <small class="text-muted" id="modalCardOrg"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="mb-3"><span class="badge px-3 py-2" id="modalPhaseBadge"></span></div>
                        
                        <div class="small">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Estimated Value</span>
                                <span class="fw-bold text-success" id="modalValue"></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Days in Current Phase</span>
                                <span class="fw-semibold" id="modalDays"></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Assigned To</span>
                                <span class="fw-semibold" id="modalAssignee"></span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">Product Interest</span>
                                <span class="fw-semibold" id="modalProduct"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        <h6 class="fw-semibold mb-2">Quick Actions</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-premium" onclick="generateQuotationFromCard()">
                                <i class="bi bi-file-earmark-text me-2"></i> Generate / Edit Quotation
                            </button>
                            <button class="btn btn-outline-premium" onclick="addNoteToCard()">
                                <i class="bi bi-chat-left-text me-2"></i> Add Note / Log Activity
                            </button>
                            <button class="btn btn-outline-premium" onclick="moveToNextPhaseFromModal()">
                                <i class="bi bi-arrow-right-circle me-2"></i> Move to Next Phase
                            </button>
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="fw-semibold mb-1 small">Recent Notes</h6>
                            <div id="modalNotes" class="small text-muted border rounded p-2" style="min-height: 60px; max-height: 90px; overflow-y:auto;">
                                No notes yet.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-danger btn-sm" onclick="archiveCard()">Archive Lead</button>
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Quotation Modal -->
<div class="modal fade" id="quotationModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Create Quotation <span id="quoteClientName" class="text-teal"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-medium">Quotation Validity</label>
                        <input type="date" class="form-control" id="quoteValidity" value="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium">Payment Terms</label>
                        <select class="form-select" id="quoteTerms">
                            <option>50% Advance, 50% on Delivery</option>
                            <option>100% on Delivery</option>
                            <option>Monthly Installments (6 months)</option>
                            <option>Yearly Maintenance Contract</option>
                        </select>
                    </div>
                </div>

                <h6 class="fw-semibold mb-2">Line Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 32%;">Item / Service</th>
                                <th>Description</th>
                                <th style="width: 10%;">Qty</th>
                                <th style="width: 18%;">Unit Price (৳)</th>
                                <th style="width: 15%;">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="quoteItemsBody"></tbody>
                    </table>
                </div>
                <button class="btn btn-sm btn-outline-premium mb-3" onclick="addQuoteLine()">
                    <i class="bi bi-plus-lg"></i> Add Line Item
                </button>

                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <table class="table table-sm mb-0">
                            <tr><td class="text-end fw-medium">Subtotal</td><td class="text-end fw-bold" id="quoteSubtotal">৳0</td></tr>
                            <tr>
                                <td class="text-end fw-medium">Discount</td>
                                <td><input type="number" class="form-control form-control-sm text-end" id="quoteDiscount" value="0" oninput="calculateQuoteTotal()"></td>
                            </tr>
                            <tr class="border-top"><td class="text-end fw-bold">Grand Total</td><td class="text-end fw-bold fs-5 text-success" id="quoteGrandTotal">৳0</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-outline-premium" onclick="saveQuotationAsDraft()">Save Draft</button>
                <button class="btn btn-teal" onclick="sendQuotation()">Send Quotation</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
// ==================== DATA PERSISTENCE ====================
function loadPipelineData() {
    const saved = localStorage.getItem('phaseflow_pipeline');
    if (saved) {
        return JSON.parse(saved);
    }
    
    // Default demo data
    return [
        { id: 101, name: "Md. Karim Hossain", org: "Karim Traders Ltd.", phase: 1, value: 185000, days: 4, assignee: "You", product: "Inventory", tags: ["Inventory"], notes: [] },
        { id: 102, name: "Fatema Begum", org: "Sunrise Pharmacy", phase: 2, value: 245000, days: 7, assignee: "Rafiq", product: "Pharmacy", tags: ["Pharmacy"], notes: [] },
        { id: 103, name: "Rahim Uddin", org: "Rahim Traders", phase: 1, value: 95000, days: 2, assignee: "You", product: "Inventory", tags: ["Inventory"], notes: [] },
        { id: 104, name: "Nasrin Akter", org: "City Hospital", phase: 3, value: 420000, days: 12, assignee: "Nadia", product: "Expiry", tags: ["Expiry", "Custom"], notes: ["Quotation accepted on 10th June"] },
        { id: 105, name: "Jahangir Alam", org: "DeshiMart Ltd.", phase: 2, value: 165000, days: 5, assignee: "You", product: "Inventory", tags: ["Inventory"], notes: [] },
        { id: 106, name: "Salma Khatun", org: "MediCare Pharmacy", phase: 4, value: 310000, days: 28, assignee: "Rafiq", product: "Pharmacy", tags: ["Pharmacy"], notes: ["Project delivered successfully"] },
        { id: 107, name: "Abdul Kader", org: "Kader Enterprise", phase: 5, value: 78000, days: 45, assignee: "You", product: "Hosting", tags: ["Hosting"], notes: [] },
    ];
}

function savePipelineData() {
    localStorage.setItem('phaseflow_pipeline', JSON.stringify(pipelineData));
}

let pipelineData = loadPipelineData();
let currentCardId = null;
let currentQuoteClient = null;

// ==================== RENDER KANBAN ====================
function renderKanban(filteredData = null) {
    const dataToRender = filteredData || pipelineData;
    
    for (let i = 1; i <= 5; i++) {
        document.getElementById(`phase-${i}`).innerHTML = '';
    }
    
    let phaseCounts = {1:0, 2:0, 3:0, 4:0, 5:0};
    let phaseValues = {1:0, 2:0, 3:0, 4:0, 5:0};
    
    dataToRender.forEach(card => {
        const col = document.getElementById(`phase-${card.phase}`);
        if (!col) return;
        
        phaseCounts[card.phase]++;
        phaseValues[card.phase] += card.value;
        
        const lastMoved = card.lastMoved ? new Date(card.lastMoved).toLocaleDateString('en-GB', {day:'numeric', month:'short'}) : '';
        
        const cardHTML = `
            <div class="premium-card p-3 mb-2 kanban-card" data-id="${card.id}" onclick="showCardDetail(${card.id})">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="min-w-0">
                        <div class="fw-semibold text-truncate">${card.name}</div>
                        <div class="small text-muted text-truncate">${card.org}</div>
                    </div>
                    <span class="badge bg-light text-dark border px-2 py-1 flex-shrink-0" style="font-size:0.7rem;">${card.assignee}</span>
                </div>
                
                <div class="mt-2 d-flex justify-content-between align-items-center">
                    <div><span class="fw-bold text-success">৳${(card.value/1000).toFixed(0)}K</span></div>
                    <div class="small text-muted">${card.days}d ${lastMoved ? '• ' + lastMoved : ''}</div>
                </div>
                
                <div class="mt-2 d-flex flex-wrap gap-1">
                    ${card.tags.map(tag => `<span class="badge bg-teal-subtle text-teal px-2 py-1" style="font-size:0.7rem;">${tag}</span>`).join('')}
                </div>
            </div>
        `;
        col.innerHTML += cardHTML;
    });
    
    for (let i = 1; i <= 5; i++) {
        document.getElementById(`count-phase-${i}`).textContent = phaseCounts[i];
        document.getElementById(`value-phase-${i}`).textContent = '৳' + (phaseValues[i]/1000).toFixed(0) + 'K';
    }
    
    initSortable();
}

function initSortable() {
    document.querySelectorAll('.kanban-body').forEach(column => {
        new Sortable(column, {
            group: 'pipeline',
            animation: 180,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                const cardId = parseInt(evt.item.dataset.id);
                const newPhase = parseInt(evt.to.id.split('-')[1]);
                const oldPhase = pipelineData.find(c => c.id === cardId).phase;
                
                if (newPhase === oldPhase) return;
                
                handlePhaseChange(cardId, oldPhase, newPhase);
            }
        });
    });
}

function handlePhaseChange(cardId, oldPhase, newPhase) {
    const cardIndex = pipelineData.findIndex(c => c.id === cardId);
    if (cardIndex === -1) return;
    
    const card = pipelineData[cardIndex];
    let confirmMessage = `Move "${card.name}" from Phase ${oldPhase} to Phase ${newPhase}?`;
    
    if (newPhase === 3 && oldPhase === 2) {
        confirmMessage = `Excellent! ${card.name} accepted the quotation?\nThis will mark them as a Real Customer and move them to Polish phase.`;
    }
    if (newPhase === 5 && oldPhase === 4) {
        confirmMessage = `Warranty / support period complete for ${card.name}?\nMoving to Review & Renew phase.`;
    }
    
    if (confirm(confirmMessage)) {
        card.phase = newPhase;
        card.days = 0;
        card.lastMoved = new Date().toISOString();
        
        if (newPhase === 3) {
            showToast('Converted to Real Customer!', `${card.name} has been marked as a paying customer.`, 'success');
        }
        
        if (newPhase === 2) {
            setTimeout(() => {
                if (confirm('Would you like to create a quotation for this client now?')) {
                    generateQuotationFromCard(cardId);
                }
            }, 600);
        }
        
        savePipelineData();
        renderKanban();
    } else {
        renderKanban(); // revert
    }
}

// ==================== CARD DETAIL ====================
function showCardDetail(cardId) {
    const card = pipelineData.find(c => c.id === cardId);
    if (!card) return;
    currentCardId = cardId;
    
    document.getElementById('modalCardTitle').textContent = card.name;
    document.getElementById('modalCardOrg').textContent = card.org;
    document.getElementById('modalValue').textContent = '৳' + card.value.toLocaleString();
    document.getElementById('modalDays').textContent = card.days + ' days';
    document.getElementById('modalAssignee').textContent = card.assignee;
    document.getElementById('modalProduct').textContent = card.product;
    
    const phaseBadge = document.getElementById('modalPhaseBadge');
    phaseBadge.className = `badge px-3 py-2`;
    if (card.phase === 1) phaseBadge.classList.add('status-lead');
    else if (card.phase === 2) phaseBadge.classList.add('status-quotation');
    else if (card.phase === 3) phaseBadge.classList.add('status-agreed');
    else if (card.phase === 4) phaseBadge.classList.add('status-delivered');
    else phaseBadge.classList.add('status-review');
    phaseBadge.textContent = `Phase ${card.phase}`;
    
    // Notes
    const notesDiv = document.getElementById('modalNotes');
    if (card.notes && card.notes.length > 0) {
        notesDiv.innerHTML = card.notes.map(n => `<div class="mb-1">• ${n}</div>`).join('');
    } else {
        notesDiv.innerHTML = '<span class="text-muted">No notes yet.</span>';
    }
    
    new bootstrap.Modal(document.getElementById('cardDetailModal')).show();
}

function moveToNextPhaseFromModal() {
    if (!currentCardId) return;
    const card = pipelineData.find(c => c.id === currentCardId);
    if (!card || card.phase >= 5) return;
    
    bootstrap.Modal.getInstance(document.getElementById('cardDetailModal')).hide();
    setTimeout(() => handlePhaseChange(currentCardId, card.phase, card.phase + 1), 250);
}

function addNoteToCard() {
    if (!currentCardId) return;
    const card = pipelineData.find(c => c.id === currentCardId);
    if (!card) return;
    
    const note = prompt('Add a note or activity log:', '');
    if (!note || note.trim() === '') return;
    
    if (!card.notes) card.notes = [];
    card.notes.unshift(note.trim() + ' (' + new Date().toLocaleDateString('en-GB') + ')');
    
    savePipelineData();
    showCardDetail(currentCardId); // refresh modal
    showToast('Note Added', 'Activity logged successfully.', 'success');
}

function archiveCard() {
    if (!currentCardId || !confirm('Archive this lead? It will be removed from the active pipeline.')) return;
    
    pipelineData = pipelineData.filter(c => c.id !== currentCardId);
    bootstrap.Modal.getInstance(document.getElementById('cardDetailModal')).hide();
    savePipelineData();
    renderKanban();
    showToast('Lead Archived', 'The lead has been removed from the pipeline.', 'info');
}

// ==================== QUOTATION ====================
function generateQuotationFromCard(cardId = null) {
    const id = cardId || currentCardId;
    const card = pipelineData.find(c => c.id === id);
    if (!card) return;
    
    currentQuoteClient = card;
    bootstrap.Modal.getInstance(document.getElementById('cardDetailModal'))?.hide();
    
    document.getElementById('quoteClientName').textContent = `for ${card.name}`;
    document.getElementById('quoteItemsBody').innerHTML = '';
    
    // Smart pre-fill based on product
    let items = [];
    if (card.product === 'Pharmacy') {
        items = [
            {name: "Pharmacy Management System", desc: "Stock, sales, expiry & reporting", qty: 1, price: 145000},
            {name: "Barcode Scanner Integration", desc: "Hardware + software setup", qty: 2, price: 18500}
        ];
    } else if (card.product === 'Expiry') {
        items = [
            {name: "Inventory with Expiry Tracking", desc: "Full system + mobile app", qty: 1, price: 265000},
            {name: "Annual Maintenance", desc: "Updates + priority support", qty: 1, price: 55000}
        ];
    } else {
        items = [
            {name: "Core Inventory Software", desc: "Multi-branch + reporting", qty: 1, price: 95000},
            {name: "Customization & Training", desc: "On-site training + custom reports", qty: 1, price: 45000}
        ];
    }
    
    items.forEach(item => addQuoteLine(item));
    
    new bootstrap.Modal(document.getElementById('quotationModal')).show();
    setTimeout(calculateQuoteTotal, 150);
}

function addQuoteLine(item = null) {
    const tbody = document.getElementById('quoteItemsBody');
    const row = document.createElement('tr');
    
    const name = item ? item.name : "Custom Development";
    const desc = item ? item.desc : "Additional features";
    const qty = item ? item.qty : 1;
    const price = item ? item.price : 35000;
    
    row.innerHTML = `
        <td><input type="text" class="form-control form-control-sm" value="${name}" oninput="calculateQuoteTotal()"></td>
        <td><input type="text" class="form-control form-control-sm" value="${desc}" oninput="calculateQuoteTotal()"></td>
        <td><input type="number" class="form-control form-control-sm text-center" value="${qty}" oninput="calculateQuoteTotal()"></td>
        <td><input type="number" class="form-control form-control-sm text-end" value="${price}" oninput="calculateQuoteTotal()"></td>
        <td class="text-end fw-bold align-middle line-total">৳0</td>
        <td class="text-center"><button class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove(); calculateQuoteTotal()">×</button></td>
    `;
    tbody.appendChild(row);
    
    row.querySelectorAll('input').forEach(input => input.addEventListener('input', calculateQuoteTotal));
    calculateQuoteTotal();
}

function calculateQuoteTotal() {
    let subtotal = 0;
    document.querySelectorAll('#quoteItemsBody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('input[type="number"]:nth-child(3)').value) || 0;
        const price = parseFloat(row.querySelector('input[type="number"]:nth-child(4)').value) || 0;
        const total = qty * price;
        row.querySelector('.line-total').textContent = '৳' + total.toLocaleString();
        subtotal += total;
    });
    
    const discount = parseFloat(document.getElementById('quoteDiscount').value) || 0;
    document.getElementById('quoteSubtotal').textContent = '৳' + subtotal.toLocaleString();
    document.getElementById('quoteGrandTotal').textContent = '৳' + (subtotal - discount).toLocaleString();
}

function saveQuotationAsDraft() {
    showToast('Draft Saved', 'Quotation saved. You can find it in the Quotations module later.', 'success');
    bootstrap.Modal.getInstance(document.getElementById('quotationModal')).hide();
}

function sendQuotation() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('quotationModal'));
    modal.hide();
    
    showToast('Quotation Sent!', `Quotation sent to ${currentQuoteClient.name}.`, 'success');
    
    const card = pipelineData.find(c => c.id === currentQuoteClient.id);
    if (card && card.phase === 1) {
        setTimeout(() => {
            card.phase = 2;
            card.lastMoved = new Date().toISOString();
            savePipelineData();
            renderKanban();
        }, 900);
    }
}

// ==================== OTHER FUNCTIONS ====================
function addNewLeadToPhase1() {
    const name = prompt('Client Full Name:');
    if (!name) return;
    
    const newLead = {
        id: Date.now(),
        name: name,
        org: prompt('Organization:', 'New Company Ltd.') || 'New Company',
        phase: 1,
        value: parseInt(prompt('Estimated Value (৳):', '125000')) || 125000,
        days: 0,
        assignee: 'You',
        product: 'Inventory',
        tags: ['New Lead'],
        notes: [],
        lastMoved: new Date().toISOString()
    };
    
    pipelineData.unshift(newLead);
    savePipelineData();
    renderKanban();
    showToast('New Lead Added', `${name} added to Phase 1.`, 'success');
}

function filterPipeline() {
    const search = document.getElementById('pipelineSearch').value.toLowerCase();
    const assignee = document.getElementById('assigneeFilter').value;
    const product = document.getElementById('productFilter').value;
    
    const filtered = pipelineData.filter(card => {
        const matchSearch = card.name.toLowerCase().includes(search) || card.org.toLowerCase().includes(search);
        const matchAssignee = !assignee || card.assignee === assignee;
        const matchProduct = !product || card.product === product;
        return matchSearch && matchAssignee && matchProduct;
    });
    
    renderKanban(filtered);
}

function resetPipelineFilters() {
    document.getElementById('pipelineSearch').value = '';
    document.getElementById('assigneeFilter').value = '';
    document.getElementById('productFilter').value = '';
    renderKanban();
}

function resetDemoData() {
    if (!confirm('Reset all pipeline data to default demo?')) return;
    localStorage.removeItem('phaseflow_pipeline');
    pipelineData = loadPipelineData();
    renderKanban();
    showToast('Demo Data Reset', 'Pipeline has been restored to default demo data.', 'info');
}

// ==================== REVIEW SYSTEM (Phase 5 Enhancement) ====================
let currentReviewCardId = null;

function sendReviewRequest() {
    if (!currentCardId) return;
    const card = pipelineData.find(c => c.id === currentCardId);
    if (!card) return;
    
    bootstrap.Modal.getInstance(document.getElementById('cardDetailModal')).hide();
    
    setTimeout(() => {
        showToast('Review Request Sent', `Yearly review request email sent to ${card.name}.`, 'success');
        
        // Auto create a Review ticket
        if (typeof tickets !== 'undefined') {
            // This would normally be handled in Tickets module
        }
    }, 400);
}

function collectReview() {
    if (!currentCardId) return;
    currentReviewCardId = currentCardId;
    
    bootstrap.Modal.getInstance(document.getElementById('cardDetailModal')).hide();
    
    setTimeout(() => {
        // Reset stars
        document.querySelectorAll('#starRating span').forEach(s => s.textContent = '☆');
        document.getElementById('reviewRating').value = '5';
        document.getElementById('reviewText').value = '';
        
        new bootstrap.Modal(document.getElementById('reviewModal')).show();
    }, 400);
}

function setRating(rating) {
    document.getElementById('reviewRating').value = rating;
    const stars = document.querySelectorAll('#starRating span');
    stars.forEach((star, index) => {
        star.textContent = (index < rating) ? '★' : '☆';
    });
}

function submitReview() {
    if (!currentReviewCardId) return;
    
    const card = pipelineData.find(c => c.id === currentReviewCardId);
    const rating = document.getElementById('reviewRating').value;
    const text = document.getElementById('reviewText').value;
    const permission = document.getElementById('reviewPermission').checked;
    
    if (!text.trim()) {
        alert('Please write a short testimonial.');
        return;
    }
    
    // Save review
    let reviews = JSON.parse(localStorage.getItem('phaseflow_reviews') || '[]');
    reviews.push({
        id: Date.now(),
        clientId: currentReviewCardId,
        clientName: card.name,
        org: card.org,
        rating: parseInt(rating),
        text: text,
        permission: permission,
        date: new Date().toISOString().split('T')[0]
    });
    localStorage.setItem('phaseflow_reviews', JSON.stringify(reviews));
    
    bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
    
    showToast('Review Collected!', `Thank you! Review from ${card.name} has been saved.`, 'success');
    
    // Optional: Move card or mark as reviewed
    setTimeout(() => {
        if (confirm('Mark this client as reviewed for this year?')) {
            card.phase = 5; // stay in review phase
            card.lastReviewed = new Date().toISOString();
            savePipelineData();
            renderKanban();
        }
    }, 1000);
}

// Add Review Modal HTML if not exists
if (!document.getElementById('reviewModal')) {
    const reviewModalHTML = `
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold">Collect Client Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">How would you rate our service?</label>
                        <div class="d-flex gap-2 fs-4" id="starRating">
                            <span onclick="setRating(1)" style="cursor:pointer;">☆</span>
                            <span onclick="setRating(2)" style="cursor:pointer;">☆</span>
                            <span onclick="setRating(3)" style="cursor:pointer;">☆</span>
                            <span onclick="setRating(4)" style="cursor:pointer;">☆</span>
                            <span onclick="setRating(5)" style="cursor:pointer;">☆</span>
                        </div>
                        <input type="hidden" id="reviewRating" value="5">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">Testimonial / Feedback</label>
                        <textarea class="form-control" id="reviewText" rows="4" placeholder="What did you like about working with us?"></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="reviewPermission" checked>
                        <label class="form-check-label small" for="reviewPermission">
                            Allow us to publish this review
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" onclick="submitReview()">Submit Review</button>
                </div>
            </div>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', reviewModalHTML);
}

// Initial Load
document.addEventListener('DOMContentLoaded', function() {
    renderKanban();
    
    // Add drag feedback styles
    const style = document.createElement('style');
    style.innerHTML = `
        .sortable-ghost { opacity: 0.3; transform: scale(0.97); box-shadow: 0 10px 20px rgba(0,0,0,0.15); }
        .kanban-card { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); cursor: grab; }
        .kanban-card:hover { box-shadow: 0 8px 25px rgba(15, 23, 42, 0.12); transform: translateY(-2px); }
        .kanban-card:active { cursor: grabbing; }
    `;
    document.head.appendChild(style);
});
</script>