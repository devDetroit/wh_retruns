<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaliperLog extends Model
{
    use HasFactory;
    protected $table = 'caliper_log';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'family',
        'part_number'
    ];
    protected $casts = [
        'updated_by' => 'integer',
    ];
    protected $dates = [
        'created_at',
    ];
}
