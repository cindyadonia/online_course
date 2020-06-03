<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class FileService
{
    public function fileURL($filename)
    {
        return url('/api/v1/file').$filename;
    }

    public function originalURL($filename)
    {
        return url('/app/public/file').$filename;
    }
}