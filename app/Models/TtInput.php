<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtInput extends Model
{
    use HasFactory;
    protected $guarded = ['updated_at']; 
}
