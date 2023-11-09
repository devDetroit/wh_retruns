<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;
    protected $table = 'part';
    protected $primaryKey = 'id_part';
    public $timestamps = false;
    protected $fillable = [
        'id_family',
        'part_num',
        'id_part_type'
    ];


    public function components()
    {
        return $this->hasMany(PartComponent::class, 'id_part', 'id_part');
    }

    public function family()
    {
        return $this->hasOne(Family::class, 'id', 'id_family');
    }

    public function parttype()
    {
        return $this->hasOne(PartType::class, 'id', 'id_part_type');
    }
}
