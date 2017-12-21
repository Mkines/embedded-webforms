<?php
// src/AppBundle/Entity/GuruContactFormView.php
/**
 * Description: this form is embedded inside of MyBC on the Clubs/Orgs page. It just gives student's an easy way to contact the student gurus directly from within MyBC to request help with or establish
 * a club or org site.
 */
namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class GuruContactFormView
{
    protected $db_controller;
    protected $bc_helper;

    private $firstName;
    private $lastName;
    private $email;
    private $clubOrgName;
    private $additionalNotes;

    public function __construct($db_controller, $bc_helper)
    {
        $this->db_controller = $db_controller;
        $this->bc_helper = $bc_helper;
    }


    /** Getters and Setters: **/
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
    public function getLastName()
    {
        return $this->lastName;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function setClubOrgName($clubOrgName)
    {
        $this->clubOrgName = $clubOrgName;
    }
    public function getClubOrgName()
    {
        return $this->clubOrgName;
    }

    public function setAdditionalNotes($additionalNotes)
    {
        $this->additionalNotes = $additionalNotes;
    }
    public function getAdditionalNotes()
    {
        return $this->additionalNotes;
    }
}