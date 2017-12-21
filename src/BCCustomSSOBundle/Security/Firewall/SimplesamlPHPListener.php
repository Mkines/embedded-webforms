<?php
namespace BCCustomSSOBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use BCCustomSSOBundle\Security\Authentication\Token\SimplesamlPHPAuthToken;


class SimplesamlPHPListener implements ListenerInterface
{
    protected $tokenStorage;
    protected $authenticationManager;
    protected $simplesaml_auth_object;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager, $simplesaml_auth_object)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->simplesaml_auth_object = $simplesaml_auth_object;
    }

    public function handle(GetResponseEvent $event)
    {
        // Runs on ever page load under the security context...
        $request = $event->getRequest();

        $token = new SimplesamlPHPAuthToken();
        $token->loadSimplesamlPHPAuthData($this->simplesaml_auth_object->getAuthDataArray());

        $authToken = $this->authenticationManager->authenticate($token);
        $this->tokenStorage->setToken($authToken);

        return;
    }
}