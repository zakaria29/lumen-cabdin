<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Surat;
use App\Sekolah;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailBuilder;
/**
 *
 */
class SuratController extends Controller
{
  public function __construct()
  {
    config(['app.timezone' => 'Asia/Jakarta']);
    // $this->middleware("auth");
  }

  public function get($limit = null, $offset = null)
  {
    if ($limit == null  || $offset == null) {
      return response([
        "surat" => Surat::with("sekolah")->orderBy("waktu","desc")->get(),
        "count" => Surat::count()
      ]);
    } else {
      return response([
        "surat" => Surat::with("sekolah")->orderBy("waktu","desc")->take($limit)->skip($offset)->get(),
        "count" => Surat::count()
      ]);
    }
  }

  public function getBySekolah($id_sekolah, $limit = null, $offset = null)
  {
    if ($limit == null  || $offset == null) {
      return response([
        "surat" => Surat::where("id_sekolah", $id_sekolah)
        ->with("sekolah")->orderBy("waktu","desc")->get(),
        "count" => Surat::where("id_sekolah", $id_sekolah)->count()
      ]);
    } else {
      return response([
        "surat" => Surat::where("id_sekolah", $id_sekolah)
        ->with("sekolah")->orderBy("waktu","desc")->take($limit)->skip($offset)->get(),
        "count" => Surat::where("id_sekolah", $id_sekolah)->count()
      ]);
    }
  }

  public function save(Request $request)
  {
    $folderId = "1GWyg6rxy7tQCSiTd_zsJKPVeje6x1LRD";
    try {
      $action = $request->action;
      if ($action == "insert") {
        $file = $request->file;
        $filename = $file->getClientOriginalName();

        config(['filesystems.disks.google.folderId' => $folderId]);
        Storage::disk('google')->put($filename, file_get_contents($file));
        $link = Storage::disk('google')->url($filename);
        $url = explode("/", $link);
        $idFile = end($url);

        $surat = new Surat();
        $surat->nomor_surat = $request->nomor_surat;
        $surat->prihal = $request->prihal;
        $surat->id_sekolah = $request->id_sekolah;
        $surat->file_surat = $filename;
        $surat->waktu = date("Y-m-d H:i:s");
        $surat->link = $link;
        $surat->save();
        // --------------------------------------
        $id_sekolah = $request->id_sekolah;
        $sekolah = Sekolah::where("id_sekolah", $id_sekolah)->with("admin_sekolah")->first();
        $number = $sekolah->admin_sekolah->kontak;
        $message = "Yth. Bapak/Ibu Admin $sekolah->nama_sekolah, mohon segera menindaklanjuti
        surat tugas dari Cabdin tentang $request->prihal pada link berikut ini.";
        $send = $this->send_message($number, $message);
        // --------------------------------------
        return response(["message" => "Data berhasil ditambahkan","wa" => $send]);
      } else if($action == "update") {
        $surat = Surat::where("id_surat", $request->id_surat)->first();
        $surat->nomor_surat = $request->nomor_surat;
        $surat->prihal = $request->prihal;
        $surat->id_sekolah = $request->id_sekolah;

        if($request->hasFile("file")){
          config(['filesystems.disks.google.folderId' => $folderId]);
          $url = parse_url($surat->link);
          parse_str($url["query"], $query);
          $idFile = $query["id"];
          Storage::disk("google")->delete($idFile);
          $file = $request->file;
          $filename = $file->getClientOriginalName();


          Storage::disk('google')->put($filename, file_get_contents($file));
          $link = Storage::disk('google')->url($filename);
          $surat->link = $link;
          $surat->file_surat = $filename;
        }
        $surat->save();
        // --------------------------------------
        $id_sekolah = $request->id_sekolah;
        $sekolah = Sekolah::where("id_sekolah", $id_sekolah)->with("admin_sekolah")->first();
        $number = $sekolah->admin_sekolah->kontak;
        $message = "Yth. Bapak/Ibu Admin $sekolah->nama_sekolah, mohon segera menindaklanjuti
        surat tugas dari Cabdin tentang $request->prihal pada link berikut ini.";
        $send = $this->send_message($number, $message);
        // --------------------------------------
        return response(["message" => "Data berhasil diubah","wa" => $send]);
      }
    } catch (\Exception $e) {
      return response(["message" => $e->getMessage()]);
    }
  }

  public function drop($id)
  {
    $folderId = "1GWyg6rxy7tQCSiTd_zsJKPVeje6x1LRD";
    try {
      $surat = Surat::where("id_surat", $id)->first();
      $url = parse_url($surat->link);
      parse_str($url["query"], $query);
      $idFile = $query["id"];
      config(['filesystems.disks.google.folderId' => $folderId]);
      Storage::disk("google")->delete($idFile);

      Surat::where("id_surat", $id)->delete();
      return response(["message" => "Data berhasil dihapus"]);
    } catch (\Exception $e) {
      return response(["message" => $e->getMessage()]);
    }
  }

  public function find($limit = null, $offset = null, Request $request)
  {
    $find = $request->find;
    $result = Surat::whereRaw('MATCH (`nomor_surat`, `prihal`, `file_surat`) AGAINST (?)' , array($find))
    ->with("sekolah")->orderBy("waktu","desc");
    if ($limit == null || $offset == null) {
      return response([
        "count" => $result->count(),
        "surat" => $result->get()
      ]);
    } else {
      return response([
        "count" => $result->count(),
        "surat" => $result->take($limit)->skip($offset)->get(),
      ]);
    }
  }

  public function findBySekolah($id_sekolah, $limit = null, $offset = null, Request $request)
  {
    $find = $request->find;
    $result = Surat::where("id_sekolah", $id_sekolah)
    ->whereRaw('MATCH (`nomor_surat`, `prihal`, `file_surat`) AGAINST (?)' , array($find))
    ->with("sekolah")->orderBy("waktu","desc");
    if ($limit == null || $offset == null) {
      return response([
        "count" => $result->count(),
        "surat" => $result->get()
      ]);
    } else {
      return response([
        "count" => $result->count(),
        "surat" => $result->take($limit)->skip($offset)->get(),
      ]);
    }
  }

  public function send_message($number, $message)
  {
    $curl = curl_init();
    $postfields = ["phone" => $number, "message" => $message];
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.wassenger.com/v1/messages",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($postfields),
      // CURLOPT_POSTFIELDS => "{\"phone\":\"+6281335810765\",\"message\":\"Hello world! This is a test message.\"}",
      CURLOPT_HTTPHEADER => array(
        "content-type: application/json",
        "token: 01d479debb8d9ad5dda294d7e5b39bb0b794fe3bb6c430761e486df689d4049285b6a32072ea36d2"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      // echo "cURL Error #:" . $err;
      return ["status" => false, "error" => $err];
    } else {
      return ["status" => true, "result" => $response];
    }
  }
}
