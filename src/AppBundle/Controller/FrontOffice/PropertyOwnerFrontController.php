<?php

namespace AppBundle\Controller\FrontOffice;

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Property as BaseEntity;
use AppBundle\Form\Property\PropertyType as BaseType;
use AppBundle\Form\Property\PropertyEditType as BaseEditType;
use AppBundle\Form\Property\PropertyFrontSearchType as BaseSearchType;

class PropertyOwnerFrontController extends Controller
{

    /**
     * @Route("/profile/mes-annonces", name="front_my_properties_list")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $qb = $em->getRepository('AppBundle:Property')->findBy(array('user' => $user), array('id' => 'desc'));
        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 24);

        return $this->render('frontOffice/property/my_properties.html.twig', array(
                'entities' => $entities,
                'ids' => $this->get('my_favorites.properties')
            )
        );
    }

    /**
     * @Route("/profile/mes-annonces/voir/{slug}", name="front_my_properties_show")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function showAction(BaseEntity $entity)
    {
        $this->denyAccessUnlessGranted('view', $entity);

        $em = $this->getDoctrine()->getManager();

        $others = $em->getRepository('AppBundle:Property')->findBy(array('active' => true), array('id' => 'desc'), 4, 0);

        return $this->render('frontOffice/property/show.html.twig', array(
            'entity' => $entity,
            'others' => $others,
            'ids' => $this->get('my_favorites.properties')

        ));
    }

    /**
     * @Route("/profile/mes-annonces/ajouter", name="front_my_properties_new")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function newAction(Request $request)
    {
        $entity = new BaseEntity();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(BaseType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $request->getMethod() == 'POST') {
            if ($form->isValid()) {
                $entity->setUser($this->getUser());
                $entity->setToken(time() . md5($_SERVER['REMOTE_ADDR']));
                $em->persist($entity);
                $em->flush();
                $entity->setRef($entity->getCategory()->getAlias().$entity->getId());
                $em->flush();
                $this->addFlash('success', 'Votre annonce a été ajouté.');
                return $this->redirectToRoute('front_my_properties_list');
            }
        }
        return $this->render(
            'frontOffice/property/new.html.twig', array(
                'form' => $form->createView(),
            )
        );
    }


    /**
     * @Route("/profile/mes-annonces/modifier/{token}", name="front_my_properties_edit")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function editAction(Request $request, BaseEntity $entity)
    {
        $this->denyAccessUnlessGranted('edit', $entity);
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
            return $this->redirectToRoute('front_my_properties_list');
        }
        return $this->render('frontOffice/property/edit.html.twig', array(
                'form' => $form->createView(),
                'entity' => $entity
            )
        );
    }

    /**
     * @Route("/profile/mes-annonces/supprimer/{token}", name="front_my_properties_delete")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function deleteAction(Request $request, BaseEntity $entity)
    {
        $this->denyAccessUnlessGranted('delete', $entity);

        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            $em->remove($entity);
            $em->flush();
            $this->addFlash('success', 'Le bien a été supprimé.');

            $totalAn = $em->getRepository('AppBundle:Statistic')->findOneBy(array('alias' => 'tot-an'));
            $totalAn->setValue($totalAn->getValue() - 1);

            $totalAnValid = $em->getRepository('AppBundle:Statistic')->findOneBy(array('alias' => 'tot-an-valid'));
            $totalAnValid->setValue($totalAnValid->getValue() - 1);

            $totalAttente = $em->getRepository('AppBundle:Statistic')->findOneBy(array('alias' => 'tot-an-att'));
            $totalAttente->setValue($totalAttente->getValue() - 1);

            $totalAnRej = $em->getRepository('AppBundle:Statistic')->findOneBy(array('alias' => 'tot-an-rej'));
            $totalAnRej->setValue($totalAnRej->getValue() - 1);

            $totalAnPublie = $em->getRepository('AppBundle:Statistic')->findOneBy(array('alias' => 'tot-an-publie'));
            $totalAnPublie->setValue($totalAnPublie->getValue() - 1);

            $em->flush();
        }
        return new JsonResponse();
    }

    /**
     * Render the search form
     */
    public function searchFormAction(Request $request)
    {
        $entity = new BaseEntity();
        $form = $this->createForm(BaseSearchType::class, $entity);
        $form->handleRequest($request);
        return $this->render('frontOffice/property/search_my_properties.html.twig', array(
            'form_search' => $form->createView(),
        ));
    }

    /**
     * @Route("/profile/mes-annonces/chercher", name="front_my_properties_search")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function searchAction(Request $request)
    {
        // if user can create so he is logged in
        $this->denyAccessUnlessGranted('create');

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $data = $request->query->all();
        $qb = $em->getRepository('AppBundle:Property')->searchInMyProperties($data, $user);

        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 30);

        if ($request->isXmlHttpRequest()) {
            $view = $this->renderView('frontOffice/property/my_properties.html.twig', array(
                'entities' => $entities,
                'ids' => $this->get('my_favorites_properties')
            ));
            $response = new Response($view);

            return $response;
        }

        return $this->render('frontOffice/property/my_properties.html.twig', array(
            'entities' => $entities,
            'ids' => $this->get('my_favorites_properties')
        ));
    }


    /**
     * @Route("/profile/mes-annonces/activer/{token}", name="front_my_properties_active")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function activeAction(Request $request, BaseEntity $entity)
    {
        $this->denyAccessUnlessGranted('edit', $entity);

        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            if (false === $entity->getActive()) {
                $entity->setActive(true);
                $totalAnPublie = $em->getRepository('AppBundle:Statistic')->findOneBy(array('alias' => 'tot-an-pub'));
                $totalAnPublie->setValue($totalAnPublie->getValue() + 1);
            } else {
                $entity->setActive(false);
                $totalAnPublie = $em->getRepository('AppBundle:Statistic')->findOneBy(array('alias' => 'tot-an-pub'));
                $totalAnPublie->setValue($totalAnPublie->getValue() - 1);
            }
            $em->flush();
            $this->addFlash('success', 'l\'annonce a été modifié');
        }
        return new JsonResponse();
    }

}
