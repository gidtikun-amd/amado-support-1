<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crmitemrequest_api extends Model
{
    protected $table = 'crmitemrequest_api';
    protected $fillable = ['api_id', 'api_ref', 'itmreq_id', 'api_json_send', 
    'api_json_receive', 'api_status', 'api_user','api_msg'];
}
