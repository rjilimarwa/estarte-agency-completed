<?php

namespace AppBundle\Controller\BackOffice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\MessageSent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Entity\MessageReceived;
use AppBundle\Form\Message\MessageSentType;


class MessageReceivedBackController extends Controller
{
    /**
     * Show all entities
     *
     * @Route("/messages/recu", name="back_message_received_list")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $qb = $em->getRepository('AppBundle:MessageReceived')->findBy(array(), array('id' => 'DESC'));
        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 20);

        return $this->render('backOffice/messages/received/list.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Show all entities
     *
     * @Route("/messages/recu/lire/{id}", name="back_message_received_show")
     */
    public function showAction(MessageReceived $entity)
    {
        $msg = new MessageSent();
        $form = $this->createForm(MessageSentType::class, $msg);
        return $this->render('backOffice/messages/received/show.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }


    /**
     * delete
     *
     * @Route("/messages/recu/supprimer/{id}", name="back_message_received_delete")
     */
    public function deleteAction(Request $request, MessageReceived $entity)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();

            $this->addFlash('success', 'Message a été supprimé.');

            return new JsonResponse();
        }
        return new JsonResponse();
    }

}

