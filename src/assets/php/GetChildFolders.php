<?php
/**
 * Created by PhpStorm.
 * User: Pablo
 * Date: 28/02/2018
 * Time: 5:23 p.m.
 */

$folder = file_get_contents('php://input');
$folder = json_decode($folder, true);

$array = array();

$path = $folder['path'] . '\\' . $folder['name'];

if (is_dir($path)) {
    $dirCount = 0;

    $tmp = scanDirs($path);

    foreach ($tmp as $key => $item) {
        if (is_dir($item['path'] . '\\' . $item['name'])) {

//            $tmp = array_merge($tmp, scanDirs($item['path'] . '\\' . $item['name']));
//
//            unset($tmp[$key]);

            $dirCount += 1;
        }
    }

//    foreach ($tmp as $item) {
//        $array[] = $item;
//    }
    $array = $tmp;
    if ((($dirCount / (count(scandir($path, 0)) - 2)) * 100) < 75) {
        $array[]['detailsAccess'] = true;
    }
}

echo json_encode($array);

function folderSize($dir) {
    $size = 0;
    foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSize($each);
    }
    return $size;
}

function scanDirs($path) {
    $array = array();

    foreach (scandir($path, 0) as $item) {
        if ($item != '.' && $item != '..') {

            $tmp['name'] = utf8_encode($item);

            if (is_dir($path . '\\' . $item)) {
                $tmp['size'] = folderSize($path . '\\' . $item);

                $tmp['type'] = 'folder_open';
            } else {
                $tmp['size'] = filesize($path . '\\' . $item);

                $tmp['type'] = fileFormat($path . '\\' . $item);
            }

            if (($tmp['size'] / 1048576) < 1) {
                $tmp['size'] = round($tmp['size'] / 1024, 2) . ' KB';
            } else {
                if (($tmp['size'] / 1073741824) < 1) {
                    $tmp['size'] = round($tmp['size'] / 1048576, 2) . ' MB';
                } else {
                    $tmp['size'] = round($tmp['size'] / 1073741824, 2) . ' GB';
                }
            }

            $tmp['path'] = $path;
            $array[] = $tmp;
        }
    }

    return $array;
}

function fileFormat($path) {

    $arrayDelimitter = explode('.', $path);
    $format = $arrayDelimitter[count($arrayDelimitter) - 1];
    $iconType = "";

    $format = strtolower($format);

    if ($format == 'mkv' || $format == 'mp4' || $format == 'avi' || $format == 'mpeg' || $format == 'mpg' || $format == 'mpeg' || $format == 'rmvb') {
        $iconType = "local_movies";
    } else {
        if ($format == 'srt' || $format == 'sub' || $format == 'txt' || $format == 'doc' || $format == 'docx' || $format == 'pdf') {
            $iconType = "insert_drive_file";
        } else {
            if ($format == 'jpg' || $format == 'jpeg' || $format == 'png' || $format == 'gif' || $format == 'tiff') {
                $iconType = "wallpaper";

//                $originalName = explode('\\', $path)[count(explode('\\', $path)) - 1];
//
//                copy($path, '../img/movies/' . $originalName);
            }
        }
    }

    return $iconType;
}
