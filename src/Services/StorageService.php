<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 29.07.18
 * Time: 21:14
 */

namespace ARudkovskiy\Admin\Services;


use ARudkovskiy\Admin\Contracts\StorageServiceContract;
use Carbon\Carbon;

class StorageService implements StorageServiceContract
{

    public function createBreadcrumbs(string $path): array
    {
        $breadcrumbs = [];
        if (strpos($path, '/') !== false) {
            $path = explode('/', $path);
            $pieces = [];

            foreach ($path as $folder) {
                if (empty($folder)) {
                    continue;
                }

                array_push($pieces, $folder);

                $item = [];
                $item['short'] = $folder;
                $item['full'] = $this->encodeFolder(implode('/', $pieces));

                array_push($breadcrumbs, $item);
            }
        }

        array_unshift($breadcrumbs, [
            'short' => 'Файловое хранилище',
            'full' => $this->encodeFolder('/')
        ]);

        return $breadcrumbs;
    }

    public function encodeFolder(string $folder): string
    {
        return urlencode(base64_encode($folder));
    }

    public function decodeFolder(string $encoded): string
    {
        return urldecode(base64_decode($encoded));
    }

    public function getDefaultFolder(): string
    {
        $date = Carbon::now();
        $year = $date->year;
        $month = $date->month;
        $month = '00' . $month;

        $month = substr($month, -2);

        return implode('/', [ $year, $month ]);
    }

}