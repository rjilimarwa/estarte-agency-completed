<?php
namespace AppBundle\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Category as BaseEntity;
use AppBundle\Form\Category\CategoryType as BaseType;
use AppBundle\Form\Category\CategoryEditType as BaseEditType;

class CategoryBackController extends Controller
{
    /**
     * Show all entities
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listAction(Request $request)
    {
        $this->denyAccessUnlessGranted(array('ROLE_ADMIN', 'IS_AUTHENTICATED_REMEMBERED'), null, 'Unable to access this page!');

        $em = $this->getDoctrine()->getManager();


        $paginator = $this->get('knp_paginator');

        $qb = $em->getRepository('AppBundle:Category')->findAll();

        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 20);

        return $this->render('backOffice/category/list.html.twig', array(
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

        return $this->render('backOffice/category/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * New entity
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

            if ($form->isValid()) {

                $em->persist($entity);
                $em->flush();

                $view = $this->renderView('backOffice/category/tr.html.twig', array('entity' => $entity));

                $this->addFlash('success', 'Le bien a été ajouté.');

                return new JsonResponse(array(
                    'view' => $view,
                    'result'=> 1
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
            'backOffice/category/new.html.twig', array(
                'form' => $form->createView(),
            )
        );
    }


    /**
     * Edit entity
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

                $view = $this->renderView('backOffice/category/tr.html.twig', array('entity' => $entity));

               $this->addFlash('success', 'Catégorie a été modifié.');

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
        return $this->render('backOffice/category/edit.html.twig', array(
                'form' => $form->createView(),
                'entity' => $entity
            )
        );
    }


}