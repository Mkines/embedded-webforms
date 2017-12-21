<?php

namespace BCCustomSSOBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

//Entity and Form Imports:

class SSOMainController extends Controller
{
    public function loginAction(Request $request)
    {
        // Primary SSO Login Point:
        $as = $this->get('simplesamlphp_auth_object');
        $as->requireAuth();

        return $this->redirectToRoute('sso_main.login_check');
    }

    public function loginCheckAction(Request $request)
    {
        /**
         * Description: Lands here "logged in" and checks to see if the Symfony User Entity is created yet. If not it's going to create it from the data passed
         * in the single sign on assertion.
         */
        $as = $this->get('simplesamlphp_auth_object');
        if (!$as->isAuthenticated())
        {
            return $this->redirectToRoute('sso_main.login');
        }
    }

}