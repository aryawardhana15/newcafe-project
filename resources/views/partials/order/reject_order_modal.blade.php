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