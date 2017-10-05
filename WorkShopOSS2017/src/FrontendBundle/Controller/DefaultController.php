<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use FrontendBundle\Parser\JSONparser;

class DefaultController extends Controller
{

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
            //$jsonTest = json_decode(file_get_contents($data["Link"]), true);
            $jsonTestFromAllocine = json_decode(file_get_contents('./testallocine.json'), true);
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
