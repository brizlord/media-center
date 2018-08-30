<?php
/**
 * Created by PhpStorm.
 * User: Pablo
 * Date: 01/03/2018
 * Time: 12:36 p.m.
 */

$path = file_get_contents('php://input');
$path = str_replace('"', '', $path);

$tmp = array('size'=>0);

foreach (scandir($path, 0) as $item) {
    if ($item != '.' && $item != '..') {
        if (is_dir($path . '\\' . $item)) {
            $tmp['size'] += folderSize($path . '\\' . $item);
        } else {
            $tmp['size'] += filesize($path . '\\' . $item);
        }
    }
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

echo json_encode($tmp);

function folderSize($dir) {
    $size = 0;
    foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
        $size += is_file($each) ? filesize($each) : folderSize($each);
    }

    return $size;
}