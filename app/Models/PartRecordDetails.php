<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartRecordDetails extends Model
{
    use HasFactory;
    protected $table = 'part_record_details';
    protected $primaryKey = 'id';
    protected $appends = array('MaxValue');
    public $timestamps = false;
    protected $fillable = [
        'part_record_id',
        'component',
        'quantity'
    ];

    public function getMaxValueAttribute()
    {
        return $this->quantity;
    }
    public function part()
    {
        return $this->hasOne(Part::class, 'id_part', 'part_record_id');
    }

    public function component()
    {
        return $this->hasOne(Component::class, 'part_num', 'component');
    }
}
