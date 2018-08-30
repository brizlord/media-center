<?php
/**
 * Created by PhpStorm.
 * User: Pablo
 * Date: 19/03/2018
 * Time: 2:03 p.m.
 */

$config = file_get_contents("php://input");
$config = json_decode($config, true);

echo json_encode(file_put_contents("../config/config.cfg", "proxy->" . $config['config']['proxy'] . "\r\n" . "port->" . $config['config']['port']));