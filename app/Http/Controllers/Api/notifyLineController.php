<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Curl;

class notifyLineController extends Controller
{
    private $link = 'https://notify-api.line.me/api/notify';
    private $token_m1to1 = '80pzwtS3TNGUac5bu7IWzJuU4KlXdmHAVhtUa7CSYYx';
    private $token_gTicketITSupport = '23k9qGbgUu0LGID5qK4RNEIADT1DQ0stMUvsOhwgSHC';
    private $token_gTicketITSupportSt = '7HfNATmA1lgzXUsYc7Mugpt2EnCDzkhZ4HpP16gzk84';
    private $token_gItItemRequest = 's8oi3Phnoe5Fq5qryNe5mUTKZpRMfudMSiEkHs5HxZw';
    private $token_gItMemo = 'SLevaoPpQo82DhFZwRJw2Rqla05c8irwFEgTSNUplcg';
    private $token_gItJobReq = 'fuCTJbogID4O6GVFqJV4REIHyFjmsAhTP4m1dEY00Ve';
    private $token_gAdEqReq = 'bqJkn5bQxJN3ftqA0W4LOcSfLMz2cR3zznnJ1KmibGX';
    private $token_gAdJobReq = 'hPj0rPwD5oncfc1O2jdl4aClGsxtDwFSKhvxlv12HqZ';

    public function call_notify_group_ticket_it_support($to,$message){

        switch($to){
            case 'manager': $token = $this->token_gTicketITSupport; break;
            case 'staff': $token = $this->token_gTicketITSupportSt; break;
            case 'jobreq': $token = $this->token_gItJobReq; break;
            case 'memo': $token = $this->token_gItMemo; break;
        }

        $call = Curl::to($this->link)
                    ->withContentType('application/x-www-form-urlencoded')
                    ->withBearer($token)
                    ->withData($message)
                    ->post();

    }

    public function call_notify_group_item_request($message){
        $call = Curl::to($this->link)
                    ->withContentType('application/x-www-form-urlencoded')
                    ->withBearer($this->token_gItItemRequest)
                    ->withData($message)
                    ->post();
    }

    public function call_notity_group_administrative($to,$message){

        switch($to){
            case 'eqreq'    : $token = $this->token_gAdEqReq;   break;
            case 'jobreq'   : $token = $this->token_gAdJobReq;  break;
        }

        $call = Curl::to($this->link)
            ->withContentType('application/x-www-form-urlencoded')
            ->withBearer($token)
            ->withData($message)
            ->post();

    }
}
