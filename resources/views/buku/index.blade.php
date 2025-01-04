@include('template.header')
@include('template.sidebar')

<div id="loadingIndicator"
    style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<div class="container-fluid">
    <div class="form-group">
        <label for="filter_kategori">Filter berdasarkan Kategori</label>
        <select class="form-control" id="filter_kategori" name="kategori_id">
            <option value="">Semua Kategori</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-sm btn-primary mb-3" data-toggle="modal" data-target="#tambah_buku"><i
            class="fas fa-plus fa-sm"></i> Tambah Buku</button>
    <h2 class="mb-4">Daftar Buku</h2>

    <div id="alertMessage" class="container mt-3" style="display: none;"></div>

    <div class="toast" id="toastNotification" style="position: absolute; top: 20px; right: 20px; z-index: 9999;">
        <div class="toast-header">
            <strong class="mr-auto">Pesan</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            <span id="toastMessage">Pesan berhasil</span>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Judul</th>
                <th class="text-center">Deskripsi</th>
                <th class="text-center">Kategori</th>
                <th class="text-center">Gambar</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody id="bukuList">
            @if ($bukus->isEmpty())
                <tr>
                    <td colspan="6" class="text-center">Data tidak ada</td>
                </tr>
            @else
                @foreach ($bukus as $index => $buku)
                    <tr id="buku_{{ $buku->id }}">
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $buku->judul }}</td>
                        <td>{{ $buku->deskripsi }}</td>
                        <td>{{ $buku->kategori->nama_kategori }}</td>
                        <td class="text-center">
                            @if ($buku->gambar)
                                <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}"
                                    width="50" height="50">
                            @else
                                <span class="text-muted">Tidak ada gambar</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-sm editBuku" data-id="{{ $buku->id }}"
                                data-judul="{{ $buku->judul }}" data-deskripsi="{{ $buku->deskripsi }}"
                                data-kategori="{{ $buku->kategori_id }}" data-toggle="modal"
                                data-target="#edit_buku"><i class="fa fa-edit"></i> Edit</button>
                            <button class="btn btn-danger btn-sm deleteBuku" data-id="{{ $buku->id }}"
                                data-toggle="modal" data-target="#deleteModal"><i class="fa fa-trash"></i>
                                Hapus</button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>


<div class="modal fade" id="tambah_buku" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Buku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formBuku" method="post" action="{{ route('buku.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="judul">Judul</label>
                        <input type="text" class="form-control" id="judul" name="judul" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
                        <select class="form-control" id="kategori_id" name="kategori_id" required>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gambar">Gambar</label>
                        <input type="file" class="form-control" id="gambar" name="gambar">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="edit_buku" data-id="" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Buku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditBuku" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_judul">Judul</label>
                        <input type="text" class="form-control" id="edit_judul" name="judul" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_deskripsi">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_kategori_id">Kategori</label>
                        <select class="form-control" id="edit_kategori_id" name="kategori_id" required>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_gambar">Gambar Baru (optional)</label>
                        <input type="file" class="form-control" id="edit_gambar" name="gambar">
                        <small class="form-text text-muted">Jika tidak mengganti gambar, biarkan kosong.</small>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function showToast(message) {
            $('#toastMessage').text(message);
            $('#toastNotification').toast({
                delay: 3000
            });
            $('#toastNotification').toast('show');
        }

        function showLoading() {
            $('#loadingIndicator').show();
        }

        function hideLoading() {
            $('#loadingIndicator').hide();
        }

        $('#formBuku').submit(function(e) {
            e.preventDefault();
            var fileInput = $('#gambar')[0];
            var file = fileInput.files[0];

            if (file && file.size > 2 * 1024 * 1024) {
                showToast('Ukuran file terlalu besar. Maksimal 2MB.');
                return;
            }

            var validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (file && !validTypes.includes(file.type)) {
                showToast('Format file tidak valid. Harus berupa file gambar (jpeg, png, jpg, gif).');
                return;
            }

            var formData = new FormData(this);
            showLoading();
            $.ajax({
                url: '{{ route('buku.store') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    hideLoading();
                    $('#tambah_buku').modal('hide');
                    showToast(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    showToast('Terjadi kesalahan');
                }
            });
        });

        $('#formEditBuku').submit(function(e) {
            e.preventDefault();
            var bukuId = $('#edit_buku').data('id');
            var formData = new FormData(this);
            var fileInput = $('#edit_gambar')[0];
            var file = fileInput.files[0];

            if (file && file.size > 2 * 1024 * 1024) {
                showToast('Ukuran file terlalu besar. Maksimal 2MB.');
                return;
            }

            var validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (file && !validTypes.includes(file.type)) {
                showToast('Format file tidak valid. Harus berupa file gambar (jpeg, png, jpg, gif).');
                return;
            }
            showLoading();
            $.ajax({
                url: `/buku/${bukuId}`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                },
                success: function(response) {
                    hideLoading();
                    $('#edit_buku').modal('hide');
                    showToast(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    showToast('Terjadi kesalahan saat mengupdate buku.');
                },
            });
        });

        $('.editBuku').on('click', function() {
            var bukuId = $(this).data('id');
            var judul = $(this).data('judul');
            var deskripsi = $(this).data('deskripsi');
            var kategoriId = $(this).data('kategori');

            $('#edit_buku').data('id', bukuId);
            $('#edit_judul').val(judul);
            $('#edit_deskripsi').val(deskripsi);
            $('#edit_kategori_id').val(kategoriId);
        });

        var bukuIdToDelete;
        $('.deleteBuku').on('click', function() {
            bukuIdToDelete = $(this).data('id');
        });

        $('#confirmDelete').on('click', function() {
            showLoading();
            $.ajax({
                url: `/buku/${bukuIdToDelete}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hideLoading();
                    $('#deleteModal').modal('hide');
                    showToast(response.message);
                    $(`#buku_${bukuIdToDelete}`).remove();
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    showToast('Terjadi kesalahan saat menghapus buku.');
                }
            });
        });
        $('#filter_kategori').change(function() {
            var kategoriId = $(this).val();
            var url = '{{ route('buku.index') }}';
            if (kategoriId) {
                url += '?kategori_id=' + kategoriId;
            }

            showLoading();

            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    hideLoading();

                    $('#bukuList').html(response);
                },
                error: function(xhr, status, error) {
                    hideLoading();
                    showToast('Terjadi kesalahan saat memuat data.');
                }
            });
        });
    });
</script>


@include('template.footer')
