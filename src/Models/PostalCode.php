<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    use Timestamp;

    protected $fillable = [
        'official_code',
        'postal_code5',
        'postal_code7',
        'kana_pref',
        'kana_city',
        'kana_town',
        'pref',
        'city',
        'town',
        'flag_doubleCode',
        'flag_banchi',
        'flag_chome',
        'flag_double_area',
        'flag_update',
        'flag_update_reason',
    ];
}
