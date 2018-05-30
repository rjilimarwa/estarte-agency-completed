<?php

namespace AppBundle\Security\Authorization;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use AppBundle\Entity\User;
use AppBundle\Entity\Property as Entity;

// Voter class requires Symfony 2.8 or higher version
class PropertyVoter extends Voter
{
    const CREATE = 'create';
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::CREATE, self::VIEW, self::EDIT, self::DELETE))) {
            return false;
        }

        if (!$subject instanceof Entity) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // ROLE_SUPER_ADMIN can do anything! The power!
        if ($this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN'))) {
            return true;
        }

        // you know $subject is a Entity object, thanks to supports
        /** @var Entity $entity */
        $entity = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($entity, $user);
            case self::EDIT:
                return $this->canEdit($entity, $user);
            case self::DELETE:
                return $this->canDelete($entity, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Entity $entity, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($entity, $user)) {
            return true;
        }

        // the Entity object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return $entity->isActive();
    }

    private function canEdit(Entity $entity, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $entity->getUser();
    }

    private function canDelete(Entity $entity, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $entity->getUser();
    }
}