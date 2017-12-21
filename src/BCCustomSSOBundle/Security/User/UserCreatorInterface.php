<?php

namespace BCCustomSSOBundle\Security\User;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserCreatorInterface
{
    /**
     * @return UserInterface|null
     */
    public function createUser($simplesamlAuthTokenData);
}