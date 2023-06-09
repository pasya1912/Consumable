<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class itemRequest extends Model
{
    use HasFactory;
    //table = request_item
    protected $table = 'request_item';
    //no timestamps
    public $timestamps = false;
    //belongs to request
    public function request()
    {
        return $this->belongsTo(Requests::class, 'id_request', 'id');
    }
}
