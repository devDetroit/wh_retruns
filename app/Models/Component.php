<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Component extends Model
{
    use HasFactory;
    protected $table = 'components';
    protected $primaryKey = 'id_component';
    public $timestamps = false;
    protected $fillable = [
        'part_num',
        'type',
        'id_component_type'
    ];

    public function type(){
        return $this->hasOne(ComponentType::class, 'id', 'id_component_type');
    }

}
