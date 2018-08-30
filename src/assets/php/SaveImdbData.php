<?php
/**
 * Created by PhpStorm.
 * User: Pablo
 * Date: 16/03/2018
 * Time: 9:19 p.m.
 */

include "Downloaders/DownloadContentPages.class.php";
include "Downloaders/Write.class.php";

$data = file_get_contents('php://input');
$data = json_decode($data, true);

$proxyConfig = json_decode($data['proxyConfig'], true);
$data = json_decode($data['data'], true);

$cover = \Downloaders\DownloadContentPages::downloadGET($data['Poster'], '', '', $proxyConfig); //Cover

\Downloaders\Write::SaveCover("../img/movies/" . str_replace(':', '-', $data['Title']) . ".jpeg", $cover); //Guardar

$data['Poster'] = "assets/img/movies/" . str_replace(':', '-', $data['Title']) . ".jpeg";

file_put_contents("../data/" . str_replace(':', '-', $data['Title']) . '.json', json_encode($data));

echo json_encode($data);