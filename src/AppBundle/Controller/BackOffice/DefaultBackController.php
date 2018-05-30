<?php
namespace AppBundle\Controller\BackOffice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class DefaultBackController extends Controller
{
    /**
     * @Route("/", name="back_office_homepage")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
       return $this->render('backOffice/index.html.twig');
    }

    /**
     * Render navbar
     *
     */
    public function navBarAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lastMessages = $em->getRepository('AppBundle:MessageReceived')->findBy(
            array(),array('id'=>'desc'), 2, 0);


        return $this->render('backOffice/includes/navbar.html.twig', array(
                'lastMessages' => $lastMessages,
            )
        );
    }

    /**
     * Render navbar
     *
     */
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        return $this->render('backOffice/includes/menu.html.twig');
    }

}