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
        }
        return $this->render('FrontendBundle:Default:index.html.twig', array('form' => $form->createView()));
    }

}
