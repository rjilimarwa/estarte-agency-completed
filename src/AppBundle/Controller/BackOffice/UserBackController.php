<?php

namespace AppBundle\Controller\BackOffice;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\User as BaseEntity;

class UserBackController extends Controller
{
    /**
     * @Route("/utilisateurs/{type}", name="back_list_users")
     */
    public function listAction(Request $request, $type)
    {

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $qb = $em->getRepository('AppBundle:User')->listBack($type);

        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 20);

        return $this->render('backOffice/user/list.html.twig', array(
                'entities' => $entities,
            )
        );
    }

    /**
     * @Route("/utilisateurs/voir/{id}", name="back_user_show")
     */
    public function showAction(BaseEntity $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $countProperties = $em->getRepository('AppBundle:Property')->countPropertyByUser($entity);
        return $this->render('backOffice/user/show.html.twig', array(
            'entity' => $entity,
            'countProperties'=>$countProperties
        ));
    }


    /**
     * @Route("/utilisateurs/state/{id}", name="back_user_state")
     */
    public function stateAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        if ($user->isEnabled() == true) {
            $user->setEnabled(false);
        } else {
            $user->setEnabled(true);
        }
        $em->flush();

        $this->addFlash('success', 'Utilisateur a été modifié.');
        return new JsonResponse();
    }


}