<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

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
            $jsonTestFormImdb = json_decode(file_get_contents('./testimdb.json'), true);
            $jsonTest = array_merge($jsonTestFromAllocine,$jsonTestFormImdb);
            //$jsonTest = json_decode(file_get_contents('./photos.json'), true);
            foreach ($jsonTest as $jsonTestLines => $jsonTestLinesValues){
                if (is_array($jsonTestLines)){
                    var_dump(array_diff_key($jsonTestFormImdb,$jsonTestLines));
                    echo '|--> '.$jsonTestLines.'<br />';
                }else{
                    echo '|--> '.$jsonTestLines;
                }
                if (is_array($jsonTestLinesValues)){
                    foreach ($jsonTestLinesValues as $jsonTestLinesValue => $jsonTestLinesChildrenValues){
                        echo '|----> '.$jsonTestLinesValue;
                        if (is_array($jsonTestLinesChildrenValues)){
                            foreach ($jsonTestLinesChildrenValues as $jsonTestLinesChildrenValue => $jsonTestLinesChildrenD2Values){
                                echo '|------> '.$jsonTestLinesChildrenValue;
                                if (is_array($jsonTestLinesChildrenD2Values)){
                                    foreach ($jsonTestLinesChildrenD2Values as $jsonTestLinesChildrenD2Value => $jsonTestLinesChildrenD3Values){
                                        echo '|------> '.$jsonTestLinesChildrenD2Value;
                                        if (is_array($jsonTestLinesChildrenD3Values)){
                                            foreach ($jsonTestLinesChildrenD3Values as $jsonTestLinesChildrenD3Value => $jsonTestLinesChildrenD4Values){
                                                echo '|--------> '.$jsonTestLinesChildrenD3Value;
                                                if (is_array($jsonTestLinesChildrenD4Values)){
                                                    foreach ($jsonTestLinesChildrenD4Values as $jsonTestLinesChildrenD4Value => $jsonTestLinesChildrenD5Values){
                                                        echo '|----------> '.$jsonTestLinesChildrenD4Value;
                                                    }
                                                }else{
                                                    echo '::: '.$jsonTestLinesChildrenD4Values.'<br />';
                                                }
                                            }
                                        }else{
                                            echo '::: '.$jsonTestLinesChildrenD3Values.'<br />';
                                        }
                                    }
                                }else{
                                    echo '::: '.$jsonTestLinesChildrenD2Values.'<br />';
                                }
                            }
                        }else{
                            echo '::: '.$jsonTestLinesChildrenValues.'<br />';
                        }
                    }
                }else{
                    echo '::: '.$jsonTestLinesValues.'<br />';
                }
                echo '<br />';
            }
        }
        return $this->render('FrontendBundle:Default:index.html.twig', array('form' => $form->createView()));
    }

}
