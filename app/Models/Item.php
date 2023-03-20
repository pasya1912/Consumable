<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    //table = item_master
    protected $table = 'item_master';
    //no timestamps
    public $timestamps = false;
    public $primaryKey = 'no';

    //fillable
    protected $fillable = [
        'code_item',
        'name_item',
        'area',
        'lemari',
        'no2',
        'satuan',
        'satuan_oca',
        'convert',
        'note',
        'kate'
    ];


}
