<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class BukuController extends Controller
{

    public function index(Request $request)
    {
        $query = Buku::query();
        $kategoris = Kategori::all();

        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $bukus = $query->with('kategori')->get();

        if ($request->ajax()) {
            return view('buku.partial_list', compact('bukus', 'kategoris'));
        }

        return view('buku.index', compact('bukus', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        if ($request->hasFile('gambar')) {
            if ($buku->gambar) {
                Buku::hapusGambar($buku->gambar);
            }

            $buku->gambar = Buku::uploadGambar($request);
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
        $buku = Buku::find($id);
        if ($buku) {
            if ($buku->gambar) {
                Storage::disk('public')->delete($buku->gambar);
            }

            $buku->delete();

            return response()->json(['message' => 'Buku berhasil dihapus']);
        }

        return response()->json(['message' => 'Buku tidak ditemukan'], 404);
    }
}
