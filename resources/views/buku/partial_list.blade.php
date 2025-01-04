@foreach ($bukus as $index => $buku)
    <tr id="buku_{{ $buku->id }}">
        <td class="text-center">{{ $index + 1 }}</td>
        <td>{{ $buku->judul }}</td>
        <td>{{ $buku->deskripsi }}</td>
        <td>{{ $buku->kategori->nama_kategori }}</td>
        <td class="text-center">
            @if ($buku->gambar)
                <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}" width="50" height="50">
            @else
                <span class="text-muted">Tidak ada gambar</span>
            @endif
        </td>
        <td class="text-center">
            <button class="btn btn-primary btn-sm editBuku" data-id="{{ $buku->id }}"
                data-judul="{{ $buku->judul }}" data-deskripsi="{{ $buku->deskripsi }}"
                data-kategori="{{ $buku->kategori_id }}" data-toggle="modal" data-target="#edit_buku"><i
                    class="fa fa-edit"></i> Edit</button>
            <button class="btn btn-danger btn-sm deleteBuku" data-id="{{ $buku->id }}" data-toggle="modal"
                data-target="#deleteModal"><i class="fa fa-trash"></i> Hapus</button>
        </td>
    </tr>
@endforeach
