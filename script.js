document.addEventListener('DOMContentLoaded', function() {
    // Form validation with better UX
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = 'var(--error)';
                    field.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
                    
                    // Add error message
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('field-error')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'field-error';
                        errorDiv.style.color = 'var(--error)';
                        errorDiv.style.fontSize = '0.8rem';
                        errorDiv.style.marginTop = '5px';
                        errorDiv.textContent = 'This field is required';
                        field.parentNode.appendChild(errorDiv);
                    }
                } else {
                    field.style.borderColor = 'var(--medium-gray)';
                    field.style.boxShadow = 'none';
                    
                    // Remove error message
                    const errorDiv = field.parentNode.querySelector('.field-error');
                    if (errorDiv) {
                        errorDiv.remove();
                    }
                }
            });
            
            // Password confirmation validation
            const password = form.querySelector('input[name="password"]');
            const confirmPassword = form.querySelector('input[name="confirm_password"]');
            if (password && confirmPassword && password.value !== confirmPassword.value) {
                valid = false;
                confirmPassword.style.borderColor = 'var(--error)';
                confirmPassword.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
                
                if (!document.querySelector('.password-mismatch')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error password-mismatch';
                    errorDiv.style.marginTop = '1rem';
                    errorDiv.textContent = '❌ Passwords do not match!';
                    form.prepend(errorDiv);
                }
            }
            
            if (!valid) {
                e.preventDefault();
                
                // Smooth scroll to first error
                const firstError = form.querySelector('[style*="border-color: var(--error)"]');
                if (firstError) {
                    firstError.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                    firstError.focus();
                }
            }
        });
    });
    
    // Date validation for pickup requests
    const pickupDate = document.querySelector('input[name="pickup_date"]');
    if (pickupDate) {
        const today = new Date().toISOString().split('T')[0];
        pickupDate.min = today;
        
        // Add date validation
        pickupDate.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            
            if (selectedDate < today) {
                this.style.borderColor = 'var(--error)';
                this.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
            } else {
                this.style.borderColor = 'var(--medium-gray)';
                this.style.boxShadow = 'none';
            }
        });
    }
    
    // Add loading states to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.type === 'submit' || this.getAttribute('href')) {
                this.style.opacity = '0.7';
                this.style.pointerEvents = 'none';
                
                // Add loading spinner
                const originalText = this.innerHTML;
                this.innerHTML = '⏳ Processing...';
                
                // Restore after 2 seconds if still on same page
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.opacity = '1';
                    this.style.pointerEvents = 'auto';
                }, 2000);
            }
        });
    });
    
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.01)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});

// Delete modal functions
let deleteId = null;

function confirmDelete(requestId, wasteType) {
    deleteId = requestId;
    document.getElementById('requestDetails').textContent = 'Request #' + requestId + ' - ' + wasteType;
    document.getElementById('deleteModal').style.display = 'block';
    
    // Add animation
    const modal = document.getElementById('deleteModal');
    modal.style.animation = 'fadeIn 0.3s ease';
}

function cancelDelete() {
    deleteId = null;
    const modal = document.getElementById('deleteModal');
    modal.style.animation = 'fadeOut 0.3s ease';
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

function proceedDelete() {
    if (deleteId) {
        // Show loading state
        const deleteBtn = document.querySelector('.modal-buttons .btn-danger');
        const originalText = deleteBtn.innerHTML;
        deleteBtn.innerHTML = '⏳ Deleting...';
        deleteBtn.disabled = true;
        
        // Proceed with deletion
        setTimeout(() => {
            window.location.href = 'my_requests.php?delete_id=' + deleteId;
        }, 500);
    }
}

// Enhanced modal handling
document.addEventListener('click', function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        cancelDelete();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        cancelDelete();
    }
});

// Add CSS animation for fadeOut
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
`;
document.head.appendChild(style);