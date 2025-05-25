<!-- Upload Payment Proof Modal -->
<div class="modal fade" id="uploadProofModal" tabindex="-1" aria-labelledby="uploadProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-b bg-gray-50 p-4">
                <h5 class="modal-title text-lg font-semibold text-gray-800" id="uploadProofModalLabel">
                    Upload Bukti Pembayaran
                </h5>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="uploadProofForm" class="relative" data-no-loading>
                @csrf
                <input type="hidden" name="old_image_proof" id="old_image_proof" value="{{ env('IMAGE_PROOF') }}">
                
                <div class="modal-body p-6">
                    <!-- Error Alert -->
                    <div id="uploadError" class="hidden mb-4">
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p class="font-bold">Error</p>
                            <p id="errorMessage"></p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Image Preview -->
                        <div class="flex justify-center">
                            <div class="relative w-64 h-64 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex justify-center items-center">
                                <img src="" alt="Preview" id="image_preview" 
                                     class="absolute inset-0 w-full h-full object-contain p-2 hidden">
                                <div class="text-center" id="placeholder_text">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Klik untuk memilih gambar atau drag and drop
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        PNG, JPG, GIF up to 2MB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- File Input -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">
                                File Bukti Pembayaran
                            </label>
                            <div class="mt-1">
                                <input type="file" 
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                                       id="image_upload_proof" 
                                       name="image_upload_proof" 
                                       accept="image/*" 
                                       required>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Format yang diterima: JPG, PNG, GIF (max 2MB)
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-gray-50 px-6 py-3">
                    <button type="button" 
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" 
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" 
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Upload Bukti
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Preview image before upload
    $('#image_upload_proof').on('change', function(e) {
        const file = e.target.files[0];
        const preview = $('#image_preview');
        const placeholder = $('#placeholder_text');
        
        if (file) {
            // Validate file size
            if (file.size > 2 * 1024 * 1024) { // 2MB
                showError('Ukuran file terlalu besar. Maksimal 2MB.');
                this.value = '';
                return;
            }

            // Validate file type
            if (!file.type.match('image.*')) {
                showError('File harus berupa gambar (JPG, PNG, atau GIF).');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.attr('src', e.target.result).removeClass('hidden');
                placeholder.addClass('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.addClass('hidden');
            placeholder.removeClass('hidden');
        }
    });

    // Form submission
    $('#uploadProofForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        
        // Disable submit button
        submitBtn.prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Hide modal
                $('#uploadProofModal').modal('hide');
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Bukti pembayaran berhasil diupload',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    // Reload page
                    window.location.reload();
                });
            },
            error: function(xhr) {
                // Show error message
                const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat mengupload bukti pembayaran';
                showError(message);
                
                // Enable submit button
                submitBtn.prop('disabled', false);
            }
        });
    });

    // Helper function to show error message
    function showError(message) {
        $('#errorMessage').text(message);
        $('#uploadError').removeClass('hidden');
    }

    // Reset form when modal is hidden
    $('#uploadProofModal').on('hidden.bs.modal', function() {
        const form = $(this).find('form');
        form[0].reset();
        $('#image_preview').addClass('hidden');
        $('#placeholder_text').removeClass('hidden');
        $('#uploadError').addClass('hidden');
        form.find('button[type="submit"]').prop('disabled', false);
    });

    // Drag and drop functionality
    const dropZone = document.querySelector('.border-dashed');
    const fileInput = document.getElementById('image_upload_proof');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            fileInput.files = files;
            $(fileInput).trigger('change');
        }
    }
});
</script>
@endpush 