<?php
/**
 * Created by PhpStorm.
 * User: Pablo
 * Date: 28/02/2018
 * Time: 1:10 p.m.
 */

$category = file_get_contents("php://input");
$category = json_decode($category, true);

AllMoviesFolder::GetAllMoviesFolder($category);

abstract class AllMoviesFolder {

    static function GetAllMoviesFolder($category) {
        $db = new SQLite3('../data/data.db');

        $results = $db->query("SELECT * FROM folders WHERE category='{$category['category']}'");

        $array = array();

        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            foreach (scandir($row['path'], 0) as $item) {
                if ($item != '.' && $item != '..') {
                    $tmp['name'] = utf8_encode($item);
                    if (is_dir($row['path'] . '\\' . $item)) {
                        $tmp['size'] = self::folderSize($row['path'] . '\\' . $item);
                    } else {
                        $tmp['size'] = filesize($row['path'] . '\\' . $item);
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

                    $tmp['path'] = $row['path'];
                    $array[] = $tmp;
                }
            }
        }

        $db->close();

        echo json_encode($array);
    }

    static function folderSize($dir) {
        $size = 0;
        foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : self::folderSize($each);
        }
        return $size;
    }
}

