<?php
/**
 * Created by PhpStorm.
 * User: meg4r0m
 * Date: 03/10/17
 * Time: 18:35
 */

namespace FrontendBundle\Parser;


class JSONparser
{
    public static function JSONParserGetName($jsonTest, $keys = array()){
        foreach ($jsonTest as $jsonTestLines){
            if (is_array($jsonTestLines)){
                $keys[] = array_keys($jsonTestLines);
                self::JSONParserGetName($jsonTestLines, $keys);
            }else{
                $keys[] = $jsonTestLines;
            }
        }
        return $keys;
    }

    public static function mergePerKey($array1,$array2){
        $mergedArray = [];
        foreach ($array1 as $key => $value) {
            if(isset($array2[$key])){
                $mergedArray[$value] = null;
                continue;
            }
            $mergedArray[$value] = $array2[$key];
        }
        return $mergedArray;
    }

}