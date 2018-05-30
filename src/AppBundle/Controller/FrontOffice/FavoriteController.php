<?php
namespace AppBundle\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Property;
use AppBundle\Entity\PropertyFavorite as BaseEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FavoriteController extends Controller
{

    /**
     * @Route("/profile/mes-favoris", name="front_my_favorite_properties")
     *
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:PropertyFavorite')->listByUser($this->getUser()->getId());

        $paginator = $this->get('knp_paginator');

        $entities = $paginator->paginate($qb, $request->query->get('page', 1), 20);

        return $this->render('frontOffice/property/my_favorites.html.twig', array(
                'entities' => $entities,
                'ids'=> $this->get('my_favorites.properties')->getFavorites()
            )
        );
    }

    /**
     * @Route("/favoris/new/{token}", name="front_favorite_new")
     *
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function newAction(Request $request, Property $property)
    {
        $entity = new BaseEntity();

        if ($request->isXmlHttpRequest() and $request->getMethod() == 'POST') {

            $em = $this->getDoctrine()->getManager();

            $nbFav = $em->getRepository('AppBundle:PropertyFavorite')->findOneBy(array(
                'user' => $this->getUser(), 'property' => $property
            ));
            if (!$nbFav) {
                $entity->setUser($this->getUser());
                $entity->setProperty($property);
                $em->persist($entity);
                $em->flush();
                $this->addFlash('success', 'Ajouté aux favoris');
            }
        }

        return new JsonResponse();
    }

    /**
     * @Route("/favoris/delete/{token}", name="front_favorite_remove")
     *
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function removeAction(Request $request, Property $property)
    {
        if ($request->isXmlHttpRequest() and $request->getMethod() == 'POST') {

            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:PropertyFavorite')->findOneBy(array(
                'user' => $this->getUser(), 'property' => $property
            ));

            if ($entity) {
                $em->remove($entity);
                $em->flush();
                $this->addFlash('error', 'Retiré de favoris');
            }
        }

        return new JsonResponse();
    }

}
