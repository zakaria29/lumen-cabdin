<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = "unit";
    protected $primaryKey = "id_unit";
    protected $fillable = ["id_unit","nama_unit","folder_id"];
    public $incrementing = false;
}
