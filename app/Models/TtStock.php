<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtStock extends Model
{
    use HasFactory;
    protected $table = 'tt_stocks';

    protected $guarded = ['updated_at']; 
}
