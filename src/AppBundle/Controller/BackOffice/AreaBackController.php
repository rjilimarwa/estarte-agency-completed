<?php

namespace AppBundle\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Area as BaseEntity;
use AppBundle\Form\Area\AreaType as BaseType;
use AppBundle\Form\Area\AreaEditType as BaseEditType;
use Symfony\Component\Routing\Annotation\Route;

class AreaBackController extends Controller
{
    /**
     * @Route("areas", name="back_office_area_list")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listAction(Request $request)
    {
        $this->denyAccessUnlessGranted(array('ROLE_ADMIN', 'IS_AUTHENTICATED_REMEMBERED'), null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();


        $paginator = $this->get('knp_paginator');

        $qb = $em->getRepository('AppBundle:Area')->findBy(array(), array('name' => 'asc'));

        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 120);

        return $this->render('backOffice/area/list.html.twig', array(
                'entities' => $entities,
                'form' => $this->newFormAction(),
            )
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

        return $this->render('backOffice/area/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("categories", name="back_office_area_new")
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted(array('ROLE_ADMIN', 'IS_AUTHENTICATED_FULLY'), null, 'Unable to access this page!');

        $entity = new BaseEntity();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(BaseType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $city = $form->get('city')->getData();
            $areaName = $form->get('name')->getData();
            $areaNameExists = $em->getRepository('AppBundle:Area')->findOneBy(
                array('name' => $areaName, 'city' => $city));
            if ($areaNameExists) {
                $form->get('name')->addError(new FormError('Cette gouvernorat existe pour cette ville'));
            }

            if ($form->isValid()) {

                $em->persist($entity);
                $em->flush();

                $view = $this->renderView('backOffice/area/tr.html.twig', array('entity' => $entity));

                $this->addFlash('success', 'La région a été ajouté.');

                return new JsonResponse(array(
                    'view' => $view,
                    'result' => 1
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
            'backOffice/area/new.html.twig', array(
                'form' => $form->createView(),
            )
        );
    }


    /**
     * @Route("categories/{id}", name="back_office_area_edit")
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction(Request $request, BaseEntity $entity)
    {
        $this->denyAccessUnlessGranted(array('ROLE_ADMIN', 'IS_AUTHENTICATED_FULLY'), null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(BaseEditType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $request->isXmlHttpRequest()) {
            if ($form->isValid()) {

                $em->persist($entity);
                $em->flush();

                $view = $this->renderView('backOffice/area/tr.html.twig', array('entity' => $entity));

                $this->addFlash('success', 'Région a été modifié.');

                return new JsonResponse(array(
                    'view' => $view,
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

        // !important This will be displayed inside bootstrap Modal
        return $this->render('backOffice/area/edit.html.twig', array(
                'form' => $form->createView(),
                'entity' => $entity
            )
        );
    }


}