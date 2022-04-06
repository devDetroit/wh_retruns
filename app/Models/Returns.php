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

    protected $fillable = ['track_number', 'user_id', 'lastUpdateBy'];

    protected $with = ['user'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function partnumbers()
    {
        return $this->hasMany(PartNumber::class);
    }
}
