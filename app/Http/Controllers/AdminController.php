<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Admin;
use Auth;
/**
 *
 */
class AdminController extends Controller
{
  public function __construct()
  {
    // $this->middleware("auth");
  }

  public function get($limit = null, $offset = null)
  {
    if ($limit == null  || $offset == null) {
      return response(Admin::all());
    } else {
      return response(Admin::take($limit)->skip($offset)->get());
    }
  }

  public function save(Request $request)
  {
    try {
      $action = $request->action;
      if ($action == "insert") {
        $admin = new Admin();
        $admin->nama_admin = $request->nama_admin;
        $admin->email = $request->email;
        $admin->kontak = $request->kontak;
        $admin->username = $request->username;
        $admin->password = sha1($request->password);
        $admin->save();
        return response(["message" => "Data berhasil ditambahkan"]);
      } else if($action == "update") {
        $admin = Admin::where("id_admin", $request->id_admin)->first();
        $admin->nama_admin = $request->nama_admin;
        $admin->email = $request->email;
        $admin->kontak = $request->kontak;
        $admin->username = $request->username;
        if($request->has("password")){
          $admin->password = sha1($request->password);
        }

        $admin->save();
        return response(["message" => "Data berhasil diubah"]);
      }
    } catch (\Exception $e) {
      return response(["message" => $e->getMessage()]);
    }
  }

  public function drop($id)
  {
    try {
      Admin::where("id_admin", $id)->delete();
      return response(["message" => "Data berhasil dihapus"]);
    } catch (\Exception $e) {
      return response(["message" => $e->getMessage()]);
    }
  }

}
