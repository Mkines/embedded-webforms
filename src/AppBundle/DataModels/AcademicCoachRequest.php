<?php
// src/AppBundle/DataModels/AcademicCoachRequest.php
/**
 * Description: this form is embedded on the following page of MyBC:
 *
 */
namespace AppBundle\DataModels;

class AcademicCoachRequest
{
    protected $db_controller;
    protected $bc_helper;

    private $mainAdminEmail = 'cstudwell@bridgewater.edu';
    private $secondaryAdminEmail = 'jeller@bridgewater.edu';

    private $userId;
    private $firstName;
    private $lastName;
    private $email;
    private $cellPhone;
    private $yearInCollege;
    private $major;
    private $reasonForCoachRequest;
    private $recommendation;
    private $coachRequestNames;

    public function __construct($db_controller, $bc_helper, $currentUserEmail)
    {
        $this->db_controller = $db_controller;
        $this->bc_helper = $bc_helper;

        $this->setEmail($currentUserEmail);
    }

    public function submitAcademicCoachRequest()
    {
        //$this->valuesMap = $this->bc_helper->setValuesMap($formData, $this);
        //print_r($this->valuesMap);
        $sql = "INSERT INTO academic_coach_requests (user_id, first_name, last_name, cell_phone, email, year_in_college, major, reason_for_coach_request, recommendation, coach_request_names, request_date)
                VALUES(:user_id, :first_name, :last_name, :cell_phone, :email, :year_in_college, :major, :reason_for_coach_request, :recommendation, :coach_request_names, NOW())";
        $this->db_controller->persist_class_data($this, $sql, false);

        return "academic-coach-request-submitted";
    }

    /** Getters and Setters: **/
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    public function getUserId()
    {
        return $this->userId;
    }

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

    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $cellPhone;
    }
    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    public function setYearInCollege($yearInCollege)
    {
        $this->yearInCollege = $yearInCollege;
    }
    public function getYearInCollege()
    {
        return $this->yearInCollege;
    }

    public function setMajor($major)
    {
        $this->major = $major;
    }
    public function getMajor()
    {
        return $this->major;
    }

    public function setReasonForCoachRequest($reasonForCoachRequest)
    {
        $this->reasonForCoachRequest = $reasonForCoachRequest;
    }
    public function getReasonForCoachRequest()
    {
        return $this->reasonForCoachRequest;
    }

    public function setRecommendation($recommendation)
    {
        $this->recommendation = $recommendation;
    }
    public function getRecommendation()
    {
        return $this->recommendation;
    }

    public function setCoachRequestNames($coachRequestNames)
    {
        $this->coachRequestNames = $coachRequestNames;
    }
    public function getCoachRequestNames()
    {
        return $this->coachRequestNames;
    }

    public function getMainAdminEmail()
    {
        return $this->mainAdminEmail;
    }
    public function getSecondaryAdminEmail()
    {
        return $this->secondaryAdminEmail;
    }
}