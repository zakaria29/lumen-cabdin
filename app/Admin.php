<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = "admin";
    protected $primaryKey = "id_admin";
    protected $fillable = ["id_admin","nama_admin","email","kontak","username","password"];
    public $incrementing = true;
}
