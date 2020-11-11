<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $table = "jurnal";
    protected $primaryKey = "id_jurnal";
    protected $fillable = [
      "id_jurnal","judul_jurnal","penulis","kategori","file_jurnal",
      "id_sekolah","waktu","link"
    ];
    public $incrementing = true;

    public function sekolah()
    {
      return $this->belongsTo("App\Sekolah","id_sekolah");
    }
}
