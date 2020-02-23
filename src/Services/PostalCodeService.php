<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class PostalCodeService
{
    /**
     * @var string
     */
    private $url = 'https://www.post.japanpost.jp/zipcode/dl/kogaki/zip/ken_all.zip';

    /**
     * @var string|null
     */
    private $destination = null;

    /**
     * PostalCodeService constructor.
     * @param string $destination
     */
    public function __construct(string $destination = 'temp/ken_all.zip')
    {
        $this->destination = $destination;
    }

    /**
     * https://www.post.japanpost.jp/ から郵便番号データをダウンロードする
     */
    public function download()
    {
        $client = new Client();
        $response = $client->get($this->url);
        $buffer = $response->getBody();
        return Storage::put($this->destination, $buffer);
    }
}