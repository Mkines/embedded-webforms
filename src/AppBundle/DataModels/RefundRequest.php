<?php
// src/AppBundle/DataModels/RefundRequest.php
namespace AppBundle\DataModels;
// DataMapper:
use AppBundle\DataAccessLayer\RefundRequestDataMapper;
use Symfony\Component\Validator\Constraints as Assert;

class RefundRequest
{
    // DOA:
    protected $dataMapper;

    // Variables:
    /**
     * @Assert\NotBlank(message = "Please enter your first name.")
     */
    private $firstName;

    /**
     * @Assert\NotBlank(message = "Please enter your last name.")
     */
    private $lastName;

    /**
     * Description: Check to ensure the studentID entered by the user is a 5-7 digit integer and no letters
     * @Assert\NotBlank(message = "Please enter your valid student ID number, listed on your student ID card.")
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z]+/",
     *     match=false,
     *     message="Invalid student ID number entered.")
     */
    private $studentId;
    private $term;

    /**
     * @Assert\NotBlank(message = "Please enter a valid U.S. dollar amount to be refunded. Do not enter a dollar sign or commas.")
     */
    private $refundAmount;
    private $akademosVoucher;
    private $refundProcess;
    private $refundProcessBoxNumber;
    private $refundProcessOther;
    private $refundProcessOptions = array();
    private $boxNumber;
    private $parentRefundFullname;
    private $alternatePayeeName;
    private $userSignature;

    public function __construct($db_controller, $validatorService)
    {
        $this->dataMapper = new RefundRequestDataMapper($this, $db_controller, $validatorService);
        $this->setRefundProcessOptions();
    }

    public function getDataMapper()
    {
        return $this->dataMapper;
    }

    /** Getters and Setters **/
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

    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;
    }
    public function getStudentId()
    {
        return $this->studentId;
    }

    public function setTerm($term)
    {
        $this->term = $term;
    }
    public function getTerm()
    {
        return $this->term;
    }

    public function setRefundAmount($refundAmount)
    {
        $this->refundAmount = $refundAmount;
    }
    public function getRefundAmount()
    {
        return $this->refundAmount;
    }

    public function setAkademosVoucher($akademosVoucher)
    {
        $this->akademosVoucher = $akademosVoucher;
    }
    public function getAkademosVoucher()
    {
        return $this->akademosVoucher;
    }

    public function setRefundProcess($refundProcess)
    {
        $this->refundProcess = $refundProcess;
    }
    public function getRefundProcess()
    {
        return $this->refundProcess;
    }

    public function setRefundProcessBoxNumber($refundProcessBoxNumber)
    {
        $this->refundProcessBoxNumber = $refundProcessBoxNumber;
    }
    public function getRefundProcessBoxNumber()
    {
        return $this->refundProcessBoxNumber;
    }

    public function setRefundProcessOther($refundProcessOther)
    {
        $this->refundProcessOther = $refundProcessOther;
    }
    public function getRefundProcessOther()
    {
        return $this->refundProcessOther;
    }

    public function setParentRefundFullname($parentRefundFullname)
    {
        $this->parentRefundFullname = $parentRefundFullname;
    }
    public function getParentRefundFullname()
    {
        return $this->parentRefundFullname;
    }

    public function setAlternatePayeeName($alternatePayeeName)
    {
        $this->alternatePayeeName = $alternatePayeeName;
    }
    public function getAlternatePayeeName()
    {
        return $this->alternatePayeeName;
    }

    public function setUserSignature($userSignature)
    {
        $this->userSignature = $userSignature;
    }
    public function getUserSignature()
    {
        return $this->userSignature;
    }

    // Option Arrays:
    public function setRefundProcessOptions()
    {
        $this->refundProcessOptions = $this->dataMapper->selectFinanceRefundRequestAddressOptions();
    }
    public function getRefundProcessOptions()
    {
        return $this->refundProcessOptions;
    }
}