<?php
/**
 * Created by PhpStorm.
 * User: Pablo
 * Date: 18/03/2018
 * Time: 10:21 p.m.
 */

$db = new SQLite3('../data/data.db');

$results = $db->query("SELECT category FROM folders");
$array = array();

while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    $array[] = $row['category'];
}

echo json_encode($array);