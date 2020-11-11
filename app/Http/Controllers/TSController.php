<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\TS;
class TSController extends Controller
{

  function __construct()
  {

  }

  public function getAll()
  {
    $ts = TS::all();
    $data = array();
    foreach ($ts as $t) {
      $item = [
        "id_ts" => $t->id_ts,
        "nama_ts" => $t->nama_ts,
        "tahun" => $t->tahun,
        "folder_id" => $t->folder_id,
        "triwulan" => $t->triwulan
      ];
      array_push($data, $item);
    }
    return response($data);
  }

  public function save(Request $request)
  {
    $action = $request->action;
    config(['filesystems.disks.google.folderId' => '174AtOp3_MK7DRq1my7BdUUQvg7p0V_r3']);
    if ($action == "insert") {
      $folder = "";
      try {
        $ts = new TS();
        $ts->nama_ts = $request->nama_ts;
        $ts->tahun = $request->tahun;
        $folder = $this->makeDirectory("174AtOp3_MK7DRq1my7BdUUQvg7p0V_r3",$request->nama_ts);
        $ts->folder_id = $folder;
        $ts->save();
        return response([
          "message" => "Data berhasil disimpan",
          "type" => "success"
          ]);
      } catch (\Exception $e) {
        $stat = Storage::disk("google")->deleteDirectory($folder);
        return response([
          "message" => $e->getMessage(),
          "type" => "danger"
          ]);
      }
    } elseif ($action == "update") {
      try {
        $ts = TS::where("id_ts", $request->id_ts)->first();
        $ts->tahun = $request->tahun;
        $ts->nama_ts = $request->nama_ts;
        $ts->save();
        Storage::disk("google")->move($ts->folder_id,$request->nama_ts);
        return response([
          "message" => "Data berhasil disimpan",
          "type" => "success"
          ]);
      } catch (\Exception $e) {
        return response([
          "message" => $e->getMessage(),
          "type" => "danger"
          ]);
      }
    }
  }

  public function drop($id_ts){
    try {
      config(['filesystems.disks.google.folderId' => '174AtOp3_MK7DRq1my7BdUUQvg7p0V_r3']);
      $ts = TS::where("id_ts",$id_ts)->first();
      $stat = Storage::disk("google")->deleteDirectory($ts->folder_id);
      TS::where("id_ts", $id_ts)->delete();
      return response([
        "message" => "Data berhasil dihapus",
        "type" => "success"
        ]);
    } catch (\Exception $e) {
      return response([
        "message" => $e->getMessage(),
        "type" => "danger"
        ]);
    }

  }

  public function makeDirectory($folderId,$name){
    config(['filesystems.disks.google.folderId' => $folderId]);
    $folder = Storage::disk('google')->makeDirectory($name,0711, true, true);
    $url = Storage::disk('google')->url($name);
    $url = explode("/", $url);
    $folderId = end($url);
    return $folderId;
  }
}
 ?>
