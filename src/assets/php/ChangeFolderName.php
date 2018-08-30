<?php
/**
 * Created by PhpStorm.
 * User: Pablo
 * Date: 18/03/2018
 * Time: 9:06 p.m.
 */

$data = file_get_contents("php://input");
$data = json_decode($data, true);

$data['title'] = str_replace(':', '-', $data['title']);

$dir = "";
$oldDir = explode('\\', $data['path']);

foreach ($oldDir as $key => $value) {
    if ($key < count($oldDir) - 1)
        $dir .= $value . '\\';
}

if (rename($data['path'], $dir . $data['title']))
    echo "renamed folder";
else
    echo 'dont renamed';



