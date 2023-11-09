<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;
    protected $table = 'configurations';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'starting_number',
        'extra_field_1',
        'extra_field_2',
        'extra_field_3',
    ];

    private function getBarCode(){

    }
}
