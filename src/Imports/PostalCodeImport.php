<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\Imports;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use ShibuyaKosuke\LaravelPostalcodeJapan\Models\City;
use ShibuyaKosuke\LaravelPostalcodeJapan\Models\PostalCode;
use ShibuyaKosuke\LaravelPostalcodeJapan\Models\Prefecture;

class PostalCodeImport implements ToModel, WithChunkReading, WithCustomChunkSize, WithEvents, WithProgressBar
{
    use Importable;

    /**
     * @param array $row
     * @return Model|Model[]|null
     */
    public function model(array $row)
    {
        $postal_code = PostalCode::create([
            'official_code' => $row[0],
            'postal_code5' => $row[1],
            'postal_code7' => $row[2],
            'kana_pref' => $row[3],
            'kana_city' => $row[4],
            'kana_town' => $row[5],
            'pref' => $row[6],
            'city' => $row[7],
            'town' => $row[8],
            'flag_doubleCode' => $row[9],
            'flag_banchi' => $row[10],
            'flag_chome' => $row[11],
            'flag_double_area' => $row[12],
            'flag_update' => $row[13],
            'flag_update_reason' => $row[14],
        ]);
        return $postal_code;
    }

    /**
     * @return integer
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * @return Closure[]
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                PostalCode::truncate();
            },
            AfterImport::class => function (AfterImport $event) {
                PostalCode::query()
                    ->selectRaw('floor(official_code / 1000) as id, pref as name')
                    ->groupByRaw('id, name')
                    ->get(['id', 'name'])
                    ->each(function ($prefecture) {
                        Prefecture::updateOrcreate([
                            'id' => $prefecture->id,
                        ], [
                            'id' => $prefecture->id,
                            'name' => $prefecture->name
                        ]);
                    });
                PostalCode::query()
                    ->selectRaw('official_code as id, floor(official_code / 1000) as prefecture_id, city as name')
                    ->groupByRaw('prefecture_id, id, name')
                    ->get(['id', 'prefecture_id', 'name'])
                    ->each(function ($city) {
                        City::updateOrCreate([
                            'id' => $city->id,
                        ], [
                            'id' => $city->id,
                            'prefecture_id' => $city->prefecture_id,
                            'name' => $city->name
                        ]);
                    });
            }
        ];
    }
}
