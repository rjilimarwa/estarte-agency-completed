<?php

namespace AppBundle\Controller\BackOffice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class StatisticBackController extends Controller
{
    /**
     * @Route("/statistiques", name="back_office_statistic")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $statisticProperties = $em->getRepository('AppBundle:Property')->StatisticProperties();
        $countPropertiesValid = $em->getRepository('AppBundle:Property')->countPropertiesValid();
        $countPropertiesRejected = $em->getRepository('AppBundle:Property')->countPropertiesRejected();
        $countPropertiesWaiting = $em->getRepository('AppBundle:Property')->countPropertiesWaiting();
        $countPropertiesOnline = $em->getRepository('AppBundle:Property')->countPropertiesOnline();
        $countUsers = $em->getRepository('AppBundle:User')->countUsers();

        return $this->render('backOffice/statistic/index.html.twig', array(
            'countProperties'=> $statisticProperties['countProperties'],
            'countViews' => $statisticProperties['countViews'],
            'countPropertiesValid'=> $countPropertiesValid['countValid'],
            'countPropertiesRejected'=> $countPropertiesRejected['countRejected'],
            'countPropertiesWaiting'=> $countPropertiesWaiting['countWaiting'],
            'countPropertiesOnline'=> $countPropertiesOnline['countOnline'],
            'countUsers'=> $countUsers['countUsers']
        ));
    }

}