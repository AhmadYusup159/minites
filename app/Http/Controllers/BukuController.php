<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $bukus = Buku::when($request->kategori_id, function ($query) use ($request) {
            return $query->where('kategori_id', $request->kategori_id);
        })->get();

        $kategoris = Kategori::all();
        return view('buku.index', compact('bukus', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $gambar = Buku::uploadGambar($request);

        Buku::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar,
            'kategori_id' => $request->kategori_id,
        ]);

        return response()->json(['message' => 'Buku berhasil ditambahkan']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $buku = Buku::find($id);
        $gambar = Buku::uploadGambar($request);
        if ($gambar) {
            $buku->gambar = $gambar;
        }

        $buku->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori_id' => $request->kategori_id,
        ]);

        return response()->json(['message' => 'Buku berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Buku::find($id)->delete();
        return response()->json(['message' => 'Buku berhasil dihapus']);
    }
}
