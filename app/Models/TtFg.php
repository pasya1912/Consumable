<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtFg extends Model
{
    use HasFactory;

    protected $table = 'tt_fgs';

    protected $guarded = ['id'];
}
