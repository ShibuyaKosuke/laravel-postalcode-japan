<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Events\BeforeImport;
use ShibuyaKosuke\LaravelPostalcodeJapan\Models\City;
use ShibuyaKosuke\LaravelPostalcodeJapan\Models\PostalCode;
use ShibuyaKosuke\LaravelPostalcodeJapan\Models\Prefecture;

class PostalCodeImport implements ToModel, WithChunkReading, WithCustomChunkSize, WithEvents, WithProgressBar
{
    use Importable;

    /**
     * @inheritDoc
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

        Prefecture::updateOrcreate([
            'id' => floor($row[0] / 1000),
        ], [
            'id' => floor($row[0] / 1000),
            'name' => $row[6]
        ]);

        City::updateOrCreate([
            'id' => $row[0],
        ], [
            'id' => $row[0],
            'prefecture_id' => floor($row[0] / 1000),
            'name' => $row[7]
        ]);

        return $postal_code;
    }

    /**
     * @inheritDoc
     */
    public function chunkSize(): int
    {
        return 200;
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                PostalCode::truncate();
            }
        ];
    }
}
