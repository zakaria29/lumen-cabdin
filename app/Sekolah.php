<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $table = "sekolah";
    protected $primaryKey = "id_sekolah";
    protected $fillable = ["id_sekolah","nama_sekolah","jenjang","alamat_sekolah"];
    public $incrementing = true;

    public function admin_sekolah()
    {
      return $this->hasOne("App\AdminSekolah","id_sekolah","id_sekolah");
    }
}
