<?php
// src/AppBundle/DependencyInjection/SysMessages.php
/**
 * Description: used to call/display system messages based on the "name" of the controller page you're currently on.
 */
namespace AppBundle\DependencyInjection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SysMessages
{
    protected $mainDB;
    protected $controllerName;

    public function __construct($j4, RequestStack $requestStack)
    {
        $this->mainDB = $j4;
        // This is grabbing the controllers name for use
        $this->controllerName = $requestStack->getCurrentRequest()->attributes->get('_route');
    }

    public function getSysMsg($formResponse, $responseVars)
    {
        /**
         * Description: query sys_messages table and retrieve the msg_text to display on the screen for the user
         */
        $tempString = '';
        $sql = "SELECT msg_text FROM sys_messages WHERE controller_name = :controller_name AND form_response = :form_response";
        $data = $this->mainDB->select_db_row(array('controller_name'=>$this->controllerName,  "form_response"=>$formResponse), $sql);

        if (!empty($responseVars))
        {
            // Loop through the response vars and find any place holders in the message, replace the existing field with the actual data to display...
            foreach ($responseVars as $placeholder=>$text)
                $tempString = str_replace($placeholder, $text, $data[0]['msg_text']);
        }
        else
        {
            $tempString = $data[0]['msg_text'];
        }
        return $tempString;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }
}