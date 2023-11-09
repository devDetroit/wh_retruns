<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartType extends Model
{
    use HasFactory;
    protected $table = 'part_types';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'part_description'
    ];

}
