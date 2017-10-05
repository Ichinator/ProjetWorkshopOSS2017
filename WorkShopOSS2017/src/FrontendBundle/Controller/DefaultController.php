<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use FrontendBundle\Parser\JSONparser;
use FrontendBundle\Parser\ArrayProcessing;
use FrontendBundle\Parser\XMLParser;
use FrontendBundle\Parser\cURL;
use Symfony\Component\Debug\Debug;

class DefaultController extends Controller
{
    static function emptyKeyValue($key1,$key2){
        if($key1 == $key2)
            return 0;
        else if ($key1 > $key2)
            return 1;
        else
            return -1;
    }

    function xmltest(){
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
                    $this->jsonProcessingRec($jsonTestLinesValues,1);
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

    function jsontest(){
        $jsonSample1 = json_decode(cURL::fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/OSM_Metropole_economie.json'),true);
        $jsonSample2 = json_decode(cURL::fetch_url('https://opendata.lillemetropole.fr/api/records/1.0/search/?dataset=magasins-et-boutiques&rows=-1'),true);
        $jsonTest = array_merge($jsonSample1,$jsonSample2);
    }

    function arrayProccesingRec(array &$jsonNode, &$arrayKey = array(), $level = 0, $debug = false, &$limit = 128){
        if( $level < $limit && is_int($level) && is_int($limit) )
            foreach ($jsonNode as $jsonChildrenKey => $jsonChildrenValue){
                if($debug) echo '|--'.str_repeat('-',$level*2).'> '.$jsonChildrenKey;
                if (is_array($jsonChildrenValue))
                    $this->jsonProcessingRec($jsonChildrenValue,$level+1,$limit);
                else
                    if($debug) echo '::: '.$jsonChildrenValue.'<br />';
            }
    }

    function jsonProcessingRec(array &$jsonNode, $level = 0, &$limit = 128){
        if( $level < $limit && is_int($level) && is_int($limit) )
            foreach ($jsonNode as $jsonChildrenKey => $jsonChildrenValue){
                echo '|--'.str_repeat('-',$level*2).'> '.$jsonChildrenKey;
                if (is_array($jsonChildrenValue))
                    $this->jsonProcessingRec($jsonChildrenValue,$level+1,$limit);
                else
                    echo '::: '.$jsonChildrenValue.'<br />';
            }
    }


    public function indexAction(Request $request)
    {
        //$defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder()
            ->add('Link', TextType::class)
            ->add('Category', TextType::class)
            ->add('send', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();



            $this->xmltest();

            $jsonTestFromImdb = json_decode(file_get_contents('./testimdb.json'), true);
            //$jsonTest = json_decode(file_get_contents('./photos.json'), true);
            $resultsName1 = JSONparser::JSONParserGetName($jsonTestFromAllocine, $keys = array());
            $resultsName2 = JSONparser::JSONParserGetName($jsonTestFromImdb, $keys = array());
            $resultsName3 = array_map('array_merge', $resultsName1, $resultsName2);
            $resultsComplete = array_map('array_merge', $jsonTestFromAllocine, $jsonTestFromImdb);


            return $this->render('FrontendBundle:Default:result.html.twig', array('resultsName' => $resultsName3,
                                                                                       'results' => $resultsComplete));

        }
        return $this->render('FrontendBundle:Default:index.html.twig', array('form' => $form->createView()));
    }

}
