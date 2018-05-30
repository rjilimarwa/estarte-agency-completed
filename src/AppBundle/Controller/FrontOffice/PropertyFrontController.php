<?php

namespace AppBundle\Controller\FrontOffice;

use AppBundle\Form\ContactOwnerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Property as BaseEntity;
use AppBundle\Form\Property\PropertyFrontSearchType as BaseSearchType;
use AppBundle\Entity\Property;

class PropertyFrontController extends Controller
{

    /**
     * @Route("/annonces", name="front_list_all_properties")
     */
    public function listAllAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $qb = $em->getRepository('AppBundle:Property')->listAllFront();
        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 24);

        return $this->render('frontOffice/property/index_grid.html.twig', array(
                'entities' => $entities,
                'ids' => $this->get('my_favorites.properties')->getFavorites(),
            )
        );
    }

    /**
     * @Route("/annonces/vendre/{category_slug}", name="front_list_for_sale_properties")
     */
    public function forSaleAction(Request $request, $category_slug)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->findOneBySlug($category_slug);
        $paginator = $this->get('knp_paginator');
        $qb = $em->getRepository('AppBundle:Property')->listForSale($category->getId());
        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 24);

        return $this->render('frontOffice/property/index_grid.html.twig', array(
                'entities' => $entities,
                'category' => $category,
                'ids' => $this->get('my_favorites.properties')->getFavorites(),
            )
        );
    }

    /**
     * @Route("/annonces/location/{category_slug}", name="front_list_for_rent_properties")
     */
    public function forRentAction(Request $request, $category_slug)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->findOneBySlug($category_slug);
        $paginator = $this->get('knp_paginator');
        $qb = $em->getRepository('AppBundle:Property')->listForRent($category->getId());
        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 24);

        return $this->render('frontOffice/property/index_grid.html.twig', array(
                'entities' => $entities,
                'category' => $category,
                'ids' => $this->get('my_favorites.properties')->getFavorites(),
            )
        );
    }

    /**
     * @Route("/annonces/type/{category_slug}", name="front_list_by_category__properties")
     */
    public function listByCategoryAction(Request $request, $category_slug)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->findOneBySlug($category_slug);
        $paginator = $this->get('knp_paginator');
        $qb = $em->getRepository('AppBundle:Property')->listByCategory($category->getId());
        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 24);

        return $this->render('frontOffice/property/index_grid.html.twig', array(
                'entities' => $entities,
                'category' => $category,
                'ids' => $this->get('my_favorites.properties')->getFavorites(),
            )
        );
    }

    /**
     * Show entity
     *
     * @Route("/annonces/details/{slug}", name="front_office_property_show")
     */
    public function showAction(BaseEntity $entity)
    {
        /*if($entity->getActive() == null or $entity->getState() != 'valid'){
            throw $this->createNotFoundException('Le bien n\'existe pas ou bien il est désactivé');
        }*/

        $em = $this->getDoctrine()->getManager();
        $category = $entity->getCategory()->getId();
        $similarProperties = $em->getRepository('AppBundle:Property')->similarProperties($entity->getId(), $category);
        $entity->setCountViews($entity->getCountViews() + 1);
        $statistic = $em->getRepository('AppBundle:Statistic')->findOneBy(array('alias'=> 'tot-vue-an'));
        $statistic->setValue($statistic->getValue() + 1);
        $em->flush();

        return $this->render('frontOffice/property/show.html.twig', array(
            'entity' => $entity,
            'similarProperties' => $similarProperties,
            'ids' => $this->get('my_favorites.properties')->getFavorites()

        ));
    }

    /**
     * Render the search form
     * @Route("/annonces/formulaire-du-recherche-vertical", name="front_properties_vertical_search_form")
     */
    public function verticalSearchFormAction(Request $request)
    {
        $entity = new BaseEntity();
        $form = $this->createForm(BaseSearchType::class, $entity);
        $form->handleRequest($request);
        return $this->render('frontOffice/property/form_vertical_search.html.twig', array(
            'form_search' => $form->createView(),
        ));
    }

    /**
     * Render the search form
     * @Route("/annonces/formulaire-du-recherche-horizontal", name="front_properties_horizontal_search_form")
     */
    public function horizontalSearchFormAction(Request $request)
    {
        $entity = new BaseEntity();
        $form = $this->createForm(BaseSearchType::class, $entity);
        $form->handleRequest($request);
        return $this->render('frontOffice/property/form_horizontal_search.html.twig', array(
            'form_search' => $form->createView(),
        ));
    }

    /**
     * Render the search form
     * @Route("/annonces/formulaire-du-recherche-horizontal-mini", name="front_properties_horizontal_mini_search_form")
     */
    public function horizontalMiniSearchFormAction(Request $request)
    {
        $entity = new BaseEntity();
        $form = $this->createForm(BaseSearchType::class, $entity);
        $form->handleRequest($request);
        return $this->render('frontOffice/property/form_horizontal_mini_search.html.twig', array(
            'form_search_mini' => $form->createView(),
        ));
    }

    /**
     * Search entities by specific properties
     *
     * @Route("/annonces/chercher", name="front_properties_search")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $data = $request->query->all();
        $qb = $em->getRepository('AppBundle:Property')->searchFront($data);

        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 30);

        if ($request->isXmlHttpRequest()) {
            $view = $this->renderView('frontOffice/property/index_grid.html.twig', array(
                'entities' => $entities,
                'ids' => $this->get('my_favorites.properties')->getFavorites()
            ));
            $response = new Response($view);

            return $response;
        }

        return $this->render('frontOffice/property/index_grid.html.twig', array(
            'entities' => $entities,
            'ids' => $this->get('my_favorites.properties')->getFavorites()
        ));
    }

    /**
     * @Route("/annonces/formulaire-du-contact", name="front_properties_contact_owner_form")
     */
    public function contactOwnerFormAction(Request $request, BaseEntity $entity)
    {
        $form = $this->createForm(ContactOwnerType::class);
        $form->handleRequest($request);
        return $this->render('frontOffice/property/form_contact_owner.html.twig', array(
            'form_contact_owner' => $form->createView(),
            'entity'=> $entity
        ));
    }

    /**
     * @Route("/annonces/contact/{id}", name="front_property_contact_owner")
     */
    public function contactAction(Request $request, Property $property)
    {
        $form = $this->createForm(ContactOwnerType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $view = $this->renderView('frontOffice/property/form_contact_owner.html.twig', array(
                'form_contact_owner' => $form->createView(),
                'entity'=> $property
            ));
            if ($form->isValid()) {
                $subject = $form->get('fullName')->getData().' vous contacte pour votre annonce sur immobiliere.tn';
                $message = \Swift_Message::newInstance()
                    ->setSubject($subject.': '.$property->getTitle())
                    ->setFrom(array(
                        $this->getParameter('mailer_user') => $this->getParameter('sender_name')
                    ))
                    ->setTo($property->getUser()->getEmail())
                    ->setReplyTo($form->get('email')->getData())
                    ->setBody(
                        $this->renderView(
                            'emails/contact_owner.html.twig', array(
                                'name' => $form->get('fullName')->getData(),
                                'tel'=> $form->get('phone')->getData(),
                                'subject'=> $subject,
                                'message'=> $form->get('message')->getData(),
                                'entity'=> $property
                            )
                        ),
                        'text/html'
                    )
                ;
                $this->get('mailer')->send($message);
                
                return new JsonResponse(array(
                    'view' => $view,
                    'result'=> 1,
                    'message'=> 'Votre message a été envoyé.'
                    ));

            } else {
                return new JsonResponse(array(
                    'view' => $view,
                    'result' => 0,
                    'message' => 'Formulaire non valide',
                ));
            }
        }

        return $this->redirectToRoute('front_office_property_show', array('slug' => $property->getSlug()));
    }

    /**
     * @Route("/annonces/liste-xml", name="front_property_list_xml")
     */
    public function xmlAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Property')->findBy(array('active'=>1, 'state'=>'valid'), array('id' => 'desc'));

        $header = '<?xml version="1.0" encoding="utf-8"?>';

        $html = $this->renderView('frontOffice/property/xml_list.xml.twig', array(
            'entities' => $entities,
            'h' => $header
        ));
        $response = new Response($html);
        $response->headers->set('Content-Type', 'xml');
        //$response->headers->set('Content-Type', 'application/rss+xml; charset=UTF-8');
        return $response;
    }

    /**
     * @Route("/annonces/formulaire-du-recherche/liste-ville", name="front_properties_get_villes")
     */
    public function getVillesAction(Request $request)
    {
        $gouvernorat = $_GET['slug'];
        $em = $this->getDoctrine()->getManager();
        $villes = $em->getRepository('AppBundle:Area')->listByCity($gouvernorat);
        $view = $this->renderView('frontOffice/property/liste_villes.html.twig', array(
            'villes' => $villes
        ));
        return new JsonResponse($view);
    }

    /**
     * @Route("/annonces/formulaire-du-recherche/liste-ville-mini", name="front_properties_get_villes_mini")
     */
    public function getVillesMiniAction(Request $request)
    {
        $gouvernorat = $_GET['slug'];
        $em = $this->getDoctrine()->getManager();
        $villes = $em->getRepository('AppBundle:Area')->listByCity($gouvernorat);
        $view = $this->renderView('frontOffice/property/liste_villes_mini.html.twig', array(
            'villes' => $villes
        ));
        return new JsonResponse($view);
    }

    /**
     * @Route("/profile/mes-annonces/ajouter/formulaire-ajout/liste-villes", name="front_properties_get_villes_ajout")
     */
    public function getVillesFormAjoutAction(Request $request)
    {
        $gouvernorat = $_GET['id'];
        $em = $this->getDoctrine()->getManager();
        $villes = $em->getRepository('AppBundle:Area')->listByCityId($gouvernorat);
        $view = $this->renderView('frontOffice/property/liste_villes_form_ajout.html.twig', array(
            'villes' => $villes
        ));
        return new JsonResponse($view);
    }
}
