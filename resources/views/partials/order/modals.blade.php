<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailModalLabel">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Informasi Produk</h6>
                        <div class="product-info"></div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Informasi Pengiriman</h6>
                        <div class="shipping-info"></div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6 class="mb-3">Informasi Pembayaran</h6>
                        <div class="payment-info"></div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Status Pesanan</h6>
                        <div class="status-info"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

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
                        <label for="proofImage" class="form-label">Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="proofImage" name="image_upload_proof" accept="image/*" required>
                        <div class="form-text">Format: JPG, PNG. Maksimal 2MB</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Order Modal -->
<div class="modal fade" id="rejectOrderModal" tabindex="-1" aria-labelledby="rejectOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectOrderModalLabel">Tolak Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectOrderForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="refusalReason" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="refusalReason" name="refusal_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</div> 