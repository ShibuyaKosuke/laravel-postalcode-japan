<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\Services;

class ArchiveService
{
    /**
     * @var \ZipArchive
     */
    private \ZipArchive $zip;

    /**
     * @var string
     */
    private string $zip_file;

    /**
     * @var string
     */
    private string $outfile = 'app/temp/ken_all_uft8.csv';

    /**
     * @var bool
     */
    private bool $test;

    /**
     * @var string
     */
    private string $csv;

    /**
     * @param boolean $test
     * @param string $zip_file
     */
    public function __construct(bool $test, string $zip_file)
    {
        $this->test = $test;
        $this->zip_file = $zip_file;
    }

    /**
     * @return string
     */
    public function getOutFile(): string
    {
        return storage_path($this->outfile);
    }

    /**
     * @return boolean
     */
    public function unzip()
    {
        $this->zip = new \ZipArchive();
        if ($this->zip->open(storage_path('app/' . $this->zip_file)) === true) {
            $res = ($this->zip->extractTo(storage_path('app/temp/')) && $this->zip->close());
            if ($this->test) {
                $this->csv = 'app/temp/KEN_ALL.CSV';
            } else {
                $this->csv = 'app/temp/13TOKYO.CSV';
            }
            return $res;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public function mb_convert(): bool
    {
        $cp932 = mb_convert_encoding(file_get_contents(storage_path($this->csv)), 'utf-8', 'cp932');
        return file_put_contents(storage_path($this->outfile), $cp932) > 0;
    }
}