<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
require_once __DIR__.'/../../../vendor/autoload.php';
use Twilio\Rest\Client;
// phone : +12015006665
class WAController extends Controller
{
    public function test()
    {
      $sid = "AC682f7a548afa2c999b4764f2b19a1f4a";
      $token = "f88127d292ee20c7e8af1a2de1397794";
      $twilio = new Client($sid, $token);
      $i = "+6282231506608";
      $z = "+6281335810765";
      $j = "+6282235164166";
      return $twilio->messages->create("whatsapp:$i", // to
      [
        "from" => "whatsapp:+14155238886",
        "body" => "Bismillah! Please forward this message to Zakaria, he try to send this message within PHP code"
      ]);
    }

    public function sms()
    {
      $account_sid = 'AC682f7a548afa2c999b4764f2b19a1f4a';
      $auth_token = 'f88127d292ee20c7e8af1a2de1397794';
      // In production, these should be environment variables. E.g.:
      // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

      // A Twilio number you own with SMS capabilities
      $twilio_number = "+12015006665";
      $i = "+6282231506608";
      $z = "+6281335810765";
      $j = "+6285233333573";
      $client = new Client($account_sid, $auth_token);
      return $client->messages->create(
          // Where to send a text message (your cell phone?)
          $j,
          array(
              'from' => $twilio_number,
              'body' => 'Please forward this message to Zakaria, he try to make chatbot using PHP'
          )
      );
    }

}
