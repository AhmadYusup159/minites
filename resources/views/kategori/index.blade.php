@include('template.header')
@include('template.sidebar')

<div class="container-fluid">
    <button class="btn btn-sm btn-primary mb-3" data-toggle="modal" data-target="#tambah_kategori"><i
            class="fas fa-plus fa-sm"></i>Tambah Kategori</button>
    <h2 class="mb-4">Daftar Kategori</h2>
    <div id="alertMessage" class="container mt-3" style="display: none;">
    </div>
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
                <th class="text-center">Nama Kategori</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody id="kategoriList">
            @foreach ($kategoris as $index => $kategori)
                <tr id="kategori_{{ $kategori->id }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $kategori->nama_kategori }}</td>
                    <td class="text-center">
                        <button class="btn btn-primary btn-sm editKategori" data-id="{{ $kategori->id }}"
                            data-nama="{{ $kategori->nama_kategori }}" data-toggle="modal"
                            data-target="#edit_kategori"><i class="fa fa-edit"></i> Edit</button>
                        <button class="btn btn-danger btn-sm deleteKategori" data-id="{{ $kategori->id }}"
                            data-toggle="modal" data-target="#deleteModal">
                            <i class="fa fa-trash"></i> Hapus
                        </button>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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

<div class="modal fade" id="tambah_kategori" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formKategori" method="post" action="{{ route('kategori.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
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

<div class="modal fade" id="edit_kategori" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditKategori" method="post" action="">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_nama_kategori">Nama Kategori</label>
                        <input type="text" class="form-control" id="edit_nama_kategori" name="nama_kategori"
                            required>
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

        $('#formKategori').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: '{{ route('kategori.store') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    showToast(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function() {
                    showToast('Terjadi kesalahan');
                }
            });
        });

        $(document).on('click', '.editKategori', function() {
            var kategoriId = $(this).data('id');
            var kategoriNama = $(this).data('nama');
            $('#edit_nama_kategori').val(kategoriNama);
            $('#formEditKategori').attr('action', '/kategori/' + kategoriId);
        });

        $('#formEditKategori').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var actionUrl = $(this).attr('action');
            $.ajax({
                url: actionUrl,
                method: 'PUT',
                data: formData,
                success: function(response) {
                    showToast(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function() {
                    showToast('Terjadi kesalahan');
                }
            });
        });

        var kategoriId;
        $(document).on('click', '.deleteKategori', function() {
            kategoriId = $(this).data('id');
        });

        $('#confirmDelete').click(function() {
            $.ajax({
                url: '/kategori/' + kategoriId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    showToast(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                    $('#deleteModal').modal('hide');
                },
                error: function() {
                    showToast('Terjadi kesalahan');
                    $('#deleteModal').modal('hide');
                }
            });
        });
    });
</script>


@include('template.footer')
