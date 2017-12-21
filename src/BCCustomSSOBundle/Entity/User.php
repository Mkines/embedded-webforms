<?php
// src/BCSSOBundle/Entity/User.php
namespace BCCustomSSOBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="single_sign_on_sessions.user")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $usertype;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $fullname;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $lastName;

    /**
     * @var array
     * @ORM\Column(type="json_array")
     */
    protected $roles;

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Bridgewater Specific Assertion Values
     *
     * Description: these functions set the various values that could be returned by the IDP in the assertion for use in the application.
     */

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param string $user_id
     *
     * @return User
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserType()
    {
        return $this->usertype;
    }

    /**
     * @param string $userType
     *
     * @return User
     */
    public function setUserType($usertype)
    {
        $this->usertype = $usertype;

        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullname;
    }

    /**
     * @param string $fullname
     *
     * @return User
     */
    public function setFullName($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }
    /**
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    //Seondary User Class Functions:
    //-----------------------------------------------------------------------------------------------------------------------
    public function getUserPermissions($mainDB)
    {
        /**
         * Description: this is an updated version of the user_permissions function. This will replace getUserPermissions($j4)
         * This version stores the user permissions as an array inside the user object for reference inside the controller or entity at anytime.
         */
        $userPermissions = array();
        // SQL SELECT:
        $sql = "SELECT * FROM user_permissions where user_id = :user_id";
        $queryData = $mainDB->select_db_row(array("user_id"=>$this->getUserId()), $sql);

        foreach ((array)$queryData as $i=>$row)
            array_push($userPermissions, $row['perm_name']);

        return $userPermissions;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * @return string|null
     */
    public function getSalt()
    {
        return '';
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([$this->user_id, $this->username, $this->roles]);
    }

    /**
     * @return void
     */
    public function unserialize($serialized)
    {
        list($this->user_id, $this->username, $this->roles) = unserialize($serialized);
    }

    /**
     * BC Specific Function: this grabs the user's permissions from the user_permissions table in the database. Returns it to the main
     *     controller as an array.
     */
    public function checkForUpdates($ssoToken, $db_controller)
    {
        /**
         * Description: this runs on every successful login where the user is found (not being created new) in the single_sign_on_sessions.user table. Once the data is loaded
         * into this class. Double check the user's attributes have not changed since the last time they logged in. If so UPDATE their information in the table.
         */
        // Check usertype -
        if($ssoToken['description'][0] != $this->getUsertype()) {
            $sql = "UPDATE single_sign_on_sessions.user SET usertype = :usertype WHERE user_id = :user_id";
            $db_controller->manipulate_db_row(array('usertype'=>$ssoToken['description'][0], 'user_id'=>$this->getUserId()), $sql);
        }
    }

    public function updateUserPermissions($j4)
    {
        /**
         * Description: checks the system for automated user permissions and adds/removes them on login depending on custom criteria
         */
        //todo: add query and criteria to the page
    }
}
