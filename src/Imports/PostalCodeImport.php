<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\Imports;

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
     * @var string[]
     */
    protected $fields = [
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

    /**
     * @inheritDoc
     */
    public function model(array $row)
    {
        $data = array_combine($this->fields, $row);
        return PostalCode::create($data);
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
            },
            AfterImport::class => function (AfterImport $event) {
                $cities = PostalCode::query()
                    ->select(['official_code', 'city'])
                    ->groupBy('official_code')
                    ->get();
                $prefectures = PostalCode::query()
                    ->select(['official_code', 'prefecture_id', 'pref'])
                    ->groupBy('prefecture_id')
                    ->get();

                $cities->each(function (PostalCode $postalCode) {
                    City::updateOrCreate([
                        'id' => $postalCode->official_code,
                    ], [
                        'id' => $postalCode->official_code,
                        'prefecture_id' => floor($postalCode->official_code / 1000),
                        'name' => $postalCode->city,
                    ]);
                });
                $prefectures->each(function (PostalCode $postalCode) {
                    Prefecture::updateOrcreate([
                        'id' => floor($postalCode->official_code / 1000),
                    ], [
                        'id' => floor($postalCode->official_code / 1000),
                        'name' => $postalCode->pref,
                    ]);
                });
            }
        ];
    }
}
