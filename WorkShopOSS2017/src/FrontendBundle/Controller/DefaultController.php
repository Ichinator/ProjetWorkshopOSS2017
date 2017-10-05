<?php

namespace FrontendBundle\Controller;

use FrontendBundle\Parser\XMLParser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use FrontendBundle\Parser\JSONparser;

class DefaultController extends Controller
{
    public function indexAction(Request $request){
        $form = $this->createFormBuilder()
            ->add('Links', CollectionType::class, array(
                'entry_type'   => UrlType::class,
                'prototype' => true,
                'allow_add' => true,
                'entry_options'  => array(
                    'attr'      => array('class' => 'email-box')
                ),))
            ->add('Category', ChoiceType::class, array('choices'  => array(
                'XML' => 'XML',
                'JSON' => 'JSON',
                'XLS' => 'XLS',
            ),))
            ->add('send', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $links = $data['Links'];
            $countLinks = count($links);

            if ($data['Category'] == 'XML'){
                $resultsComplete = array_map('array_merge', XMLParser::getSamples());
            }elseif ($data['Category'] == 'JSON'){
                if (is_array($links) && count($links) >= 2) {
                    for ($i = 0; $i < $countLinks; $i++) {
                        if ($i == 0) {
                            $array1 = json_decode(file_get_contents($links[$i]), true);
                        } else {
                            $resultsComplete = JSONparser::mergePerKey($array1, $links[$i]);
                        }
                    }
                }else{
                    $jsonTestFromAllocine = json_decode(file_get_contents('./testallocine.json'), true);
                    $jsonTestFromImdb = json_decode(file_get_contents('./testimdb.json'), true);
                    $resultsComplete = JSONparser::mergePerKey($jsonTestFromAllocine, $jsonTestFromImdb);
                }

            }else{
                $resultsComplete = array("A Venir..." => "En cours");
            }
            return $this->render('FrontendBundle:Default:result.html.twig', array('results' => $resultsComplete));

        }
        return $this->render('FrontendBundle:Default:index.html.twig', array('form' => $form->createView()));
    }

}
