<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtDc extends Model
{
    use HasFactory;

    protected $table = 'tt_dcs';

    protected $guarded = ['id'];
}
