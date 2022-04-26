<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    protected $fillable = ['track_number', 'created_by', 'last_updated_by', 'returnstatus_id'];

    protected $with = ['createdBy', 'returnstatus', 'updatedBy'];


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }
    public function returnstatus()
    {
        return $this->belongsTo(ReturnStatus::class);
    }

    public function partnumbers()
    {
        return $this->hasMany(PartNumber::class);
    }
}
