<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;
    //table = request_item
    protected $table = 'request';
    //no timestamps
    public $timestamps = false;
    //casts code_item = array
    //hide id
    protected $hidden = ['id'];
}
