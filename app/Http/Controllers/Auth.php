<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Admin;
use App\AdminSekolah;

class Auth extends Controller
{
    public function authenticate(Request $request)
    {
      $username = $request->username;
      $password = sha1($request->password);
      $admin = Admin::where("username", $username)->where("password", $password);
      if ($admin->count() > 0) {
        $adm = $admin->first();
        $token = Crypt::encrypt(json_encode($adm));
        return response([
          "logged" => true,
          "token" => $token,
        ]);
      } else {
        return response([
          "logged" => false
        ]);
      }
    }

    public function check(Request $request)
    {
      try {
        $token = $request->token;
        $admin = json_decode(Crypt::decrypt($token));
        $check = Admin::where("id_admin", $admin->id_admin)->count();
        if ($check > 0) {
          return response([
            "auth" => true,
            "admin" => Admin::where("id_admin", $admin->id_admin)->first()
          ]);
        }else{
          return response(["auth" => false]);
        }
      } catch (\Exception $e) {
        return response(["auth" => false, "message" => $e->getMessage()]);
      }
    }

    public function authentic(Request $request)
    {
      $username = $request->username;
      $password = sha1($request->password);
      $admin = AdminSekolah::where("username", $username)->where("password", $password);
      if ($admin->count() > 0) {
        $adm = $admin->first();
        $token = Crypt::encrypt(json_encode($adm));
        return response([
          "logged" => true,
          "token" => $token,
        ]);
      } else {
        return response([
          "logged" => false
        ]);
      }
    }

    public function verify(Request $request)
    {
      try {
        $token = $request->token;
        $admin = json_decode(Crypt::decrypt($token));
        $check = AdminSekolah::where("id_admin_sekolah", $admin->id_admin_sekolah)->count();
        if ($check > 0) {
          return response([
            "auth" => true,
            "admin_sekolah" => AdminSekolah::where("id_admin_sekolah", $admin->id_admin_sekolah)
            ->first()
          ]);
        }else{
          return response(["auth" => false]);
        }
      } catch (\Exception $e) {
        return response(["auth" => false, "message" => $e->getMessage()]);
      }
    }
}
