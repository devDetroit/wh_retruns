<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnStatus extends Model
{
    use HasFactory;

    protected $fillable = ['description'];

    public function returns()
    {
        return $this->hasMany(Returns::class);
    }
}
