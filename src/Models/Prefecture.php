<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;

class Prefecture extends Model
{
    use Timestamp;

    protected $fillable = [
        'id',
        'name'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
