<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\itemRequest;
use App\Models\Export;

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
    protected $fillable = ['status'];

    //has many request_item
    public function request_item()
    {
        return $this->hasMany(itemRequest::class, 'id_request', 'id');
    }
    //has One export
    public function export()
    {
        return $this->hasOne(Export::class, 'id_request', 'id');
    }
    public function jam()
    {
        return $this->hasOne(Jadwal::class, 'id', 'id_jam');
    }

}
