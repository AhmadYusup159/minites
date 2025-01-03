<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
