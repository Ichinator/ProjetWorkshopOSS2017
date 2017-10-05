<?php
/**
 * Created by PhpStorm.
 * User: tf507967
 * Date: 05/10/2017
 * Time: 10:08
 */
namespace FrontendBundle\Parser;

class ArrayProcessing{
    public static function getArrayKeys(array &$start = array() ){
        $resultkeys = array();
        foreach ($start as $key => $value){

            if(is_array($value))
                $resultkeys[$key] = self::getArrayKeys($value);
            else
                $resultkeys[$key] = '';
        }
        return $resultkeys;
    }

    public static function mergeSameKeys(array $arrays){
        $totalkeys = array();
        foreach ($arrays as $value){
            if( is_array($value) )
                $totalkeys[] = self::getArrayKeys($value);
        }
        $resultKeys = array();
        $resultKeys[] = call_user_func_array('array_intersect_key',$totalkeys);
        $resultKeys[] = call_user_func_array('array_diff_key',$totalkeys);

        return $resultKeys;
    }
}