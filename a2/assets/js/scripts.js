/**
 * scripts.js
 * PetConnect – Custom JavaScript
 * Extended from AT1 for AT2 dynamic features.
 * No inline JavaScript used on any page.
 * Student: Yizhao Zheng | s3976066
 */

'use strict';

/* ==========================================================
   1. IMAGE EXTENSION VALIDATION & PREVIEW  (add.php)
   ========================================================== */
function initImageUpload() {
    const imageInput    = document.getElementById('image');
    const previewImg    = document.getElementById('imagePreview');
    const previewBox    = document.getElementById('imagePreviewContainer');
    const alertValid    = document.getElementById('alertValid');
    const alertInvalid  = document.getElementById('alertInvalid');

    if (!imageInput) return;   // Not on add.php – exit

    const ALLOWED = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    imageInput.addEventListener('change', function () {
        const file = this.files[0];

        // Reset all states
        if (alertValid)   { alertValid.style.display   = 'none'; }
        if (alertInvalid) { alertInvalid.style.display = 'none'; }
        if (previewBox)   { previewBox.style.display   = 'none'; }

        if (!file) return;

        const ext = file.name.split('.').pop().toLowerCase();

        if (!ALLOWED.includes(ext)) {
            // Invalid extension
            if (alertInvalid) {
                alertInvalid.textContent = 'Invalid file type. Allowed formats: JPG, JPEG, PNG, GIF, WEBP.';
                alertInvalid.style.display = 'flex';
            }
            imageInput.value = '';  // Clear selection
            return;
        }

        // Valid – show success + preview
        const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        if (alertValid) {
            alertValid.textContent = 'Valid image selected: ' + file.name + ' (' + sizeMB + ' MB)';
            alertValid.style.display = 'flex';
        }

        // FileReader preview
        const reader = new FileReader();
        reader.onload = function (e) {
            if (previewImg) { previewImg.src = e.target.result; }
            if (previewBox) { previewBox.style.display = 'block'; }
        };
        reader.readAsDataURL(file);
    });
}

/* ==========================================================
   2. GALLERY MODAL  (gallery.php)
   ========================================================== */
function initGalleryModal() {
    const modalEl    = document.getElementById('petModal');
    if (!modalEl) return;   // Not on gallery.php – exit

    const modalImg   = document.getElementById('modalPetImg');
    const modalTitle = document.getElementById('petModalLabel');
    const triggers   = document.querySelectorAll('[data-bs-target="#petModal"]');

    triggers.forEach(function (el) {
        el.addEventListener('click', function () {
            const src  = this.getAttribute('data-img-src');
            const name = this.getAttribute('data-pet-name');
            if (modalImg)   { modalImg.src         = src;  modalImg.alt = name; }
            if (modalTitle) { modalTitle.textContent = name; }
        });
    });
}

/* ==========================================================
   3. GALLERY STATUS FILTER – dropdown (gallery.php)
   ========================================================== */
function initGalleryFilter() {
    const filterSelect = document.getElementById('statusFilter');
    if (!filterSelect) return;   // Not on gallery.php – exit

    filterSelect.addEventListener('change', function () {
        const selected = this.value.toLowerCase();
        const cards    = document.querySelectorAll('[data-status]');

        cards.forEach(function (card) {
            const status = card.getAttribute('data-status').toLowerCase();
            card.style.display = (selected === 'all' || status === selected) ? '' : 'none';
        });
    });
}

/* ==========================================================
   4. SCROLL ANIMATION  (carried over from AT1)
   ========================================================== */
function initScrollAnimation() {
    const animateOnScroll = function () {
        document.querySelectorAll('.card, .gallery-card, .pet-card').forEach(function (el) {
            if (el.getBoundingClientRect().top < window.innerHeight - 100) {
                el.style.opacity  = '1';
                el.style.transform = 'translateY(0)';
            }
        });
    };
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll();
}

/* ==========================================================
   5. INITIALISE ON DOM READY
   ========================================================== */
document.addEventListener('DOMContentLoaded', function () {
    initImageUpload();
    initGalleryModal();
    initGalleryFilter();
    initScrollAnimation();
});
