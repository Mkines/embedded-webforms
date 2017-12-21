<?php
// ..src/BCCustomSSOBundle/Security/User/UserCreator.php
namespace BCCustomSSOBundle\Security\User;

use BCCustomSSOBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;
use BCCustomSSOBundle\Security\User\UserCreatorInterface;

class UserCreator implements UserCreatorInterface
{
    /** @var ObjectManager */
    private $objectManager;
    private $mainDB; //passed as a service from the main config

    /**
     * @param ObjectManager           $objectManager
     */
    public function __construct(ObjectManager $objectManager, $j4)
    {
        $this->objectManager = $objectManager;
        $this->mainDB = $j4;
    }

    /**
     * @return UserInterface|null
     */
    public function createUser($simplesamlAuthTokenData)
    {
        $attributes = $simplesamlAuthTokenData['Attributes'];
        $username = $attributes['uid'][0];
        // If $Username is '' return false;
        if(!isset($username) || $username == ''){
            return false;
        }

        /**
         * Bridgewater Attributes
         */
        if (array_key_exists('http://schemas.bridgewater.edu/2017/03/employeeNumber', $attributes))
            $user_id = (is_array($attributes['http://schemas.bridgewater.edu/2017/03/employeeNumber'])) ? $attributes['http://schemas.bridgewater.edu/2017/03/employeeNumber'] : [];
        else
            $user_id = [];
        if (array_key_exists('description', $attributes))
            $usertype = (is_array($attributes['description'])) ? $attributes['description'] : [];
        else
            $usertype = [];
        if (array_key_exists('displayName', $attributes))
            $fullname = (is_array($attributes['displayName'])) ? $attributes['displayName'] : [];
        else
            $fullname = [];
        if (array_key_exists('lastName', $attributes))
            $lastName = (is_array($attributes['lastName'])) ? $attributes['lastName'] : [];
        else
            $lastName = [];

        //Create the new User and apply it's attributes from the response provided by the IDP
        $user = new User();
        $user
            ->setUsername($username)
            ->setRoles(['ROLE_USER'])
            ->setUserId($user_id[0])
            ->setUserType($usertype[0])
            ->setFullName($fullname[0])
            ->setLastName($lastName[0])
        ;

        $this->objectManager->persist($user);
        $this->objectManager->flush();

        //Create the User's Initial Permissions (custom per application):
        //$user->setFirstTimeUserPerms();

        return $user;
    }
}
