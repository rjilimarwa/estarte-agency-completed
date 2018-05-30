<?php

namespace AppBundle\Controller\BackOffice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\MessageSent;
use AppBundle\Form\Message\MessageSentType;

class MessageSentBackController extends Controller
{

    /**
     * @Route("/messages/envoye", name="back_message_sent_list")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $qb = $em->getRepository('AppBundle:MessageSent')->findBy(array(), array('id' => 'DESC'));
        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 20);

        return $this->render('backOffice/messages/sent/list.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     *
     * @Route("/messages/envoye/ecrire", name="back_message_sent_new")
     */
    public function newAction(Request $request)
    {
        $entity = new MessageSent();
        $form = $this->createForm(MessageSentType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->addFlash('success', 'Le message a été envoyé');

            return $this->redirectToRoute('back_message_sent_show', array(
                'id'=> $entity->getId()
            ));

        } elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Erreur d\'envoie message');
        }

        return $this->render('backOffice/messages/sent/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/messages/envoye/lire/{id}", name="back_message_sent_show")
     */
    public function showAction(Request $request, MessageSent $entity)
    {
        return $this->render('backOffice/messages/sent/show.html.twig', array(
            'entity' => $entity
        ));
    }


    /**
     *
     * @Route("/messages/envoye/supprimer/{id}", name="back_message_sent_delete")
     */
    public function deleteAction(Request $request, MessageSent $entity)
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

