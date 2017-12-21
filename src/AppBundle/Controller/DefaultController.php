<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

//Entity and Form Imports:


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    } 

    /**
     * Main Landing Page after login - redirects them where to go:
     * @Route("/secure/", name="login-landing")
     */
    public function mainLoginLanding(Request $request)
    {
        //Instantiate the User Object and Retrieve User Permissions:
        $userObject = $this->get('security.token_storage')->getToken()->getUser();
        return $this->redirect($this->get('perm_router')->loginRedirect($userObject->getUserPermissions($this->get('db_controller'))));
    }

    /**
     * Permissions Access Denied Landing Page:
     * @Route("/secure/access-denied", name="access-denied")
     */
    public function accessDenied(Request $request, $title)
    {
        //Instantiate the User Object and Retrieve User Permissions:
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $currentRoute = $request->attributes->get('_route');

        //Page Rendering:
        return $this->render('bc-templates/access-denied.html.twig', [
            //Required for every template page:
            'page_title'=>'',
            'current_path'=>$currentRoute,
            'bc_helper'=>$this->get('bc_helper'),
            'permissions'=>$user->getUserPermissions($this->get('db_controller')),
        ]);
    }       
}
