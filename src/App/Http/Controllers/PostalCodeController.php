<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\App\Http\Controllers;

use App\Http\Controllers\Controller;
use ShibuyaKosuke\LaravelPostalcodeJapan\Models\Prefecture;

class PostalCodeController extends Controller
{
    public function prefectures()
    {
        return Prefecture::all();
    }

    public function cities(Prefecture $prefecture)
    {
        return $prefecture->cities;
    }
}
