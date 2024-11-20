<?php


// src/Middleware/CheckAuthenticatedMiddleware.php
namespace App\Middleware;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class CheckAuthenticatedMiddleware
{
    private $security;
    private $router;

    public function __construct(Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');
        $user = $this->security->getUser();

        // Vérifiez si l'utilisateur est connecté et essaie d'accéder à la page de login ou de register
        if ($user && in_array($routeName, ['app_login', 'app_register'])) {
            // Redirigez l'utilisateur vers la page d'accueil (ou toute autre page)
            $response = new RedirectResponse($this->router->generate('app_home'));
            $event->setResponse($response);
            return;
        }

        // Vérifiez si l'utilisateur n'a pas le rôle ADMIN et essaie d'accéder à l'adminpanel
        if ($routeName === 'app_admin' && $user && !in_array('ROLE_ADMIN', $user->getRoles())) {
            $response = new RedirectResponse($this->router->generate('app_home'));
            $event->setResponse($response);
            return;
        }
        
        if ($routeName === 'app_tasks_new' && $user && !in_array('ROLE_ADMIN', $user->getRoles())) {
            $response = new RedirectResponse($this->router->generate('app_home'));
            $event->setResponse($response);
            return;
        }
    }
}
