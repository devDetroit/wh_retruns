<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PartComponent extends Model
{
    use HasFactory;
    protected $table = 'parts_components';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $appends = array('MaxValue');
    protected $fillable = [
        'id_part',
        'id_component',
        'quantity'
    ];

    public function getMaxValueAttribute()
    {
        return $this->quantity;
    }

    public function part()
    {
        return $this->hasOne(Part::class, 'id_part', 'id_part');
    }

    public function component()
    {
        return $this->hasOne(Component::class, 'id_component', 'id_component');
    }
}
