<?php

namespace AppBundle\Controller\BackOffice;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Slider as BaseEntity;
use AppBundle\Form\Slider\SliderType as BaseType;
use AppBundle\Form\Slider\SliderType as BaseEditType;

class SliderBackController extends Controller
{
    /**
     * @Route("/slider", name="back_slider_list")
     *
     * Security("has_role('ROLE_ADMIN')")
     */
    public function listAction(Request $request)
    {
        //$this->denyAccessUnlessGranted(array('ROLE_ADMIN', 'IS_AUTHENTICATED_REMEMBERED'), null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $qb = $em->getRepository('AppBundle:Slider')->findBy(array(), array('id' => 'asc'));

        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 20);

        return $this->render('backOffice/slider/list.html.twig', array(
                'entities' => $entities,
                'form' => $this->newFormAction(),
            )
        );
    }

    /**
     * @Route("/slider/voir/{id}", name="back_slider_show")
     *
     */
    public function showAction(BaseEntity $entity)
    {
        return $this->render('backOffice/slider/show.html.twig', array(
                'entity' => $entity)
        );
    }

    /**
     * Render the new form
     *
     */
    public function newFormAction()
    {
        $entity = new BaseEntity();
        $form = $this->createForm(BaseType::class, $entity);

        return $this->render('backOffice/slider/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/slider/ajouter", name="back_slider_new")
     *
     * Security("has_role('ROLE_ADMIN')")
     */
    public function newAction(Request $request)
    {
        //$this->denyAccessUnlessGranted(array('ROLE_ADMIN', 'IS_AUTHENTICATED_FULLY'), null, 'Unable to access this page!');

        $entity = new BaseEntity();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(BaseType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $entity->setColor($_POST['color']);
                $em->persist($entity);
                $em->flush();

                $this->addFlash('success', 'Photo a été ajouté.');
                return new JsonResponse(array(
                    'view' => $this->renderView('backOffice/slider/tr.html.twig', array('entity' => $entity)),
                ));

            } else {
                $view = $this->renderView('backOffice/form/form_errors.html.twig', array(
                    'form' => $form->createView()));

                return new JsonResponse(array(
                    'view' => $view,
                    'result' => 0,
                ));
            }
        }

        return $this->render(
            'backOffice/slider/new.html.twig', array(
                'form' => $form->createView(),
            )
        );
    }


    /**
     * @Route("/slider/edit/{id}", name="back_slider_edit")
     *
     * Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request, BaseEntity $entity)
    {
        //$this->denyAccessUnlessGranted(array('ROLE_ADMIN', 'IS_AUTHENTICATED_FULLY'), null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(BaseEditType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $entity->setColor($_POST['color']);
            $em->persist($entity);
            $em->flush();

            $view = $this->renderView('backOffice/slider/tr.html.twig', array('entity' => $entity));

            $this->addFlash('success', 'Photo a été modifié.');

            return $this->redirectToRoute('back_slider_list');

        }

        return $this->render('backOffice/slider/edit.html.twig', array(
                'form' => $form->createView(),
                'entity' => $entity
            )
        );
    }


    /**
     * @Route("/slider/supprimer/{id}", name="back_slider_delete")
     *
     * Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, BaseEntity $entity)
    {
        // $this->denyAccessUnlessGranted(array('ROLE_ADMIN', 'IS_AUTHENTICATED_FULLY'), null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $em->remove($entity);
            $em->flush();

            $this->addFlash('success', 'Photo a été supprimé.');
        }

        return new JsonResponse();
    }


    /**
     * @Route("/slider/activer/{id}", name="back_slider_active")
     */
    public function activeAction(Request $request, BaseEntity $entity)
    {

        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            if (false === $entity->getActive()) {
                $entity->setActive(true);
            } else {
                $entity->setActive(false);
            }

            $em->flush();
        }

        return new JsonResponse();
    }

}