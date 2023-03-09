<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtAssy extends Model
{
    use HasFactory;

    protected $table = 'tt_assy';

    protected $guarded = ['id'];
}
