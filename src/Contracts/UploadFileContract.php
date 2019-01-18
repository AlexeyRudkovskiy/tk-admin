<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 27.07.18
 * Time: 17:06
 */

namespace ARudkovskiy\Admin\Contracts;


use ARudkovskiy\Admin\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

interface UploadFileContract
{

    public function handle(Request $request);

    public function process(UploadedFile $file, string $folder): File;

}