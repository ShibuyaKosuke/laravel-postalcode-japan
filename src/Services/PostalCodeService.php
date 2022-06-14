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
     * @var string
     */
    private $test_url = 'https://www.post.japanpost.jp/zipcode/dl/kogaki/zip/13tokyo.zip';

    /**
     * @var string|null
     */
    private $destination = null;

    /**
     * @var bool
     */
    private bool $test;

    /**
     * PostalCodeService constructor.
     * @param boolean $test
     * @param string $destination
     */
    public function __construct(bool $test = false, string $destination = 'temp/ken_all.zip')
    {
        $this->destination = $destination;
        $this->test = $test;
    }

    /**
     * https://www.post.japanpost.jp/ から郵便番号データをダウンロードする
     * @return boolean
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function download()
    {
        $url = ($this->test) ? $this->test_url : $this->url;
        $client = new Client();
        $response = $client->get($url);
        $buffer = $response->getBody();
        return Storage::put($this->destination, $buffer);
    }
}