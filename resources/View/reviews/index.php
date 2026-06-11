<?php
/**
 * Collected Reviews Page - PhaseFlow CRM
 * View all client testimonials and reviews
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="section-header mb-1">Collected Reviews</h1>
        <p class="text-muted mb-0">All client testimonials and feedback received</p>
    </div>
</div>

<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="premium-card p-4 text-center">
            <div class="text-muted small">Total Reviews</div>
            <div class="fs-2 fw-bold text-teal" id="totalReviews">0</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="premium-card p-4 text-center">
            <div class="text-muted small">Average Rating</div>
            <div class="fs-2 fw-bold text-warning" id="avgRating">0.0</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="premium-card p-4 text-center">
            <div class="text-muted small">Published Permission</div>
            <div class="fs-2 fw-bold text-success" id="publishedCount">0</div>
        </div>
    </div>
</div>

<!-- Reviews List -->
<div class="premium-card p-4">
    <div id="reviewsList">
        <!-- Populated by JavaScript -->
    </div>
</div>

<script>
function loadAndRenderReviews() {
    const reviews = JSON.parse(localStorage.getItem('phaseflow_reviews') || '[]');
    const container = document.getElementById('reviewsList');
    container.innerHTML = '';
    
    if (reviews.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5 text-muted">
                <i class="bi bi-chat-quote fs-1"></i>
                <h5 class="mt-3">No reviews collected yet</h5>
                <p class="small">Reviews collected from Pipeline Phase 5 will appear here.</p>
            </div>
        `;
        return;
    }
    
    // Calculate stats
    let totalRating = 0;
    let published = 0;
    
    reviews.forEach(review => {
        totalRating += review.rating;
        if (review.permission) published++;
    });
    
    document.getElementById('totalReviews').textContent = reviews.length;
    document.getElementById('avgRating').textContent = (totalRating / reviews.length).toFixed(1);
    document.getElementById('publishedCount').textContent = published;
    
    // Render reviews
    reviews.forEach(review => {
        const stars = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);
        
        const div = document.createElement('div');
        div.className = 'border rounded p-4 mb-3';
        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-semibold">${review.clientName}</div>
                    <div class="small text-muted">${review.org || ''}</div>
                </div>
                <div class="text-end">
                    <div class="text-warning fs-5">${stars}</div>
                    <div class="small text-muted">${review.date}</div>
                </div>
            </div>
            
            <div class="mt-3 fst-italic">
                "${review.text}"
            </div>
            
            <div class="mt-3">
                ${review.permission 
                    ? '<span class="badge bg-success-subtle text-success px-3 py-1">Approved for publishing</span>' 
                    : '<span class="badge bg-secondary-subtle text-secondary px-3 py-1">Private</span>'}
            </div>
        `;
        container.appendChild(div);
    });
}

// Initial Load
document.addEventListener('DOMContentLoaded', function() {
    loadAndRenderReviews();
});
</script>