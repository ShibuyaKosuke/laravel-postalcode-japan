<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use Timestamp;

    protected $fillable = [
        'id',
        'prefecture_id',
        'name'
    ];

    public function prefecture()
    {
        return $this->belongsTo(Prefecture::class);
    }
}
