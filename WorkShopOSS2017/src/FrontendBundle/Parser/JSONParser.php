<?php
/**
 * Created by PhpStorm.
 * User: meg4r0m
 * Date: 03/10/17
 * Time: 18:35
 */
namespace FrontendBundle\Parser;
class JSONParser
{
    public static function JSONParserGetName($jsonTest){
        $keys = array();
        $BaseArrayCount = count(array_keys($jsonTest));
        echo 'Count Base: '.$BaseArrayCount.'<br /><br />';
        foreach ($jsonTest as $jsonTestLines => $jsonTestLinesValues){
            if (is_int($jsonTestLines)) {
                $arrayCount = $jsonTestLines;
            }else{
                $arrayCount = count($jsonTestLines);
            }
            if (is_array($jsonTestLines && $BaseArrayCount != 1)){
                if (!is_int(array_values(array_keys($jsonTestLines)))) {
                    $keys[] = array_keys($jsonTestLines);
                }
                //echo '|--> '.$jsonTestLines.'<br />';
            }elseif($BaseArrayCount != 1){
                $keys[] = $jsonTestLines;
                echo '|--> '.$jsonTestLines;
            }
            if (is_array($jsonTestLinesValues)){
                if (!is_int(array_values(array_keys($jsonTestLinesValues)))){
                    $keys[]= array_keys($jsonTestLinesValues);
                }
                foreach ($jsonTestLinesValues as $jsonTestLinesValue => $jsonTestLinesChildrenValues){
                    //echo '|----> '.$jsonTestLinesValue;
                    if (is_array($jsonTestLinesChildrenValues)){
                        foreach ($jsonTestLinesChildrenValues as $jsonTestLinesChildrenValue => $jsonTestLinesChildrenD2Values){
                            //echo '|------> '.$jsonTestLinesChildrenValue;
                            if (is_array($jsonTestLinesChildrenD2Values)){
                                foreach ($jsonTestLinesChildrenD2Values as $jsonTestLinesChildrenD2Value => $jsonTestLinesChildrenD3Values){
                                    //echo '|------> '.$jsonTestLinesChildrenD2Value;
                                    if (is_array($jsonTestLinesChildrenD3Values)){
                                        foreach ($jsonTestLinesChildrenD3Values as $jsonTestLinesChildrenD3Value => $jsonTestLinesChildrenD4Values){
                                            //echo '|--------> '.$jsonTestLinesChildrenD3Value;
                                            if (is_array($jsonTestLinesChildrenD4Values)){
                                                foreach ($jsonTestLinesChildrenD4Values as $jsonTestLinesChildrenD4Value => $jsonTestLinesChildrenD5Values){
                                                    //echo '|----------> '.$jsonTestLinesChildrenD4Value;
                                                }
                                            }else{
                                                //echo '::: '.$jsonTestLinesChildrenD4Values.'<br />';
                                            }
                                        }
                                    }else{
                                        //echo '::: '.$jsonTestLinesChildrenD3Values.'<br />';
                                    }
                                }
                            }else{
                                //echo '::: '.$jsonTestLinesChildrenD2Values.'<br />';
                            }
                        }
                    }else{
                        //echo '::: '.$jsonTestLinesChildrenValues.'<br />';
                    }
                }
            }else{
                //echo '::: '.$jsonTestLinesValues.'<br />';
            }
            //echo '<br />';
        }
        return $keys;
    }
}