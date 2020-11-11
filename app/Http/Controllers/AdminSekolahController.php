<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\AdminSekolah;
use Auth;
/**
 *
 */
class AdminSekolahController extends Controller
{
  public function __construct()
  {
    // $this->middleware("auth");
  }

  public function get($limit = null, $offset = null)
  {
    if ($limit == null  || $offset == null) {
      return response(AdminSekolah::all());
    } else {
      return response(AdminSekolah::take($limit)->skip($offset)->get());
    }
  }

  public function save(Request $request)
  {
    try {
      $action = $request->action;
      if ($action == "insert") {
        $adminSekolah = new AdminSekolah();
        $adminSekolah->nama_admin_sekolah = $request->nama_admin_sekolah;
        $adminSekolah->email = $request->email;
        $adminSekolah->kontak = $request->kontak;
        $adminSekolah->id_sekolah = $request->id_sekolah;
        $adminSekolah->username = $request->username;
        $adminSekolah->password = sha1($request->password);
        $adminSekolah->save();
        return response(["message" => "Data berhasil ditambahkan"]);
      } else if($action == "update") {
        $adminSekolah = AdminSekolah::where("id_admin_sekolah",
        $request->id_admin_sekolah)->first();
        $adminSekolah->nama_admin_sekolah = $request->nama_admin_sekolah;
        $adminSekolah->email = $request->email;
        $adminSekolah->kontak = $request->kontak;
        $adminSekolah->id_sekolah = $request->id_sekolah;
        $adminSekolah->username = $request->username;
        if($request->has('password')){
          $adminSekolah->password = sha1($request->password);
        }

        $adminSekolah->save();
        return response(["message" => "Data berhasil diubah"]);
      }
    } catch (\Exception $e) {
      return response(["message" => $e->getMessage()]);
    }
  }

  public function drop($id)
  {
    try {
      AdminSekolah::where("id_admin_sekolah", $id)->delete();
      return response(["message" => "Data berhasil dihapus"]);
    } catch (\Exception $e) {
      return response(["message" => $e->getMessage()]);
    }
  }

}
