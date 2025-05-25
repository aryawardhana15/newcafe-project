<!-- Upload Proof Modal -->
<div class="modal fade" id="uploadProofModal" tabindex="-1" aria-labelledby="uploadProofModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadProofModalLabel">Upload Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadProofForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="image_upload_proof" class="form-label">Bukti Transfer</label>
                        <input type="file" class="form-control" id="image_upload_proof" name="image_upload_proof" accept="image/*" required>
                        <div class="form-text">Format: JPG, PNG, JPEG. Maksimal 2MB</div>
                    </div>
                    <!-- Preview Image -->
                    <div class="mt-3 text-center d-none" id="imagePreview">
                        <img src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload Bukti</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview image before upload
    document.getElementById('image_upload_proof').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('d-none');
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    });

    // Handle form submission
    document.getElementById('uploadProofForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = this.action;
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            // Show error message
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message || 'Terjadi kesalahan saat upload bukti pembayaran'
            });
        })
        .finally(() => {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            // Reset form and close modal
            document.getElementById('uploadProofModal').querySelector('.btn-close').click();
            e.target.reset();
            document.getElementById('imagePreview').classList.add('d-none');
        });
    });
});</script>