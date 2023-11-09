<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentType extends Model
{
    use HasFactory;
    protected $table = 'component_types';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'description',
        'url',
    ];

    public function components(){
        return $this->hasMany(Component::class, 'id_component_type', 'id');
    }
}
