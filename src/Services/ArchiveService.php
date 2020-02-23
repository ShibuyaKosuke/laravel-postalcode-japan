<?php

namespace ShibuyaKosuke\LaravelPostalcodeJapan\Services;

class ArchiveService
{
    private $zip;
    private $zip_file;
    private $outfile = 'app/temp/ken_all_uft8.csv';

    /**
     * ArchiveService constructor.
     * @param $zip_file
     */
    public function __construct($zip_file)
    {
        $this->zip_file = $zip_file;
    }

    public function getOutFile()
    {
        return storage_path($this->outfile);
    }

    /**
     * @return bool
     */
    public function unzip()
    {
        $this->zip = new \ZipArchive();
        if ($this->zip->open(storage_path('app/' . $this->zip_file)) === true) {
            return ($this->zip->extractTo(storage_path('app/temp/')) && $this->zip->close());
        }
        return false;
    }

    /**
     * @return bool
     */
    public function mb_convert()
    {
        $cp932 = mb_convert_encoding(file_get_contents(storage_path('app/temp/KEN_ALL.CSV')), 'utf-8', 'cp932');
        return file_put_contents(storage_path($this->outfile), $cp932) > 0;
    }
}