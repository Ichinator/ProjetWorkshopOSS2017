<?php
/**
 * Created by PhpStorm.
 * User: tf507967
 * Date: 04/10/2017
 * Time: 15:53
 */
namespace FrontendBundle\Parser;
class cURL{

    public static function fetch_url($url = 'https://www.google.fr/', $timeout = 20){

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        if (preg_match('`^https://`i', $url))
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Récupération du contenu retourné par la requête
        $page_content = curl_exec($ch);

        curl_close($ch);
        return $page_content;
    }
}