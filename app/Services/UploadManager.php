<?php

namespace App\Services;

use URL;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadManager
{
    protected $disk;

    public function __construct()
    {
        $this->disk = Storage::disk(config('filesystems.default', 'public'));
    }

    public function store(UploadedFile $file, $dir = '')
    {
        $hashName = str_ireplace('.jpeg', '.jpg', $file->hashName());

        $mime = $file->getMimeType();

        $realname = $this->disk->putFileAs($dir, $file, $hashName);

        return [
            'success' => true,
            'filename' => $hashName,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $mime,
            'size' => $file->getClientSize(),
            'relative_url' => "storage/$realname",
            'url' => URL::previous() . "storage/$realname"
        ];
    }
}