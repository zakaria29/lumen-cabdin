<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Sekolah;
use Auth;
/**
 *
 */
class SekolahController extends Controller
{
  public function __construct()
  {
    // $this->middleware("auth");
  }

  public function get($limit = null, $offset = null)
  {
    if ($limit == null  || $offset == null) {
      return response([
        "sekolah" => Sekolah::with("admin_sekolah")->get(), "count" => Sekolah::count()]);
    } else {
      return response([
        "sekolah" => Sekolah::with("admin_sekolah")->take($limit)->skip($offset)->get(),
        "count" => Sekolah::count()
      ]);
    }
  }

  public function save(Request $request)
  {
    try {
      $action = $request->action;
      if ($action == "insert") {
        $sekolah = new Sekolah();
        $sekolah->nama_sekolah = $request->nama_sekolah;
        $sekolah->alamat_sekolah = $request->alamat_sekolah;
        $sekolah->jenjang = $request->jenjang;
        $sekolah->save();
        return response(["message" => "Data berhasil ditambahkan"]);
      } else if($action == "update") {
        $sekolah = Sekolah::where("id_sekolah", $request->id_sekolah)->first();
        $sekolah->nama_sekolah = $request->nama_sekolah;
        $sekolah->alamat_sekolah = $request->alamat_sekolah;
        $sekolah->jenjang = $request->jenjang;
        $sekolah->save();
        return response(["message" => "Data berhasil diubah"]);
      }
    } catch (\Exception $e) {
      return response(["message" => $e->getMessage()]);
    }
  }

  public function drop($id)
  {
    try {
      Sekolah::where("id_sekolah", $id)->delete();
      return response(["message" => "Data berhasil dihapus"]);
    } catch (\Exception $e) {
      return response(["message" => $e->getMessage()]);
    }
  }

  public function find($limit = null, $offset = null, Request $request)
  {
    $find = $request->find;
    $result = Sekolah::whereRaw('MATCH (`nama_sekolah`, `alamat_sekolah`, `jenjang`) AGAINST (?)' , array($find))
    ->with("admin_sekolah");
    if ($limit == null || $offset == null) {
      return response([
        "count" => $result->count(),
        "sekolah" => $result->get()
      ]);
    } else {
      return response([
        "count" => $result->count(),
        "sekolah" => $result->take($limit)->skip($offset)->get(),
      ]);
    }
  }

}
