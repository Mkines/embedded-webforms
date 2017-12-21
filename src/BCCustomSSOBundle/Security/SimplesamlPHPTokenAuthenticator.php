<?php
namespace BCCustomSSOBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SimplesamlPHPTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;
    private $userCreator;
    private $simplesaml_auth_object;
    private $mainDB;
    // Redirect after login uri stuff: (this adds their requested url into the session and grabs it from the requestStack)
    private $session;
    protected $requestStack;

    private $failMessage = 'Invalid credentials';

    /**
     * Creates a new instance of FormAuthenticator
     */
    public function __construct(RequestStack $requestStack, RouterInterface $router, $userCreator, $simplesaml_auth_object, $container, $mainDB) {
        $this->router = $router;
        $this->userCreator = $userCreator;
        $this->simplesaml_auth_object = $simplesaml_auth_object;
        $this->mainDB = $mainDB;

        $this->session = $container->get('session');
        $this->requestStack = $requestStack;
    }

    public function getCredentials(Request $request)
    {
        // Means they hit the "login" screen and were redirected from there to single sign on. After successful login they should end up back here...
        if ($request->getPathInfo() == '/login-check')
        {
            // Successfully Logged into SSO at least, now create their user object in Symfony from the returned information
            return $this->simplesaml_auth_object->getAuthDataArray();
        }
        else
        {
            // Means it's just a normal page and not a login page...
            return null;
        }
    }

    public function getUser($simplesamlAuthTokenData, UserProviderInterface $userProvider)
    {
        if (isset($simplesamlAuthTokenData['saml:AuthenticatingAuthority']))
        {
            try {
                $user = $userProvider->loadUserByUsername($simplesamlAuthTokenData['Attributes']['uid'][0]);
                $user->checkForUpdates($simplesamlAuthTokenData['Attributes'], $this->mainDB);
                $user->updateUserPermissions($this->mainDB);  // update their user permissions (in-case) any are created automatically by the system
            } catch (UsernameNotFoundException $e) {
                // If the Username was not found, create the user in the database
                $user = $this->userCreator->createUser($simplesamlAuthTokenData);
                $user->updateUserPermissions($this->mainDB);  // update their user permissions (in-case) any are created automatically by the system
            }

            return $user;
        }
        else
        {
            //return error...
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($user->getUsername() == $credentials['Attributes']['uid'][0])
        {
            return true;
        }throw new CustomUserMessageAuthenticationException($this->failMessage);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $requestedUrl = $this->session->get('userRequestedUrl');
        if (isset($requestedUrl))
        {
            $url = $requestedUrl;
        }
        else
        {
            // Send them to the default target path which in this case is "secure":
            $url = $this->router->generate('default_simplesaml_php_success_path');
        }

        return new RedirectResponse($url);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $request = $this->requestStack->getCurrentRequest();
        $this->session->set('userRequestedUrl', $request->getRequestUri());

        $url = $this->router->generate('sso_main.login');
        return new RedirectResponse($url);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
