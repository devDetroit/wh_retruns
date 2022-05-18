<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartNumber extends Model
{
    use HasFactory;

    protected $fillable = ['status_id', 'note', 'returns_id', 'partnumber', 'image'];

    protected $with = ['status', 'photos'];

    public function whreturn()
    {
        return $this->belongsTo(Returns::class, 'returns_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function photos()
    {
        return $this->hasMany(PartNumberPhotos::class, 'partnumber_id');
    }
}
