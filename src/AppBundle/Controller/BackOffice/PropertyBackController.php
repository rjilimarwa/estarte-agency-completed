<?php

namespace AppBundle\Controller\BackOffice;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Property as BaseEntity;
use AppBundle\Form\Property\PropertyEditType as BaseEditType;

use AppBundle\Form\Property\PropertyBackSearchType as BaseSearchType;

class PropertyBackController extends Controller
{
    /**
     * Show all entities
     *
     * @Route("/annonces/{interval}", name="back_list_properties")
     */
    public function listAction(Request $request, $interval)
    {

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $qb = $em->getRepository('AppBundle:Property')->listBack($interval);

        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 40);

        return $this->render('backOffice/property/list.html.twig', array(
                'entities' => $entities,
            )
        );
    }

    /**
     * Show entity
     * @Route("/annonces/voir/{id}", name="back_property_show")
     */
    public function showAction(BaseEntity $entity)
    {
        return $this->render('backOffice/property/show.html.twig', array(
                'entity' => $entity)
        );
    }

    /**
     * @Route("/annonces/modifier/{id}", name="back_property_edit")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function editAction(Request $request, BaseEntity $entity)
    {
        $referer = $request->headers->get('referer');
        
        $em = $this->getDoctrine()->getManager();

        $originalPhotos = new ArrayCollection();

        // Create an ArrayCollection of the current Photo objects in the database
        foreach ($entity->getPhotos() as $photo) {
            $originalPhotos->add($photo);
        }

        $form = $this->createForm(BaseEditType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            // supprimer les photos
            foreach ($originalPhotos as $photo) {
                if (false === $entity->getPhotos()->contains($photo)) {
                    $entity->removePhoto($photo);
                    $em->persist($photo);
                    $em->remove($photo);
                }
            }

            $em->persist($entity);
            $em->flush();
            $entity->setRef($entity->getCategory()->getAlias() . '0' . $entity->getId());
            $em->flush();
            $this->addFlash('success', 'l\'annonce a été modifié');

            if(isset($_POST['referer']) and $_POST['referer'] != ''){
                return $this->redirect($_POST['referer']);
            }

            return $this->redirectToRoute('back_list_properties', array('interval' => 'tous'));
        }
        return $this->render('backOffice/property/edit.html.twig', array(
                'form' => $form->createView(),
                'entity' => $entity,
                'referer'=> $referer
            )
        );
    }


    /**
     * Delete entity
     * @Route("/annonces/supprimer/{token}", name="back_property_delete")
     */
    public function deleteAction(Request $request, BaseEntity $entity)
    {

        $em = $this->getDoctrine()->getManager();

        $title = $entity->getTitle();
        if ($request->isXmlHttpRequest()) {
            $em->remove($entity);
            $em->flush();

            $message = \Swift_Message::newInstance()
                ->setSubject('Annonce supprimée: ' . $title)
                ->setFrom(array(
                    $this->getParameter('mailer_user_no_reply') => $this->getParameter('sender_name')
                ))
                ->setTo($entity->getUser()->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/property_deleted.html.twig', array(
                            'title' => $title
                        )
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            $this->addFlash('success', 'L\'annonce a été supprimée.');
        }

        return new JsonResponse();
    }


    /**
     * Publish or do not publish entity
     * @Route("/annonces/state/{token}/valid", name="back_property_valid")
     */
    public function validAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Property')->findOneBy(array('token' => $token));

        $referer = $request->headers->get('referer');
        $entity->setState('valid');
        $em->flush();

        $this->addFlash('success', 'L\'annonce a été acceptée.');

        $message = \Swift_Message::newInstance()
            ->setSubject('Annonce acceptée: ' . $entity->getTitle())
            ->setFrom(array(
                $this->getParameter('mailer_user_no_reply') => $this->getParameter('sender_name')
            ))
            ->setTo($entity->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/property_valid.html.twig', array(
                        'entity' => $entity
                    )
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);

        return $this->redirect($referer);
    }

    /**
     * Publish or do not publish entity
     * @Route("/annonces/state/{token}/reject", name="back_property_reject")
     */
    public function rejectAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Property')->findOneBy(array('token' => $token));

        $referer = $request->headers->get('referer');
        $entity->setState('rejected');
        $em->flush();

        $message = \Swift_Message::newInstance()
            ->setSubject('Annonce refusée: ' . $entity->getTitle())
            ->setFrom(array(
                $this->getParameter('mailer_user_no_reply') => $this->getParameter('sender_name')
            ))
            ->setTo($entity->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/property_rejected.html.twig', array(
                        'entity' => $entity
                    )
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);

        $this->addFlash('success', 'L\'annonce a été rejetée.');
        return $this->redirect($referer);
    }


    /**
     * Render the search form
     */
    public function searchFormAction(Request $request)
    {
        $entity = new BaseEntity();
        $form = $this->createForm(BaseSearchType::class, $entity);
        $form->handleRequest($request);
        return $this->render('backOffice/property/search.html.twig', array(
            'form_search' => $form->createView(),
        ));
    }

    /**
     * Search entities by specific properties
     *
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $data = $request->query->all();
        $qb = $em->getRepository('AppBundle:Property')->searchBack($data);

        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 30);

        if ($request->isXmlHttpRequest()) {
            $view = $this->renderView('backOffice/property/list.html.twig', array(
                'entities' => $entities,
            ));
            $response = new Response($view);

            return $response;
        }

        return $this->render('backOffice/property/list.html.twig', array(
            'entities' => $entities,
        ));
    }


}