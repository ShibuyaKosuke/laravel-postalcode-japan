<?php

use ShibuyaKosuke\LaravelPostalcodeJapan\App\Http\Controllers\PostalCodeController;

Route::middleware(['api', 'web'])->group(function () {
    Route::get('ajax/prefectures', PostalCodeController::class . '@prefectures')->name('ajax.prefectures');
    Route::get('ajax/cities/{prefecture}', PostalCodeController::class . '@cities')->name('ajax.cities');
});
