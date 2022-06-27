<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class it_assethandle_file extends Model
{
    protected $table = 'it_assethandle_file';
    protected $fillable = [
            'assthd_id',
            'it_asst_hw_seq',
            'assthd_file_group',
            'assthd_file',
            'assthd_path'
        ];
}
