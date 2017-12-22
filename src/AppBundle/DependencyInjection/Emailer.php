<?php
/**
 * Tyler Weisman, Web Developer Bridgewater College
 * Emailer
 * Version 1.0
 * Description: responsible for routing and sending all emails based on system input to users.
 */
namespace AppBundle\DependencyInjection;
use Symfony\Component\HttpFoundation\RequestStack;

class Emailer
{
    protected $twig;
    protected $mailer;
    protected $bc_helper;
    protected $requestStack;

    private $sysAdminEmail = 'tweisman@bridgewater.edu';
    private $adminEmail1 = 'mheatwol@bridgewater.edu';


    /** Form Constructor **/
    public function __construct($twig, $bc_helper, \Swift_Mailer $mailer, RequestStack $requestStack)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->bc_helper = $bc_helper;
        $this->requestStack = $requestStack;
    }

    private function getCurrentRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /** Email Creation: **/
    private function renderEmail($templatePath, $customFields)
    {
        return $this->twig->render(
            $templatePath,
            $customFields
        );
    }
    private function buildEmailHeaders($subject, $from, $to, $body)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, 'text/html')
        ;

        return $message;
    }

    public function sendSpeakerRequestForm($valuesMap)
    {
        $customFields = $valuesMap;
        $body = $this->renderEmail('/email-templates/speaker-request-form-email-to-admin.html.twig', $customFields);
        $email = $this->buildEmailHeaders('Speaker Request Form Submitted', 'no-reply@bridgewater.edu', $this->adminEmail1, $body);

        $emailStatus = $this->mailer->send($email);
    }

    public function sendFloryHonorsEssay($MainEntity, $fileName)
    {
        $customFields = array('fullname' => $MainEntity->getFullname(), 'high_school' => $MainEntity->getHighSchool());
        $body = $this->renderEmail('/email-templates/flory-honors-essay-submitted-email.html.twig', $customFields);
        $email = $this->buildEmailHeaders('New Flory Honors Program Essay Submission', 'no-reply@bridgewater.edu', 'jduncan@bridgewater.edu', $body);

        $email->attach(\Swift_Attachment::fromPath('/var/www/html/symfony-apps/embedded-webforms/web/uploads/' . $fileName));

        $emailStatus = $this->mailer->send($email);
    }

    public function sendAcademicSupportRequestEmail($MainEntity)
    {
        $customFields = array('request_object' => $MainEntity);
        $body = $this->renderEmail('/email-templates/academic-coach-request-submitted.html.twig', $customFields);

        // Testing Server Check:
        if ($this->getCurrentRequest()->getHost() == 'gnar.bridgewater.edu') {
            $email = $this->buildEmailHeaders('Academic Coach Request Submitted', 'no-reply@bridgewater.edu', $this->sysAdminEmail, $body);
        } else {
            $email = $this->buildEmailHeaders('Academic Coach Request Submitted', 'no-reply@bridgewater.edu', $MainEntity->getMainAdminEmail(), $body);
            $email->addCc($MainEntity->getSecondaryAdminEmail());
            $email->addBcc($this->sysAdminEmail);
        }

        $emailStatus = $this->mailer->send($email);
    }

    public function sendAcademicSupportRequestStudentReceipt($MainEntity)
    {
        $customFields = array('request_object' => $MainEntity);
        $body = $this->renderEmail('/email-templates/academic-coach-request-receipt.html.twig', $customFields);

        // Testing Server Check:
        if ($this->getCurrentRequest()->getHost() == 'gnar.bridgewater.edu') {
            $email = $this->buildEmailHeaders('Academic Coach Request Received', 'no-reply@bridgewater.edu', $this->sysAdminEmail, $body);
        } else {
            $email = $this->buildEmailHeaders('Academic Coach Request Received', 'no-reply@bridgewater.edu', $MainEntity->getEmail(), $body);
        }

        $emailStatus = $this->mailer->send($email);
    }

    public function sendGuruContactRequestEmail($MainEntity)
    {
        $customFields = array('object' => $MainEntity);
        $body = $this->renderEmail('/email-templates/guru-contact-request-email.html.twig', $customFields);

        // Testing Server Check:
        if ($this->getCurrentRequest()->getHost() == 'gnar.bridgewater.edu') {
            $email = $this->buildEmailHeaders('Club/Org Guru Contact Request', 'no-reply@bridgewater.edu', $this->sysAdminEmail, $body);
        } else {
            $email = $this->buildEmailHeaders('Club/Org Guru Contact Request', 'no-reply@bridgewater.edu', 'gurus@bridgewater.edu', $body);
            $email->addCc('tweisman@bridgewater.edu', 'Tyler Weisman');
            $email->addCc('egoodwin@bridgewater.edu', 'Emily Goodwin');
        }

        $emailStatus = $this->mailer->send($email);
    }

    public function sendRefundRequestEmailToAdmin($dataModel)
    {
        $customFields = array('data_model' => $dataModel);
        $body = $this->renderEmail('/email-templates/refund-request-submitted.html.twig', $customFields);

        // Testing Server Check:
        if ($this->getCurrentRequest()->getHost() == 'gnar.bridgewater.edu') {
            $email = $this->buildEmailHeaders('Refund Request Submitted', 'no-reply@bridgewater.edu', $this->sysAdminEmail, $body);
        } else {
            $email = $this->buildEmailHeaders('Refund Request Submitted', 'no-reply@bridgewater.edu', 'brankin@bridgewater.edu', $body);
            $email->addBcc('tweisman@bridgewater.edu');
        }

        $emailStatus = $this->mailer->send($email);
    }

}
