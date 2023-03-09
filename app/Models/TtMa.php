<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtMa extends Model
{
    use HasFactory;

    protected $table = 'tt_mas';

    protected $guarded = ['id'];
}
