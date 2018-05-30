<?php

namespace AppBundle\Controller\BackOffice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Administrator as BaseEntity;
use AppBundle\Form\Administrator\AdministratorType as BaseType;
use AppBundle\Form\Administrator\AdministratorEditType as BaseEditType;

class AdministratorBackController extends Controller
{
    /**
     * @Route("/administrators", name="back_administrator_list")
     */
    public function listAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $qb = $em->getRepository('AppBundle:Administrator')->findBy(array(), array('id' => 'desc'));

        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 20);

        return $this->render('backOffice/administrator/list.html.twig', array(
                'entities' => $entities,
            )
        );
    }


    public function newFormAction()
    {
        $entity = new BaseEntity();
        $form = $this->createForm(BaseType::class, $entity);

        return $this->render('backOffice/administrator/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/administrators/ajouter", name="back_administrator_new")
     *
     */
    public function newAction(Request $request)
    {

        $entity = new BaseEntity();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(BaseType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $request->getMethod() == 'POST') {

            if ($form->isValid()) {

                $entity->getUser()->addRole('ROLE_ADMIN');

                $em->persist($entity);

                $em->flush();

                $view = $this->renderView('backOffice/administrator/tr.html.twig', array('entity' => $entity));

                $this->addFlash('success', 'Administrateur a été crée.');

                return new JsonResponse(array(
                    'view' => $view,
                ));

            } else {

                $view = $this->renderView('backOffice/form/form_errors_user.html.twig', array(
                'form' => $form->createView()));

                return new JsonResponse(array(
                    'view' => $view,
                    'result' => 0,
                ));
            }
        }

        return $this->render(
            'backOffice/administrator/new.html.twig', array(
                'form' => $form->createView(),
            )
        );
    }


    /**
     * @Route("/administrators/edit/{id}", name="back_administrator_edit")
     *
     */
    public function editAction(Request $request, BaseEntity $entity)
    {

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(BaseEditType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $em->persist($entity);
            $em->flush();
            $this->addFlash('success', 'Administrator a été modifié.');

           return $this->redirectToRoute('back_administrator_list');

        }

        return $this->render('backOffice/administrator/edit.html.twig', array(
                'form' => $form->createView(),
                'entity' => $entity
            )
        );
    }

    /**
     * @Route("/administrators/supprimer/{id}", name="back_administrator_delete")
     *
     */
    public function deleteAction(Request $request, BaseEntity $entity)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $em->remove($entity);
            $em->flush();

            $this->addFlash('success', 'Administrateur a été supprimé.');
        }

        return new JsonResponse();
    }


    /**
     * @Route("/administrators/state/{id}", name="back_administrator_state")
     */
    public function enableAction(Request $request, BaseEntity $entity)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            if (false === $entity->getUser()->isEnabled()) {
                $entity->getUser()->setEnabled(true);
            } else {
                $entity->getUser()->setEnabled(false);
            }
            $em->flush();
        }
        return new JsonResponse();
    }

}