<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmPartNumber extends Model
{
    use HasFactory;

    protected $table = 'tm_part_numbers';

    protected $guarded = ['id'];
}
