<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminSekolah extends Model
{
    protected $table = "admin_sekolah";
    protected $primaryKey = "id_admin_sekolah";
    protected $fillable = [
      "id_admin_sekolah","nama_admin_sekolah","kontak","email",
      "username","password","id_sekolah"
    ];
    public $incrementing = true;

    public function sekolah()
    {
      return $this->belongsTo("App\Sekolah","id_sekolah");
    }
}
