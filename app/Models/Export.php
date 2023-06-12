<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    use HasFactory;
    //table = request_item
    protected $table = 'export';
    //timestamps
    public $timestamps = false;
    //belongs to request
    public function request()
    {
        return $this->belongsTo(Requests::class, 'id_request', 'id');
    }
}
