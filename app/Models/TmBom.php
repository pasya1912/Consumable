<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmBom extends Model
{
    use HasFactory;

    protected $table = 'tm_boms';

    protected $guarded = ['id'];
}
