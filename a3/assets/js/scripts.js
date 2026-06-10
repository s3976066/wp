// PetConnect — shared JavaScript
document.addEventListener('DOMContentLoaded', () => {
    initImageValidation();
    initImagePreview();
    initGalleryModal();
    initStatusFilter();
    initDeleteConfirmation();
});

// ===== 1. Image file extension validation =====
function initImageValidation() {
    const input = document.querySelector('#imageInput');
    if (!input) return;

    const errorEl = document.querySelector('#imageError');
    const previewWrapper = document.querySelector('#previewWrapper');
    const previewImg = document.querySelector('#imagePreview');
    const previewMeta = document.querySelector('#previewMeta');

    const allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;

        const ext = file.name.split('.').pop().toLowerCase();

        if (!allowed.includes(ext)) {
            if (errorEl) {
                errorEl.textContent = 'Invalid file type. Allowed: jpg, jpeg, png, gif, webp';
                errorEl.classList.remove('d-none');
            }
            input.value = '';
            if (previewWrapper) previewWrapper.classList.add('d-none');
            return;
        }

        if (errorEl) errorEl.classList.add('d-none');
        showPreview(file, previewImg, previewMeta, previewWrapper);
    });

    function showPreview(file, imgEl, metaEl, wrapperEl) {
        const reader = new FileReader();
        reader.onload = () => {
            if (imgEl) imgEl.src = reader.result;
            if (metaEl) metaEl.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
            if (wrapperEl) wrapperEl.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
}

// ===== 2. Image preview (no-op, handled by initImageValidation) =====
function initImagePreview() {
    return;
}

// ===== 3. Gallery image modal =====
function initGalleryModal() {
    const images = document.querySelectorAll('.gallery-img');
    const modal = document.querySelector('#galleryModal');
    if (images.length === 0 || !modal) return;

    const modalImage = modal.querySelector('#galleryModalImage');
    const modalLabel = modal.querySelector('#galleryModalLabel');

    images.forEach(img => {
        img.addEventListener('click', e => {
            e.preventDefault();
            if (modalImage) modalImage.src = img.src;
            if (modalLabel) modalLabel.textContent = img.dataset.petName || '';
            new bootstrap.Modal(modal).show();
        });
    });
}

// ===== 4. Species dropdown filter =====
function initStatusFilter() {
    const select = document.querySelector('#speciesFilter');
    if (!select) return;

    const cards = document.querySelectorAll('.pet-card');
    if (cards.length === 0) return;

    select.addEventListener('change', () => {
        const val = select.value;
        cards.forEach(card => {
            card.style.display = (val === 'all' || card.dataset.species === val) ? '' : 'none';
        });
    });
}

// ===== 5. Delete confirmation modal =====
function initDeleteConfirmation() {
    const deleteBtn = document.querySelector('#deleteBtn');
    const deleteModal = document.querySelector('#deleteModal');
    const confirmBtn = document.querySelector('#confirmDelete');
    if (!deleteBtn || !deleteModal || !confirmBtn) return;

    confirmBtn.addEventListener('click', () => {
        const form = document.querySelector('#deleteForm');
        if (form) form.submit();
    });
}
