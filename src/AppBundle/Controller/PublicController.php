<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

//Entity and Form Imports:
use AppBundle\DataModels\SpeakersBureauRequest;
use AppBundle\Forms\SpeakersBureauRequestForm;

use AppBundle\Entity\FloryHonorsProgramRequest;
use AppBundle\Forms\FloryHonorsProgramRequestForm;

use AppBundle\Entity\Views\GenericFormEntity;

class PublicController extends Controller
{
    /**
     * @Route ("/public/speakers-bureau-request-form", name="speakers-bureau-request-form")
     */
    public function speakersBureauRequestForm(Request $request, $title)
    {
        $twigPath = "public/embedded-forms/speakers-bureau-request-form.html.twig";

        $MainEntity = new SpeakersBureauRequest($this->get('bc_helper'));
        $MainForm = $this->createForm(SpeakersBureauRequestForm::class, $MainEntity, array(
        ));


        $MainForm->handleRequest($request);
        if ($MainForm->isSubmitted() && $MainForm->get('submit')->isClicked())
        {
            $formResponse = $MainEntity->speakerRequestSubmitted($MainForm);
            $this->get('emailer')->sendSpeakerRequestForm($MainEntity->getValuesMap());

            return $this->redirectToRoute('speakers-bureau-request-form');
        }
        else
        {
            //Page Rendering:
            return $this->render($twigPath, [
                //Required for every template page:
                'page_title'=>'Speakers Bureau Request Form',
                'current_path'=>$request->attributes->get('_route'),
                'bc_helper'=>$this->get('bc_helper'),
                'permissions'=>null,
                // Form Variables:
                'main_entity' => $MainEntity,
                'main_form' => $MainForm->createView(),
            ]);
        }
    }

    /**
     * @Route ("/public/flory-honors-program-request", name="flory-honors-program-request")
     * Physical Location:
     */
    public function floryHonorsProgramRequest(Request $request, $title)
    {
        /** Session Variables **/
        $sysMsg1 = $this->get('session')->get('sys-msg-1');
        $this->get('session')->remove('sys-msg-1');

        $MainEntity = new FloryHonorsProgramRequest($this->get('bc_helper'));
        $MainForm = $this->createForm(FloryHonorsProgramRequestForm::class, $MainEntity, array(
        ));


        $MainForm->handleRequest($request);
        if ($MainForm->isSubmitted() && $MainForm->get('submit')->isClicked())
        {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $MainEntity->getEssayAttached();
            $allowedFiles = array('pdf', 'doc', 'docx');
            // Generate a unique name for the file before saving it
            if (!in_array($file->guessExtension(), $allowedFiles)) {
                $formResponse = 'error-invalid-file-type';
            } else {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                // Move the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('file_upload_directory'),
                    $fileName
                );


                $this->get('emailer')->sendFloryHonorsEssay($MainEntity, $fileName);
                $formResponse = 'submission-successful';
            }

            $responseVars = array();
            $this->get('session')->set('sys-msg-1', $this->get('sys_messages')->getSysMsg($formResponse, $responseVars));
            return $this->redirectToRoute('flory-honors-program-request');
        }
        else
        {
            $twigPath = "public/embedded-forms/flory-honors-program-request.html.twig";
            //Page Rendering:
            return $this->render($twigPath, [
                //Required for every template page:
                'page_title'=>'',
                'current_path'=>$request->attributes->get('_route'),
                'bc_helper'=>$this->get('bc_helper'),
                'permissions'=>null,
                'sys_msg_1'=>$sysMsg1,
                // Form Variables:
                //'main_entity' => $MainEntity,
                'main_form' => $MainForm->createView(),
            ]);
        }
    }

    // Financial Aid and Finance Web Forms:
    /**
     * @Route ("/public/finance/refund-request", name="refund-request")
     * Physical Location:
     */
    public function refundRequest(Request $request, $title)
    {
        /** Variable Set-Up and Instantiation **/
        // Instantiate the User Object and Retrieve User Permissions (if not public):
        $currentRoute = $request->attributes->get('_route');

        /** SESSION and GET Variables: **/
        $sysMsg1 = $this->get('session')->get('sys-msg-1');
        $this->get('session')->remove('sys-msg-1');

        /** Check User Permissions to access the page: **/
        // public...

        /** Page View and Form Objects: **/
        $MainView = new GenericFormEntity(
            'AppBundle\DataModels\RefundRequest', '\AppBundle\Forms\RefundRequestForm',
            $this->get('db_controller'), $this->get('emailer'), $this->get('form.factory')
        );


        $MainView->getFormTemplate()->handleRequest($request);
        if ($MainView->getFormTemplate()->isSubmitted() && $MainView->getFormTemplate()->get('submitForm')->isClicked())
        {
            $MainView->formPost(array('validate'));
        }
        else
        {
            //Page Rendering:
            $twigPath = "public/embedded-forms/finance/refund-request.html.twig";
            return $this->render($twigPath, [
                //Required for every template page:
                'page_title'=>'Finance Office',
                'current_path'=>$currentRoute,
                'bc_helper'=>$this->get('bc_helper'),
                'permissions'=>null,
                //'side_navigation_v2'=>$this->get('side_nav'),
                // Page Specific:
                'sys_msg_1'=>$sysMsg1,
                'main_view' => $MainView,
            ]);
        }
    }
}