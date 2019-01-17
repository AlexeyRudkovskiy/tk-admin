<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 27.07.18
 * Time: 23:53
 */

namespace ARudkovskiy\Admin\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * @package ARudkovskiy\Admin\Models
 *
 * @property string name
 * @property string type
 * @property string extension
 * @property float size
 * @property array thumbnails
 * @property string folder
 * @property string original_name
 */
class File extends Model
{

    protected $casts = [
        'thumbnails' => 'array'
    ];

    protected $appends = [
        'path', 'thumbnails_paths', 'full_path'
    ];

    public function scopeInFolder(Builder $builder, string $folder) {
        return $builder->where('folder', $folder);
    }

    public function getFullPathAttribute()
    {
        $path = $this->getPathAttribute();
        return $path . $this->name . '.' . $this->extension;
    }

    public function getThumbnailPath(string $thumbnail)
    {
        if (in_array($thumbnail, $this->thumbnails)) {
            $nameWithThumbnail = $this->name . '-' . $thumbnail;
            $nameWithExtension = $nameWithThumbnail . '.' . $this->extension;
            $nameWithFolder = $this->folder . '/' . $nameWithExtension;
            $nameWithFolder = str_replace('//', '/', $nameWithFolder);
            return \Storage::url($nameWithFolder);
        }
        return null;
    }

    public function getThumbnailsPathsAttribute()
    {
        return collect($this->thumbnails)
            ->map(function (string $thumbnail) {
                return [ $thumbnail, $this->getFullPathForThumbnail($thumbnail) ];
            })
            ->reduce(function ($thumbnails, $thumbnail) {
                list($key, $value) = $thumbnail;
                $thumbnails[$key] = $value;
                return $thumbnails;
            });
    }

    public function getPathAttribute() {
        $folder = '/storage/' . $this->folder . '/';
        $folder = str_replace('//', '/', $folder);
        return $folder;
    }

    public function getSizeAttribute() {
        $size = $this->attributes['size'];
        for ($i = 0; ($size / 1024) > 0.9; $i++, $size /= 1024) {}
        return round($size, 2) . ' ' . ['B','kB','MB','GB','TB','PB','EB','ZB','YB'][$i];
    }

    public function getFullPathForThumbnail($thumbnail): string
    {
        $path = $this->getPathAttribute();
        return $path . $this->name . '-' . $thumbnail . '.' . $this->extension;
    }

}