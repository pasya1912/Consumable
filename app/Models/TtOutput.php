<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtOutput extends Model
{
    use HasFactory;

    protected $table = 'tt_outputs';

    protected $guarded = ['id'];
}
