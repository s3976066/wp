// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Image file extension validation and preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imageError = document.getElementById('imageError');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                // Validate file extension
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                const fileName = file.name.toLowerCase();
                const fileExtension = fileName.split('.').pop();

                if (!allowedExtensions.includes(fileExtension)) {
                    imageError.textContent = 'Invalid file type. Please upload an image file (jpg, jpeg, png, gif, webp).';
                    imageInput.value = '';
                    imagePreviewContainer.style.display = 'none';
                    return;
                }

                // Clear error and show preview
                imageError.textContent = '';

                // Create image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreviewContainer.style.display = 'none';
            }
        });
    }

    // Form validation
    const petForm = document.getElementById('petForm');
    if (petForm) {
        petForm.addEventListener('submit', function(e) {
            if (!petForm.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }

            petForm.classList.add('was-validated');

            // If form is valid, show success message (for demo purposes)
            if (petForm.checkValidity()) {
                e.preventDefault();
                alert('Pet added successfully! (This is a demo - no data is actually saved)');
                petForm.reset();
                petForm.classList.remove('was-validated');
                imagePreviewContainer.style.display = 'none';
            }
        });
    }

    // Gallery modal functionality
    const galleryImages = document.querySelectorAll('.gallery-img');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');

    if (galleryImages.length > 0 && modalImage) {
        galleryImages.forEach(image => {
            image.addEventListener('click', function() {
                const imgSrc = this.src;
                const imgAlt = this.alt;

                modalImage.src = imgSrc;
                modalTitle.textContent = imgAlt;
            });
        });
    }

    // Gallery filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    if (filterButtons.length > 0 && galleryItems.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
                filterButtons.forEach(btn => btn.classList.add('btn-outline-primary'));
                this.classList.remove('btn-outline-primary');
                this.classList.add('active', 'btn-primary');

                // Filter items
                const filter = this.getAttribute('data-filter');

                galleryItems.forEach(item => {
                    if (filter === 'all') {
                        item.style.display = 'block';
                    } else {
                        const itemStatus = item.getAttribute('data-status');
                        if (itemStatus === filter) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });
            });
        });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add animation on scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.card, .gallery-item, .table');

        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;

            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('animate-fadeIn');
            }
        });
    };

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Run once on load
});

// Helper function to validate image file extensions
function validateImageExtension(filename) {
    const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const extension = filename.split('.').pop().toLowerCase();
    return allowedExtensions.includes(extension);
}
