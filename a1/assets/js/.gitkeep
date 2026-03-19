document.addEventListener('DOMContentLoaded', function() {

    // 1. 状态筛选逻辑 (gallery.html) [cite: 116]
    const filter = document.getElementById('statusFilter');
    if (filter) {
        filter.addEventListener('change', function() {
            const selectedStatus = this.value;
            document.querySelectorAll('.pet-card-wrapper').forEach(card => {
                const status = card.dataset.status;
                if (selectedStatus === 'All' || status === selectedStatus) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // 2. 图片格式验证与预览 (add.html) [cite: 111-113]
    const fileInput = document.getElementById('image');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            const allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            const ext = file.name.split('.').pop().toLowerCase();

            if (!allowed.includes(ext)) {
                alert('Invalid file format. Only jpg, jpeg, png, gif, and webp are allowed.');
                this.value = ''; // 清除不合法的文件
                document.getElementById('imagePreview').classList.add('d-none');
                return;
            }

            // 格式正确，显示预览
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('imagePreview');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        });
    }

    // 3. 模态框大图展示 (gallery.html) [cite: 114-115]
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.addEventListener('show.bs.modal', function(event) {
            const triggerImage = event.relatedTarget;
            const modalImg = document.getElementById('modalImg');
            modalImg.src = triggerImage.src;
        });
    }

});