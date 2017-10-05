<?php
/**
 * Created by PhpStorm.
 * User: tf507967
 * Date: 04/10/2017
 * Time: 15:37
 */

namespace FrontendBundle\Parser;
use FrontendBundle\Parser\cURL;
use Symfony\Component\Config\Definition\Exception\Exception;

class XMLParser{

    private static $xml = null;
    private static $encoding = 'UTF-8';

    public static function getSamples(){
        $a = array(
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_ANTI.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_COME.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_CORU.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_EURO.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_FOCH.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_GAMB.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_GARE.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_TRIA.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_PITO.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_CIRC.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_GARC.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_MOSS.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_SABI.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_SABL.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_STJ_SJLC.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_MEDC.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_CAS_CDGA.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_OCCI.xml')),
            self::createArray(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_CAS_VICA.xml')),
            self::createArray('<park xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="parking.xsd"><test>test</test><DateTime>2017-01-23T09:08:54</DateTime><Name>ANTI</Name><Status>Open</Status><Free>0252</Free><Total>0350</Total></park>')
        );
        return $a;
    }

    /**
     * Initialize the root XML node [optional]
     * @param $version
     * @param $encoding
     * @param $format_output
     */
    public static function init($version = '1.0', $encoding = 'UTF-8', $format_output = true) {
        self::$xml = new \DOMDocument($version, $encoding);
        self::$xml->formatOutput = $format_output;
        self::$encoding = $encoding;
    }

    /**
     * Convert an XML to Array
     * @param string $node_name - name of the root node to be converted
     * @param array $arr - aray to be converterd
     * @return DOMDocument
     */
    public static function &createArray($input_xml) {
        if(!$input_xml)
            throw new Exception('[XML2Array] Argument input null');
        $xml = self::getXMLRoot();
        if(is_string($input_xml)) {
            $parsed = $xml->loadXML($input_xml);
            if(!$parsed) {
                throw new Exception('[XML2Array] Error parsing the XML string.');
            }
        } else {
            if(get_class($input_xml) != 'DOMDocument') {
                throw new Exception('[XML2Array] The input XML object should be of type: DOMDocument.');
            }
            $xml = self::$xml = $input_xml;
        }
        $array[$xml->documentElement->tagName] = self::convert($xml->documentElement);
        //$array[substr($xml->documentElement->tagName, stripos($xml->documentElement->tagName, ':')+1)] = self::convert($xml->documentElement);
        self::$xml = null;    // clear the xml node in the class for 2nd time use.
        return $array;
    }

    /**
     * Convert an Array to XML
     * @param mixed $node - XML as a string or as an object of DOMDocument
     * @return mixed
     */
    private static function &convert($node) {
        $output = array();

        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
                $output['@cdata'] = trim($node->textContent);
                break;

            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;

            case XML_ELEMENT_NODE:

                // for each child node, call the covert function recursively
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = self::convert($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        //$t = substr($t, stripos($t, ':')+1);
                        // assume more nodes of same kind are coming
                        if(!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    } else {
                        //check if it is not an empty text node
                        if($v !== '') {
                            $output = $v;
                        }
                    }
                }

                if(is_array($output)) {
                    // if only one node of its kind, assign it directly instead if array($value);
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1) {
                            $output[$t] = $v[0];
                        }
                    }
                    if(empty($output)) {
                        //for empty nodes
                        $output = '';
                    }
                }

                // loop through the attributes and collect them
                if($node->attributes->length) {
                    $a = array();
                    foreach($node->attributes as $attrName => $attrNode) {
                        $a[$attrName] = (string) $attrNode->value;

                    }
                    // if its an leaf node, store the value in @value instead of directly storing it.
                    if(!is_array($output)) {
                        $output = array('@value' => $output);
                    }

                    foreach ($a as $attrName => $value) {
                        $output[$attrName] = $value;
                    }
                    $a = null;
                    //$output['@attributes'] = $a;
                }
                break;
        }
        return $output;
    }

    /*
     * Get the root XML node, if there isn't one, create it.
     */
    private static function getXMLRoot(){
        if(empty(self::$xml)) {
            self::init();
        }
        return self::$xml;
    }

    public static function xmltest(){
        $tstart = microtime(true);

        $xmlsamples = XMLParser::getSamples();
        $xmltest = array_merge($xmlsamples);

        $tend = microtime(true);



        dump( ArrayProcessing::mergeSameKeys($xmltest));
        //dump(   (array_intersect_ukey($xmltest[0],$xmltest[1],'self::emptyKeyValue'))  );


        echo 'exec : '.($tend-$tstart).'<br/>';
        $switch = false;
        if(false)
            if(!$switch)
                foreach ($xmltest as $jsonTestLines => $jsonTestLinesValues){
                    if (is_array($jsonTestLines)){
                        //var_dump(array_diff_key($jsonSample2,$jsonTestLines));
                        echo '|--> '.$jsonTestLines.'<br />';
                    }else{
                        echo '|--> '.$jsonTestLines;
                    }


                    if (is_array($jsonTestLinesValues))
                        self::jsonProcessingRec($jsonTestLinesValues,1);
                    else{
                        echo '::: '.$jsonTestLinesValues.'<br />';
                    }
                    echo '<br />';
                }

        if($switch) {
            $keys = JSONparser::JSONParserGetName($xmltest);
            echo '<pre>';
            var_dump($keys);
            echo '</pre>';
        }
    }

    public static function jsonProcessingRec(array &$jsonNode, $level = 0, &$limit = 128){
        if( $level < $limit && is_int($level) && is_int($limit) )
            foreach ($jsonNode as $jsonChildrenKey => $jsonChildrenValue){
                echo '|--'.str_repeat('-',$level*2).'> '.$jsonChildrenKey;
                if (is_array($jsonChildrenValue))
                    self::jsonProcessingRec($jsonChildrenValue,$level+1,$limit);
                else
                    echo '::: '.$jsonChildrenValue.'<br />';
            }
    }
}