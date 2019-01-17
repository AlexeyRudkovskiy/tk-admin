<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 27.07.18
 * Time: 17:06
 */

namespace ARudkovskiy\Admin\Services;


use ARudkovskiy\Admin\Contracts\StorageServiceContract;
use ARudkovskiy\Admin\Contracts\UploadFileContract;
use ARudkovskiy\Admin\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class UploadFile implements UploadFileContract
{

    /** @var StorageServiceContract */
    private $storageService;

    public function __construct()
    {
        $this->storageService = app()->make(StorageServiceContract::class);
    }

    public function handle(Request $request)
    {
        $file = $request->file('upload');
        $folder = $request->get('folder');
        $this->process($file, $folder);
    }

    public function process(UploadedFile $file, string $folder): File
    {
        $folder = !empty($folder) ? $folder: $this->storageService->getDefaultFolder();

        $extension = $file->getClientOriginalExtension();
        $clientOriginalName = $file->getClientOriginalName();

        if (starts_with($folder, '/')) {
            $folder = substr($folder, 1);
        }

        $folder = 'media/' . $folder;

        $targetFolder = 'app/public/' . $folder;
        $targetFolderWithoutLastSlash = null;
        if (!ends_with($targetFolder, '/')) {
            $targetFolderWithoutLastSlash = $targetFolder;
            $targetFolder .= '/';
        } else {
            $targetFolderWithoutLastSlash = substr($targetFolder, 0, -1);;
        }

        while (strpos($targetFolder, '//') !== false) {
            $targetFolder = str_replace('//', '/', $targetFolder);
        }

        if (!is_dir(storage_path($targetFolder))) {
            mkdir(storage_path($targetFolder), 0777, true);
        }

        $clientOriginalName = explode('.', $clientOriginalName);
        array_pop($clientOriginalName);
        $clientOriginalName = implode('.', $clientOriginalName);

        $filenameParts = [ $clientOriginalName, Carbon::now(), auth()->id() ];
        $filename = sha1(implode('@', $filenameParts));

        $mimeType = $file->getMimeType();
        $isImage = substr($mimeType, 0, 5) === 'image';

        if ($isImage) {
            $thumbnails = $this->createThumbnails($file, $filename, $extension, $targetFolder);
        }

        $fileObject = new File();
        $fileObject->name = $filename;
        $fileObject->type = 'image';
        $fileObject->size = $file->getSize();
        $fileObject->folder = $folder;
        $fileObject->extension = $extension;
        $fileObject->thumbnails = $thumbnails;
        $fileObject->original_name = $clientOriginalName;

        $targetFolderFullPath = storage_path($targetFolderWithoutLastSlash);
        $file->move($targetFolderFullPath, $filename . '.' . $extension);

        $fileObject->save();

        return $fileObject;
    }

    private function createThumbnails(UploadedFile $uploadedFile, string $filename, string $extension, string $folder) {
        $thumbnails = config('admin.images.thumbnails', []);
        $createdThumbnails = [];

        foreach ($thumbnails as $thumbnail) {
            list($width, $height) = $thumbnail['size'];
            $isCrop = false;
            $isIntelligentCrop = false;
            $isResize = false;
            $thumbnailName = null;

            if (array_key_exists('crop', $thumbnail)) {
                $isCrop = true;

                if (array_key_exists('intelligent', $thumbnail['crop'])) {
                    $isIntelligentCrop = $thumbnail['crop']['intelligent'];
                }
            }

            if (array_key_exists('resize', $thumbnail)) {
                $isResize = $thumbnail['resize'];
            }

            $thumbnailName = array_key_exists('name', $thumbnail) ?
                $thumbnail['name'] :
                implode('x', [$width, $height]);

            $thumbnailFilename = $folder . $filename . '-' . $thumbnailName . '.' . $extension;

            $image = \Image::make($uploadedFile);
            if ($isCrop) {
                if ($isIntelligentCrop) {
                    $image = $image->resize($width * 2, null, function ($c) {
                        $c->aspectRatio();
                        $c->upsize();
                    });
                }
                $image
                    ->crop($width, $height, null, null)
                    ->save(storage_path($thumbnailFilename));
            }
            if ($isResize) {
                $image = $image->resize($width, $height, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                })->save(storage_path($thumbnailFilename));
            }

            array_push($createdThumbnails, $thumbnailName);
        }

        return $createdThumbnails;
    }

}