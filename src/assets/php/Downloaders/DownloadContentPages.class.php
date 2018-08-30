<?php

namespace Downloaders;

/**
 * Created by PhpStorm.
 * User: Pablo
 * Date: 08/09/2017
 * Time: 11:50 a.m.
 */

abstract class DownloadContentPages {
    public static function NtwkConfig() {
        $proxy = "";
        $port = "";

        if (file_exists("../config/config.cfg")) {
            $config = file("../config/config.cfg");

            $proxy = explode('->', $config[0])[1];
            $port = explode('->', $config[1])[1];

            $proxy = str_replace("\r\n", '', $proxy);
            $port = str_replace("\r\n", '', $port);
        }

        return array('proxy' => $proxy, 'port' => $port);
    }

    /**
     * @param string $url de la pagina
     * @param string $cookieName
     * @param string $idCookie
     * @return array (content,cookie)
     */
    public static function getCookies(string $url, string $cookieName, string $idCookie) {
        $ch = curl_init($url);

        $curlConfig = array(
//        CURLOPT_VERBOSE => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPGET => true,
            CURLOPT_HEADER => 1,
            CURLOPT_HTTPHEADER => array("Cookie: " . $cookieName . "=" . $idCookie),
            CURLOPT_BINARYTRANSFER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows 7; Win64; x64; Chrome) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2497.0 Safari/537.36',
            CURLOPT_PROXY => self::NtwkConfig()['proxy'],
            CURLOPT_PROXYPORT => self::NtwkConfig()['port']
        );

        curl_setopt_array($ch, $curlConfig);
        $raw = false;
        while ($raw == false) {
            $raw = curl_exec($ch);
            sleep(5);
            echo $url . "\n\r";
            echo "Reintentando en 5 sec.." . "\n\r";

            if ($raw == false) {
                var_export(curl_error($ch));
            }
        }

        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $raw, $matches);
        $cookies = array();
        foreach ($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }

        var_dump($cookies);
        curl_close($ch);
        return array('content' => $raw, 'cookie' => $cookies);
    }

    /**
     * @param string $url base de la pagina
     * @param $params parametros para la url
     * @param string $cookieName
     * @param string $idCookie
     * @return array (content)
     */
    public static function downloadPOST(string $url, $params, string $cookieName, string $idCookie) {
        $ch = curl_init($url);

        $curlConfig = array(
//        CURLOPT_VERBOSE => 1,
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HEADER => 0,
            CURLOPT_BINARYTRANSFER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows 7; Win64; x64; Chrome) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2497.0 Safari/537.36',
            CURLOPT_PROXY => self::NtwkConfig()['proxy'],
            CURLOPT_PROXYPORT => self::NtwkConfig()['port']
        );

        if ($cookieName != '' && $idCookie != '') {
            $curlConfig[CURLOPT_HTTPHEADER] = array("Cookie: " . $cookieName . "=" . $idCookie);
        }

        curl_setopt_array($ch, $curlConfig);
        $raw = false;
        while ($raw == false) {
            $raw = curl_exec($ch);
            sleep(5);
            echo $url . "\n\r";
            echo "Reintentando en 5 sec.." . "\n\r";

            if ($raw == false) {
                var_export(curl_error($ch));
            }
        }

        curl_close($ch);
        return $raw;
    }

    /**
     * @param string $url de la pagina
     * @param string $cookieName
     * @param string $idCookie
     * @return array (content)
     */
    public static function downloadGET(string $url, string $cookieName, string $idCookie, array $proxyConfig) {
        $ch = curl_init($url);

        $curlConfig = array(
//        CURLOPT_VERBOSE => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPGET => true,
            CURLOPT_HEADER => 0,
            CURLOPT_BINARYTRANSFER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows 7; Win64; x64; Chrome) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2497.0 Safari/537.36',
            CURLOPT_PROXY => $proxyConfig['proxy'],
            CURLOPT_PROXYPORT => $proxyConfig['port'],
            CURLOPT_PROXYAUTH=>$proxyConfig['user'],
            CURLOPT_PROXYPASSWORD=>$proxyConfig['pass']
        );

        if ($cookieName != '' && $idCookie != '') {
            $curlConfig[CURLOPT_HTTPHEADER] = array("Cookie: " . $cookieName . "=" . $idCookie);
        }

        curl_setopt_array($ch, $curlConfig);
        $raw = false;
        while ($raw == false) {
            $raw = curl_exec($ch);
            sleep(5);
//            echo $url . "\n\r";
//            echo "Reintentando en 5 sec.." . "\n\r";
//            echo curl_error($ch);

            if ($raw == false) {
                if (curl_error($ch) == "The requested URL returned error: 500 Internal Server Error") {
                    break;
                }

                if (curl_error($ch) == "The requested URL returned error: 400 Bad Request") {
                    break;
                }
            }
        }

        curl_close($ch);
        return $raw;
    }


    public static function downloadFiles(string $url) {
        ob_start();

        echo "<pre>";
        echo "Descargando archivo ...";

        ob_flush();
        flush();

        $targetFile = fopen('data.csv', 'w');

        $ch = curl_init($url);

        $curlConfig = array(
//        CURLOPT_VERBOSE => 1,
            CURLOPT_URL => $url,
            CURLOPT_NOPROGRESS => false,
            CURLOPT_PROGRESSFUNCTION => array('self', 'progress'),
            CURLOPT_FILE => $targetFile,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows 7; Win64; x64; Chrome) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2497.0 Safari/537.36',
            CURLOPT_PROXY => self::NtwkConfig()['proxy'],
            CURLOPT_PROXYPORT => self::NtwkConfig()['port']
        );

        curl_setopt_array($ch, $curlConfig);
        $raw = curl_exec($ch);

        curl_close($ch);

        echo "FIN";
        ob_flush();
        flush();

        return $raw;
    }

    static function progress($resource, $download_size, $downloaded, $upload_size, $uploaded) {
        if ($download_size > 0)
            echo $downloaded / $download_size * 100;
        ob_flush();
        flush();
        sleep(1); // just to see effect
    }
}