@extends('/layouts/main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/transaction.js"></script>
<script src="/js/transaction_table.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
@endpush

@section('content')
<main>
  <div class="container-fluid px-4 mt-4">
    <!-- flasher -->
    @if(session()->has('message'))
    {!! session("message") !!}
    @endif

    @include('/partials/breadcumb')

    <div class="card mb-4">
      <div class="card-header">
        <i class="fas fa-fw fa-solid fa-money-check-dollar me-1"></i>
        Transaction
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
            <i class="fas fa-plus"></i> Tambah Transaksi
          </button>
        </div>

        <table id="transaction_table" class="table table-bordered">
          <thead>
            <tr>
              <th>No</th>
              <th>Kategori</th>
              <th>Deskripsi</th>
              <th>Pemasukan</th>
              <th>Pengeluaran</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($transactions as $index => $transaction)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $transaction->category->category_name }}</td>
              <td>{{ $transaction->description }}</td>
              <td>
                @if($transaction->income)
                <span class="text-success">
                  Rp {{ number_format($transaction->income, 0, ',', '.') }}
                </span>
                @else
                -
                @endif
              </td>
              <td>
                @if($transaction->outcome)
                <span class="text-danger">
                  Rp {{ number_format($transaction->outcome, 0, ',', '.') }}
                </span>
                @else
                -
                @endif
              </td>
              <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
              <td>
                <button class="btn btn-sm btn-warning" onclick="editTransaction({{ $transaction->id }})">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteTransaction({{ $transaction->id }})">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Add Transaction Modal -->
  <div class="modal fade" id="addTransactionModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Transaksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="{{ route('transaction.store') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <select class="form-select" name="category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Jenis Transaksi</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="transaction_type" id="income" value="income" checked>
                <label class="form-check-label" for="income">
                  Pemasukan
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="transaction_type" id="outcome" value="outcome">
                <label class="form-check-label" for="outcome">
                  Pengeluaran
                </label>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Jumlah</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" name="amount" min="0" required>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>

@push('scripts')
<script>
function editTransaction(id) {
  window.location.href = `/transaction/${id}/edit`;
}

function deleteTransaction(id) {
  if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
    fetch(`/transaction/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        alert('Gagal menghapus transaksi: ' + data.message);
      }
    })
    .catch(error => {
      alert('Terjadi kesalahan: ' + error);
    });
  }
}

$(document).ready(function() {
  $('#transaction_table').DataTable({
    order: [[5, 'desc']], // Sort by date column descending
    language: {
      url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
    }
  });
});
</script>
@endpush
@endsection