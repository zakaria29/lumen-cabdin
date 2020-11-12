<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Jurnal;
use Auth;
/**
 *
 */
class JurnalController extends Controller
{
  public function __construct()
  {
    // $this->middleware("auth");
  }

  public function get($limit = null, $offset = null)
  {
    if ($limit == null  || $offset == null) {
      return response(["jurnal" => Jurnal::with("sekolah")->get(), "count" => Jurnal::count()]);
    } else {
      return response([
        "jurnal" => Jurnal::with("sekolah")->take($limit)->skip($offset)->get(),
        "count" => Jurnal::count()
      ]);
    }
  }

  public function getBySekolah($id_sekolah, $limit = null, $offset = null)
  {
    if ($limit == null  || $offset == null) {
      return response([
        "jurnal" => Jurnal::where("id_sekolah", $id_sekolah)->with("sekolah")->get(),
        "count" => Jurnal::where("id_sekolah", $id_sekolah)->count()
      ]);
    } else {
      return response([
        "jurnal" => Jurnal::where("id_sekolah", $id_sekolah)
        ->with("sekolah")->take($limit)->skip($offset)->get(),
        "count" => Jurnal::where("id_sekolah", $id_sekolah)->count()
      ]);
    }
  }

  public function save(Request $request)
  {
    $folderId = "1XaGvqSY3_KJhdLwnQSCtWMaupHAjJyTK";
    try {
      $action = $request->action;
      if ($action == "insert") {


        $jurnal = new Jurnal();
        $jurnal->judul_jurnal = $request->judul_jurnal;
        $jurnal->penulis = $request->penulis;
        $jurnal->kategori = $request->kategori;
        $jurnal->id_sekolah = $request->id_sekolah;
        $jurnal->waktu = date("Y-m-d H:i:s");
        if ($request->has("link")) {
          $jurnal->link = $request->link;
          $jurnal->file_jurnal = null;
        }
        if ($request->has("file")) {
          $file = $request->file;
          $filename = $file->getClientOriginalName();

          config(['filesystems.disks.google.folderId' => $folderId]);
          Storage::disk('google')->put($filename, file_get_contents($file));
          $link = Storage::disk('google')->url($filename);
          $url = explode("/", $link);
          $idFile = end($url);

          $jurnal->file_jurnal = $filename;
          $jurnal->link = $link;
        }
        $jurnal->save();
        return response(["message" => "Data berhasil ditambahkan"]);
      } else if($action == "update") {
        $jurnal = Jurnal::where("id_jurnal", $request->id_jurnal)->first();
        $jurnal->judul_jurnal = $request->judul_jurnal;
        $jurnal->penulis = $request->penulis;
        $jurnal->kategori = $request->kategori;
        $jurnal->id_sekolah = $request->id_sekolah;
        if ($request->has("link")) {
          config(['filesystems.disks.google.folderId' => $folderId]);
          if ($jurnal->file_jurnal) {
            $url = parse_url($jurnal->link);
            parse_str($url["query"], $query);
            $idFile = $query["id"];
            Storage::disk("google")->delete($idFile);
          }
          $jurnal->link = $request->link;
          $jurnal->file_jurnal = null;
        }

        if($request->hasFile("file")){
          config(['filesystems.disks.google.folderId' => $folderId]);
          if ($jurnal->file_jurnal) {
            $url = parse_url($jurnal->link);
            parse_str($url["query"], $query);
            $idFile = $query["id"];
            Storage::disk("google")->delete($idFile);
          }
          $file = $request->file;
          $filename = $file->getClientOriginalName();

          Storage::disk('google')->put($filename, file_get_contents($file));
          $link = Storage::disk('google')->url($filename);
          $jurnal->link = $link;
          $jurnal->file_jurnal = $filename;
        }
        $jurnal->save();
        return response(["message" => "Data berhasil diubah"]);
      }
    } catch (\Exception $e) {
      return response(["message" => $e->getMessage()]);
    }
  }

  public function drop($id)
  {
    $folderId = "1XaGvqSY3_KJhdLwnQSCtWMaupHAjJyTK";
    try {
      $jurnal = Jurnal::where("id_jurnal", $id)->first();
      $url = parse_url($jurnal->link);
      parse_str($url["query"], $query);
      $idFile = $query["id"];
      config(['filesystems.disks.google.folderId' => $folderId]);
      Storage::disk("google")->delete($idFile);

      Jurnal::where("id_jurnal", $id)->delete();
      return response(["message" => "Data berhasil dihapus"]);
    } catch (\Exception $e) {
      return response(["message" => $e->getMessage()]);
    }
  }

  public function find($limit = null, $offset = null, Request $request)
  {
    $find = $request->find;
    $result = Jurnal::whereRaw('MATCH (`judul_jurnal`, `penulis`, `kategori`, `file_jurnal`) AGAINST (?)' , array($find))
    ->with("sekolah");
    if ($limit == null || $offset == null) {
      return response([
        "count" => $result->count(),
        "jurnal" => $result->get()
      ]);
    } else {
      return response([
        "count" => $result->count(),
        "jurnal" => $result->take($limit)->skip($offset)->get(),
      ]);
    }
  }

  public function findBySekolah($id_sekolah, $limit = null, $offset = null, Request $request)
  {
    $find = $request->find;
    $result = Jurnal::where("id_sekolah", $id_sekolah)
    ->whereRaw('MATCH (`judul_jurnal`, `penulis`, `kategori`, `file_jurnal`) AGAINST (?)' , array($find))
    ->with("sekolah");
    if ($limit == null || $offset == null) {
      return response([
        "count" => $result->count(),
        "jurnal" => $result->get()
      ]);
    } else {
      return response([
        "count" => $result->count(),
        "jurnal" => $result->take($limit)->skip($offset)->get(),
      ]);
    }
  }
}
