<?php
namespace AppBundle\Listener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MyFavorites
{
    protected
        $em,
        $security,
        $token;

    public function __construct(EntityManager $em, AuthorizationChecker $security, TokenStorageInterface $token)
    {
        $this->em = $em;
        $this->security = $security;
        $this->token = $token;
    }

    public function getFavorites()
    {
        if ($this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $user = $this->token->getToken()->getUser();
            $em = $this->em;
            $favorites = $em->getRepository('AppBundle:PropertyFavorite')->findByUser($user); // select favorite of this user

            $ids = array_map(function ($entity) {
                return $entity->getProperty()->getId();
            }, $favorites); // get ids of dishes in favorite
        } else {
            $ids = array(); // if user is not AUTHENTICATED_REMEMBERED show ids are null
        }

        return $ids;
    }


}
