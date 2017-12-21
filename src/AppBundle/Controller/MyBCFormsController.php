<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

//Entity and Form Imports:
use AppBundle\DataModels\AcademicCoachRequest;
use AppBundle\Forms\AcademicCoachRequestForm;

use AppBundle\Entity\GuruContactFormView;
use AppBundle\Forms\GuruContactForm;

class MyBCFormsController extends Controller
{
    // https://gnar.bridgewater.edu/webforms/...
    /**
     * @Route ("/secure/academic-coach-request-form", name="academic-coach-request-form")
     */
    public function academicCoachRequestForm(Request $request, $title)
    {
        /** Variable Set-Up and Instantiation **/
        // Instantiate the User Object and Retrieve User Permissions:
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $currentRoute = $request->attributes->get('_route');

        /** Session Variables **/
        $sysMsg1 = $this->get('session')->get('sys-msg-1');
        $this->get('session')->remove('sys-msg-1');

        /** Main Entity and Forms: **/
        $MainView = new AcademicCoachRequest($this->get('db_controller'), $this->get('bc_helper'), $user->getUsername());
        $MainForm = $this->createForm(AcademicCoachRequestForm::class, $MainView, array(
        ));

        /** Form Handling and Submission **/
        $MainForm->handleRequest($request);
        if (isset($MainForm) && $MainForm->isSubmitted() && $MainForm->get('submitRequest')->isClicked())
        {
            $MainView->setUserId($user->getUserId());
            $formResponse = $MainView->submitAcademicCoachRequest();
            if ($formResponse == 'academic-coach-request-submitted') {
                // Send an email to Chip and Joyce that a new request has been submitted
                $this->get('emailer')->sendAcademicSupportRequestEmail($MainView);
                $this->get('emailer')->sendAcademicSupportRequestStudentReceipt($MainView);
            }

            $responseVars = array();
            $this->get('session')->set('sys-msg-1', $this->get('sys_messages')->getSysMsg($formResponse, $responseVars));
            return $this->redirectToRoute('academic-coach-request-form');
        }
        else
        {
            $twigTemplate = 'secure/mybc/academic-coach-request-form.html.twig';
            return $this->render($twigTemplate, [
                //Required for every template page:
                'page_title'=>'Academic Coach Request Form',
                'current_path'=>$currentRoute,
                'bc_helper'=>$this->get('bc_helper'),
                'permissions'=>$user->getUserPermissions($this->get('db_controller')),
                'side_navigation'=>false,
                //'side_nav_options'=>$pageNav,
                'sys_msg_1'=>$sysMsg1,
                // Page Specific Variables:
                'main_form' => $MainForm->createView(),
            ]);
        }
    }

    /**
     * @Route ("/secure/guru-contact-form", name="guru-contact-form")
     */
    public function guruContactForm(Request $request, $title)
    {
        //Instantiate the User Object and Retrieve User Permissions:
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $currentRoute = $request->attributes->get('_route');

        /** Session Variables **/
        $sysMsg1 = $this->get('session')->get('sys-msg-1');
        $this->get('session')->remove('sys-msg-1');

        /** Main Entity and Forms: **/
        $MainView = new GuruContactFormView($this->get('db_controller'), $this->get('bc_helper'));
        $MainForm = $this->createForm(GuruContactForm::class, $MainView, array(
        ));

        /** Form Handling and Submission **/
        if (isset($MainForm))
            $MainForm->handleRequest($request);
        if (isset($MainForm) && $MainForm->isSubmitted() && $MainForm->get('submitRequest')->isClicked())
        {
            // Send an email to Gurus and Emily/Tyler as backup that a new request has been submitted
            $formResponse = 'guru-contact-request-submitted';
            $this->get('emailer')->sendGuruContactRequestEmail($MainView);

            $responseVars = array();
            $this->get('session')->set('sys-msg-1', $this->get('sys_messages')->getSysMsg($formResponse, $responseVars));
            return $this->redirectToRoute('guru-contact-form');
        }
        else
        {
            $twigTemplate = 'secure/mybc/guru-contact-form.html.twig';
            return $this->render($twigTemplate, [
                //Required for every template page:
                'page_title'=>'Request a Club/Org Website',
                'current_path'=>$currentRoute,
                'bc_helper'=>$this->get('bc_helper'),
                'permissions'=>$user->getUserPermissions($this->get('db_controller')),
                'side_navigation'=>false,
                //'side_nav_options'=>$pageNav,
                'sys_msg_1'=>$sysMsg1,
                // Page Specific Variables:
                'main_form' => $MainForm->createView(),
            ]);
        }
    }
}