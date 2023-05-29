<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caliper extends Model
{
    use HasFactory;
    protected $table = 'calipers';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'family',
        'part_number'
    ];
}
