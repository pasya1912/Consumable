<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    //timestamp false
    //table jadwal
    public $timestamps = false;
    protected $table = 'jadwal';
}
