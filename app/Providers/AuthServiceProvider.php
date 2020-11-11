<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Crypt;
use App\Admin;
use App\AdminSekolah;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            try {
              if ($request->header("Api-Token")) {
                $token = $request->header("Api-Token");
                $obj = json_decode(Crypt::decrypt($token));
                if (property_exists($obj,"id_admin")) {
                  $admin = Admin::where("id_admin",$obj->id_admin);
                  if ($admin->count() > 0) return $admin->first();
                  else return null;
                } elseif (property_exists($obj,"id_admin_sekolah")) {
                  $admin = AdminSekolah::where("id_admin_sekolah",$obj->id_admin_sekolah);
                  if ($admin->count() > 0) return $admin->first();
                  else return null;
                } else{
                  return null;
                }
              }
            } catch (\Exception $e) {
              return null;
            }

        });
    }
}
