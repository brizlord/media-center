<?php
namespace Downloaders;

/**
 * Created by PhpStorm.
 * User: Pablo
 * Date: 08/09/2017
 * Time: 12:09 p.m.
 */
abstract class Write
{
    /**
     * @param string $name FileName
     * @param $raw FileName Content
     */
    public static function SaveCover(string $name, $raw)
    {
        if (file_exists($name)) {
            unlink($name);
        }
        $fp = fopen($name, 'x');
        fwrite($fp, $raw);
        fclose($fp);
//        echo "Portada Guardada..." . "\n\r";
    }

    public static function SaveCSV(string $name, $raw)
    {
        if (file_exists($name)) {
            unlink($name);
        }
        $fp = fopen($name, 'x');
        fwrite($fp, $raw);
        fclose($fp);
        echo "Fichero Guardado..." . "\n\r";
    }
}