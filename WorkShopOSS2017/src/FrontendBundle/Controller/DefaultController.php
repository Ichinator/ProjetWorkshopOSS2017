<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use FrontendBundle\Model\XML2Array;
use FrontendBundle\Parser\JSONparser;

class DefaultController extends Controller
{

    function fetch_url($url = 'https://www.google.fr/', $timeout = 20){

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
        $t = new XML2Array();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            //$jsonTest = json_decode(file_get_contents($data["Link"]), true);
            //$jsonTestFromAllocine = json_decode(file_get_contents('./testallocine.json'), true);
            $jsonTest = json_decode(file_get_contents('./testallocine.json'), true);
            //$jsonTestFormImdb = json_decode(file_get_contents('./testimdb.json'), true);
            //$jsonTest = array_merge($jsonTestFromAllocine,$jsonTestFormImdb);
            //$jsonTest = json_decode(file_get_contents('./photos.json'), true);
            //$keys1 = JSONparser::JSONParserGetName($jsonTestFromAllocine);
            //$keys2 = JSONparser::JSONParserGetName($jsonTestFormImdb);
            $keys = JSONparser::JSONParserGetName($jsonTest);

            //$keys = array_merge($keys1, $keys2);
            echo '<pre>';
                var_dump($keys);
            echo '</pre>';

            $tstart = microtime(true);
            //  $jsonSample1 = json_decode($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/OSM_Metropole_economie.json'),true);
            //  $jsonSample2 = json_decode($this->fetch_url('https://opendata.lillemetropole.fr/api/records/1.0/search/?dataset=magasins-et-boutiques&rows=-1'),true);

            $xmlsamples = array(
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_ANTI.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_COME.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_CORU.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_EURO.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_FOCH.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_GAMB.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_GARE.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_TRIA.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_PITO.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_CIRC.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_GARC.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_MOSS.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_SABI.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_SABL.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_STJ_SJLC.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_MEDC.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_CAS_CDGA.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_MTP_OCCI.xml')),
                XML2Array::createArray($this->fetch_url('http://data.montpellier3m.fr/sites/default/files/ressources/FR_CAS_VICA.xml')),
            );
            $xmltest = array_merge($xmlsamples);
            //  $jsonTest = array_merge($jsonSample1,$jsonSample2);
            $tend = microtime(true);
            echo 'exec : '.($tend-$tstart).'<br/>';
            if(true)
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
        }
        return $this->render('FrontendBundle:Default:index.html.twig', array('form' => $form->createView()));
    }

}
