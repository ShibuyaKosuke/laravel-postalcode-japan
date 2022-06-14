<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\Console;

use Illuminate\Console\Command;
use ShibuyaKosuke\LaravelPostalcodeJapan\Imports\PostalCodeImport;
use ShibuyaKosuke\LaravelPostalcodeJapan\Services\ArchiveService;
use ShibuyaKosuke\LaravelPostalcodeJapan\Services\PostalCodeService;

class PostalCodeUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postalcode:update {--test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '郵便番号データを取り込んで住所マスタを作成します。';

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(): void
    {
        $test = $this->option('test');
        $destination = 'temp/ken_all.zip';
        $postalCodeService = new PostalCodeService($test, $destination);
        if (!$postalCodeService->download()) {
            return;
        }
        $archiveService = new ArchiveService($test, $destination);
        if (!$archiveService->unzip()) {
            return;
        }
        if (!$archiveService->mb_convert()) {
            return;
        }

        $csv_file = $archiveService->getOutFile();
        (new PostalCodeImport())->withOutput($this->getOutput())
            ->import($csv_file);
    }
}