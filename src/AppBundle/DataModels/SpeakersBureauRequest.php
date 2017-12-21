<?php
// src/AppBundle/DataModels/SpeakersBureauRequest.php
/**
 * Description: this form is embedded on the following page of the main website: https://www.bridgewater.edu/events-news/speakers
 * It just takes user input requesting a faculty member to be a guest speaker somewhere from the attached pdf and sends the request to Mary Kay Heatwole via email.
 */
namespace AppBundle\DataModels;

class SpeakersBureauRequest
{
    protected $bc_helper;
    private $formFields = array('org', 'orgRep', 'addressLine1', 'addressLine2', 'city', 'state', 'zipcode', 'phone', 'email',
                                'typeOfEvent', 'requestedDate', 'requestedTime', 'estimatedAudience', 'estimatedAudience', 'eventLocation',
                                'speakerFirstChoice', 'topicFirstChoice', 'speakerSecondChoice', 'topicSecondChoice', 'comments');
    private $valuesMap;

    private $org;
    private $orgRep;
    private $addressLine1;
    private $addressLine2;
    private $city;
    private $state;
    private $zipcode;
    private $phone;
    private $email;
    private $typeOfEvent;
    private $requestedDate;
    private $requestedTime;
    private $estimatedAudience;
    private $eventLocation;
    private $speakerFirstChoice;
    private $topicFirstChoice;
    private $speakerSecondChoice;
    private $topicSecondChoice;
    private $comments;

    public function __construct($bc_helper)
    {
        $this->bc_helper = $bc_helper;
    }

    private function setValuesMap($formData, $formFieldArray)
    {
        $valuesMap = array();
        foreach ((array)$formFieldArray as $formField) {
            // Convert the formField into a tableField for the database
            $tableField = $this->bc_helper->convert_camel_to_db_key($formField);
            //echo $tableField."</br>";
            $valuesMap[$tableField] = $formData[$formField]->getData();
        }

        $this->valuesMap = $valuesMap;
    }

    /** Form Processing/Functionality **/
    public function speakerRequestSubmitted($formData)
    {
        $this->setValuesMap($formData, $this->formFields);
        print_r($this->valuesMap);
    }

    /** Getters and Setters **/
    public function getValuesMap()
    {
        return $this->valuesMap;
    }

    public function setOrg($org)
    {
        $this->org = $org;
    }
    public function getOrg()
    {
        return $this->org;
    }

    public function setOrgRep($orgRep)
    {
        $this->orgRep = $orgRep;
    }
    public function getOrgRep()
    {
        return $this->orgRep;
    }

    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
    }
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
    }
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }
    public function getCity()
    {
        return $this->city;
    }

    public function setState($state)
    {
        $this->state = $state;
    }
    public function getState()
    {
        return $this->state;
    }

    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }
    public function getZipcode()
    {
        return $this->zipcode;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    public function getPhone()
    {
        return $this->phone;
    }

    public function setTypeOfEvent($typeOfEvent)
    {
        $this->typeOfEvent = $typeOfEvent;
    }
    public function getTypeOfEvent()
    {
        return $this->typeOfEvent;
    }

    public function setRequestedDate($requestedDate)
    {
        $this->requestedDate = $requestedDate;
    }
    public function getRequestedDate()
    {
        return $this->requestedDate;
    }

    public function setRequestedTime($requestedTime)
    {
        $this->requestedTime = $requestedTime;
    }
    public function getRequestedTime()
    {
        return $this->requestedTime;
    }

    public function setEstimatedAudience($estimatedAudience)
    {
        $this->estimatedAudience = $estimatedAudience;
    }
    public function getEstimatedAudience()
    {
        return $this->estimatedAudience;
    }

    public function setEventLocation($eventLocation)
    {
        $this->eventLocation = $eventLocation;
    }
    public function getEventLocation()
    {
        return $this->eventLocation;
    }

    public function setSpeakerFirstChoice($speakerFirstChoice)
    {
        $this->speakerFirstChoice = $speakerFirstChoice;
    }
    public function getSpeakerFirstChoice()
    {
        return $this->speakerFirstChoice;
    }

    public function setTopicFirstChoice($topicFirstChoice)
    {
        $this->topicFirstChoice = $topicFirstChoice;
    }
    public function getTopicFirstChoice()
    {
        return $this->topicFirstChoice;
    }

    public function setSpeakerSecondChoice($speakerSecondChoice)
    {
        $this->speakerSecondChoice = $speakerSecondChoice;
    }
    public function getSpeakerSecondChoice()
    {
        return $this->speakerSecondChoice;
    }

    public function setTopicSecondChoice($topicSecondChoice)
    {
        $this->topicSecondChoice = $topicSecondChoice;
    }
    public function getTopicSecondChoice()
    {
        return $this->topicSecondChoice;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }
    public function getComments()
    {
        return $this->comments;
    }
}