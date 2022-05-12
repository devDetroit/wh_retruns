<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartNumberPhotos extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function returns()
    {
        return $this->belongsTo(Returns::class);
    }
}
