<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return response()->json(['message' => 'Kategori berhasil ditambahkan']);
    }
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->nama_kategori = $request->input('nama_kategori');
        $kategori->save();

        return response()->json([
            'message' => 'Kategori berhasil diupdate',
            'kategori' => $kategori
        ]);
    }


    public function destroy($id)
    {
        Kategori::find($id)->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}
