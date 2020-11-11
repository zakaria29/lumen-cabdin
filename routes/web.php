<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->post("admin/auth", "Auth@authenticate");
$router->post("admin/check","Auth@check");

$router->post("admin-sekolah/auth", "Auth@authentic");
$router->post("admin-sekolah/check","Auth@verify");

$router->get("wa","WAController@test");
$router->get("sms","WAController@sms");

$router->get("sekolah[/{limit}/{offset}]", "SekolahController@get");
$router->post("sekolah", "SekolahController@save");
$router->delete("sekolah/{id_sekolah}", "SekolahController@drop");
$router->post("sekolah/find[/{limit}/{offset}]", "SekolahController@find");

$router->get("admin[/{limit}/{offset}]", "AdminController@get");
$router->post("admin", "AdminController@save");
$router->delete("admin/{id_admin}", "AdminController@drop");

$router->get("admin-sekolah[/{limit}/{offset}]", "AdminSekolahController@get");
$router->post("admin-sekolah", "AdminSekolahController@save");
$router->delete("admin-sekolah/{id_admin_sekolah}", "AdminSekolahController@drop");

$router->get("surat[/{limit}/{offset}]", "SuratController@get");
$router->post("surat", "SuratController@save");
$router->delete("surat/{id_surat}", "SuratController@drop");
$router->post("surat/find[/{limit}/{offset}]", "SuratController@find");
$router->get("test-email", "SuratController@mail");

$router->get("jurnal[/{limit}/{offset}]", "JurnalController@get");
$router->post("jurnal", "JurnalController@save");
$router->delete("jurnal/{id_jurnal}", "JurnalController@drop");
$router->post("jurnal/find[/{limit}/{offset}]", "JurnalController@find");
