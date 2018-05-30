<?php

namespace AppBundle\Security\Authentication\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    protected
        $router,
        $security;

    public function __construct(Router $router, AuthorizationChecker $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // URL for redirect the user to where they were before the login process begun if you want.
        // $referer_url = $request->headers->get('referer');

        // Default target for unknown roles. Everyone else go there.
        $url = $this->router->generate('fos_user_security_login');

        if
        ($this->security->isGranted('ROLE_ADMIN')) {
            $url = $this->router->generate('back_office_homepage');
        } elseif ($this->security->isGranted('ROLE_CUSTOMER')) {
            $url = $this->router->generate('front_office_homepage');

        }

        $response = new RedirectResponse($url);

        return $response;
    }
}