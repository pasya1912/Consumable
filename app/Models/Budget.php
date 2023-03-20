<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;
    //table = budget
    protected $table = 'budget';
    //no timestamps
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'code_item',
        'category',
        'user',
        'quota',
    ];
}
