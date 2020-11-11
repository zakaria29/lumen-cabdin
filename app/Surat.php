<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = "surat";
    protected $primaryKey = "id_surat";
    protected $fillable = [
      "id_surat","nomor_surat","prihal","id_sekolah","file_surat","waktu","link"
    ];
    public $incrementing = true;

    public function sekolah()
    {
      return $this->belongsTo("App\Sekolah","id_sekolah")->with("admin_sekolah");
    }
}
