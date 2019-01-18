<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 27.07.18
 * Time: 01:31
 */

namespace ARudkovskiy\Admin\Http\Controllers;


use ARudkovskiy\Admin\Container\AdminContainerInterface;
use ARudkovskiy\Admin\Contracts\StorageServiceContract;
use ARudkovskiy\Admin\Contracts\UploadFileContract;
use ARudkovskiy\Admin\Models\File;
use Illuminate\Http\Request;

class MediaController
{

    protected $adminContainer;

    public function __construct(AdminContainerInterface $adminContainer)
    {
        $this->adminContainer = $adminContainer;
    }

    public function index(Request $request, StorageServiceContract $storageService)
    {
        $isAllOnOnePage = config('admin.file_browser.files_browser', true);

        $files = null;
        $folder = null;
        $folders = null;

        if (!$isAllOnOnePage) {
            $folder = $request->get('folder');
            $folder = urldecode($folder);
            $folder = base64_decode($folder);

            if (starts_with($folder, '/')) {
                $folder = substr($folder, 1, strlen($folder));
            }

            if (empty($folder)) {
                $folder = '';
            }

            $files = File::inFolder($folder);
        } else {
            $files = File::query();
        }

        $filesPagination = $files->latest()
            ->paginate(60);
        $files = $filesPagination
            ->map(function (File $file) {
                $thumbnail = $file->getThumbnailPath('150x150');
                return array_merge($file->toArray(), [
                    'thumbnail' => $thumbnail
                ]);
            });

        if (!$isAllOnOnePage) {
            $folders = \Storage::directories('/public/media/' . $folder);
            $folderEscaped = str_replace('/', '\/', $folder);

            $folders = collect($folders)
                ->map(function (string $folder) {
                    return preg_replace('/^public\/media\//', '', $folder);
                })
                ->map(function (string $folderName) use ($folder, $folderEscaped) {
                    return [
                        'full' => $folderName,
                        'short' => preg_replace('/^' . $folderEscaped . '\//', '', $folderName)
                    ];
                })
                ->map(function (array $folder) {
                    $folder['full'] = starts_with('/', $folder['full']) ?: '/' . $folder['full'];
                    return $folder;
                })
                ->map(function (array $folder) use ($storageService) {
                    $folder['full'] = $storageService->encodeFolder($folder['full']);
                    return $folder;
                });

            if ($folder !== '') {
                $prevFolder = explode('/', $folder);
                array_pop($prevFolder);
                $prevFolder = implode('/', $prevFolder);
                $folders->prepend([
                    'full' => $storageService->encodeFolder($prevFolder),
                    'short' => 'назад'
                ]);
            }
        }

        if ($request->ajax()) {
            return [
                'title' => 'breadcrumbs',
                'folder' => $folder,
                'files' => $files,
                'directories' => $folders
            ];
        }

        return view('@admin::media.index', [
            'title' => 'media',
            'folder' => $folder,
            'files' => $files,
            'directories' => $folders ?? [],
            'breadcrumbs' => $storageService->createBreadcrumbs($folder ?? ''),
            'storage_service' => $storageService,
            'files_pagination' => $filesPagination
        ]);
    }

    public function delete(File $file)
    {
        $filename = $file->name;
        $thumbnails = $file->thumbnails;
        $folder = $file->folder;
        $ext = $file->extension;
        $fullpath = storage_path('app/public/' . $folder . '/' . $filename . '.' . $ext);
        if (is_file($fullpath)) {
            unlink($fullpath);
        }

        foreach ($thumbnails as $thumbnail) {
            $thumbnail_path = storage_path('app/public/' . $folder . '/' . $filename . '-' . $thumbnail . '.' . $ext);
            if (is_file($thumbnail_path)) {
                unlink($thumbnail_path);
            }
        }

        $file->delete();
        return redirect()->back();
    }

    public function upload(Request $request, UploadFileContract $imageContract)
    {
        $uploaded = $request->file('file');
        $folder = $request->get('folder', null);

        if ($folder === null) {
            $folder = '';
        } else {
            $folder = base64_decode(urldecode($folder));
        }

        $uploadedFiles = [];

        foreach ($uploaded as $file) {
            $processed = $imageContract->process($file, $folder);
            array_push($uploadedFiles, $processed);
        }

        if ($request->has('redirect_back')) {
            return redirect()->back();
        }

        return $uploadedFiles;
    }

}