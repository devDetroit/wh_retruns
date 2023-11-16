<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartRecord extends Model
{
    use HasFactory;
    protected $table = 'part_records';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $appends = array('status');
    protected $fillable = [
        'part',
        'received_date',
        'serial_number',
        'created_at',
        'status'
    ];
    protected $casts = [
        'updated_by' => 'integer',
    ];
    protected $dates = [
        'created_at',
    ];

    public function getstatusAttribute()
    {
        if ($this->received_date != null) {
            return 'Recibido';
        } else {
            return "Pendiente";
        }
    }



    public function details()
    {
        return $this->hasMany(PartRecordDetails::class, 'part_record_id', 'id');
    }

    public function part()
    {
        return $this->hasOne(Part::class, 'id_part', 'part');
    }
}
