<?php
// src/AppBundle/DependencyInjection/PermissionsRouter.php
/**
 * The Permissions Router is a class that is custom per application. This router is called from the main controller and redirects a user where they
 * belong based on current permissions and the page they are on.
 */

namespace AppBundle\DependencyInjection;

class PermissionsRouter
{
    /* Variable Set-up: */
    private $userPerms = array();

    public function loadUserPerms($permissions)
    {
        if ($permissions != false)
        {
            for ($i = 0; $i < count($permissions); $i++)
                array_push($this->userPerms, $permissions[$i]['perm_name']);
        }
    }   

    public function getUserPerms()
    {
        return $this->userPerms;
    }
        
    public function loginRedirect()
    {
        //Custom Login Redirects:
        $url = 'access-denied';

        if (in_array('example', $this->userPerms))
            $url = "/example-path";

        return $url;
    }

    public function checkPerms($currentRoute)
    {
        /**
         * Description: this function checks if the user is allowed to access a particular secured page they are trying to access... changes for every application.
         */
        $access = false;

        switch ($currentRoute) {
            case "example":
                $access = (in_array('ex', $this->userPerms) ? true : false);
                break;
        }

        return $access;
    }
}
