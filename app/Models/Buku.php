<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = ['judul', 'deskripsi', 'gambar', 'kategori_id'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public static function uploadGambar($request)
    {
        if ($request->hasFile('gambar')) {
            return $request->file('gambar')->store('images', 'public');
        }
        return null;
    }

    public static function hapusGambar($gambar)
    {
        $gambarPath = public_path('storage/' . $gambar);
        if ($gambar && File::exists($gambarPath)) {
            return File::delete($gambarPath);
        }
        return false;
    }
}
