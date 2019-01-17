<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.07.18
 * Time: 18:28
 */

namespace ARudkovskiy\Admin\EntityFields;


use ARudkovskiy\Admin\Contracts\EntityField;
use ARudkovskiy\Admin\Traits\Menuable;
use Cocur\Slugify\Slugify;
use Illuminate\Http\Request;
use Goutte\Client;

class WYSIWYGField extends EntityField
{

    protected $template = '@admin::form.widget.wysiwyg';

    public function handleRequest(Request $request, $entityObject)
    {
        $updatedValue = $request->get($this->name);
        if (empty($updatedValue)) {
            $updatedValue = '<p>&nbsp;</p>';
        }
        $matches = null;
        preg_match_all('/( ?)style=\"(([a-zA-Z0-9\;\:\ ]+)| ?)\"/im', $updatedValue, $matches);
        $matches = array_unique($matches[0]);
        foreach ($matches as $match) {
            $updatedValue = str_replace($match, '', $updatedValue);
        }

        $this->value = $updatedValue;

        $saveWithoutSlashes = $this->getOption('save_without_slashes');
        if ($saveWithoutSlashes !== null) {
            $updatedValue = strip_tags($updatedValue);
            $updatedValue = str_replace(PHP_EOL, ' ', $updatedValue);
            $updatedValue = str_replace("\r", '', $updatedValue);
            $updatedValue = str_replace("\t", '', $updatedValue);
            $updatedValue = str_replace("\a", '', $updatedValue);
            while (strpos($updatedValue, '  ') !== false) {
                $updatedValue = str_replace("  ", ' ', $updatedValue);
            }
            $updatedValue = trim($updatedValue);
            $updatedValue = str_replace('[read-more]', ' ', $updatedValue);
            $entityObject->{$saveWithoutSlashes} = $updatedValue;
        }
    }

//    public function copyFiles($content)
//    {
//        $isHaveChanges = false;
//
//        $replace_with_processed = '';
//
//        preg_match_all("%<script type=\"text/javascript\">\n?( +)?ajax_loadblock\('([a-z0-9]+)','([a-zA-Z0-9\&\\\/\:\.\?\=\_\%]+)',null\);\n?( +)?</script>%i", $content, $ajax_loads);
//        foreach ($ajax_loads[3] as $index => $ajax_url) {
//            $to_replace = $ajax_loads[0][$index];
//            $replace_with = file_get_contents($ajax_url);
//
//            $replace_with = str_replace('<META content="text/html; charset=windows-1251" http-equiv="Content-Type">', '', $replace_with);
//
//            $styles_start = strpos($replace_with, '<style');
//            $styles_end = strpos($replace_with, '</style>');
//
//            if ($styles_start !== false) {
//                $styles = mb_substr($replace_with, $styles_start, $styles_end - $styles_start + strlen('</style>'));
//                $replace_with_processed .= $styles;
//            }
//
//            $body_start = mb_strpos($replace_with, '<body>');
//            $body_end = mb_strpos($replace_with, '</body>');
//
//            if ($body_start !== false) {
//                $body_start += strlen('<body>');
//                $body = mb_substr($replace_with, $body_start, $body_end - $body_start);
//                $replace_with_processed .= $body;
//            }
//
//            $content = str_replace($to_replace, $replace_with_processed, $content);
//        }
//
//        preg_match_all("%href=\"http://(web\.znu\.edu\.ua|tk\.znu\.edu\.ua)/([a-z0-9\:\\\/\.\?\=\&\_\%\-]+)%im", $content, $links);
//        preg_match_all("%img src=\"http://(web\.znu\.edu\.ua|tk\.znu\.edu\.ua)/([a-z0-9\:\\\/\.\?\=\&\_\%\-]+)%im", $content, $images);
//        preg_match_all("%img align=\"(left|right|center)\" src=\"http://(web\.znu\.edu\.ua|tk\.znu\.edu\.ua)/([a-z0-9\:\\\/\.\?\=\&\_\%\-]+)%im", $content, $images_with_align);
//
//        $c = 0;
//
//        foreach ($links[2] as $index => $link) {
//            if (strpos($link, '=gallery/photo') !== false) {
//                $fullLink = 'http://' . $links[1][$index] . '/' . $link;
//                $page_content = file_get_contents($fullLink);
//                if (mb_strlen($page_content) === mb_strlen('Галерея не знайдена')) {
//                    continue;
//                }
//                preg_match_all("%img src=\"([a-z0-9\:\\\/\.\?\=\&\_\%\-]+)%im", $page_content, $_images);
//                $image = $_images[1][1];
//                $copiedPath = $this->copyFile($image);
//                $originalLink = $links[1][$index] . '/' . $link;
//                $originalLink = str_replace('//', '/', $originalLink);
//                $originalLink = 'http://' . $originalLink;
//                $content = str_replace($originalLink, $copiedPath, $content);
//            } else {
//                $fullLink = 'http://' . $links[1][$index] . '/' . $link;
////                if (ends_with($fullLink, '.ukr.html')) {
////                    $client = new Client();
////                    $crawler = $client->request('GET', $fullLink);
////                    $content = $crawler->filter('.column2 > .col2_block > .text')->first();
////                    $title = $content->filter('h4');
////                    $contentHtml = $content->html();
////                    $contentHtml = str_replace($title->html(), '', $contentHtml);
////                    $contentHtml = str_replace('<h4></h4>', '', $contentHtml);
////                    $contentHtml = rtrim(trim($contentHtml));
////
////                    $entityClass = $this->getEntity()->getEntityClass();
////                    /** @var Menuable $entityObject */
////                    $entityObject = new $entityClass;
////
////                    $newEntity = $this->getEntity()->createAndSave();
////                    $request = new Request([], [
////                        'content' => $contentHtml,
////                        'title' => $title->text(),
////                        'author' => 1
////                    ]);
////
////                    $newEntity->handleRequest($request);
////                    $newEntity->save();
////
////                    $newPageUrl = $entityObject->getUrl();
////                    $rootUrl = request()->root();
////                    $newPageUrl = str_replace($rootUrl, '', $newPageUrl);
////                    $content = str_replace($fullLink, $newPageUrl, $content);
////                    dd($content);
////                } else {
//                    $copiedPath = $this->copyFile($fullLink);
//                    $content = str_replace($fullLink, $copiedPath, $content);
////                }
//            }
//        }
//
//        foreach ($images[2] as $index => $image) {
//            $url = $images[1][$index] . '/' . $image;
//            $url = str_replace('//', '/', $url);
//            $url = 'http://' . $url;
//            $copiedPath = $this->copyFile($url);
//
//            $to_replace = 'http://' . $images[1][$index] . '/' . $image;
//            $content = str_replace($to_replace, $copiedPath, $content);
//            $isHaveChanges = true;
//        }
//
//        foreach ($images_with_align[3] as $index => $image) {
//            $url = $images_with_align[2][$index] . '/' . $image;
//            $url = str_replace('//', '/', $url);
//            $url = 'http://' . $url;
//            $copiedPath = $this->copyFile($url);
//
//            $to_replace = 'http://' . $images_with_align[2][$index] . '/' . $image;
//            $content = str_replace($to_replace, $copiedPath, $content);
//            $isHaveChanges = true;
//        }
//
//        return $content;
//    }
//
//    protected function copyFile($url) {
//        $image = str_replace('http://tk.znu.edu.ua/', '', $url);
//        $image = str_replace('http://web.znu.edu.ua/', '', $image);
//        $image = 'public/' . $image;
//        $image = str_replace('//', '/', $image);
//        $_image = explode('/', $image);
//        $filename = array_pop($_image);
//        $base_path = storage_path('app');
//        $base_path .= '/' . implode('/', $_image);
//        if (!is_dir($base_path)) {
//            mkdir($base_path, 0777, true);
//        }
//        chdir($base_path);
//        exec("wget {$url} -q > /dev/null", $output);
//        $image = str_replace('public/', 'storage/', $image);
//        if (!starts_with($image, '/')) {
//            $image = '/' . $image;
//        }
//        return $image;
//    }


}